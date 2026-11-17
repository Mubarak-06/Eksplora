<?php

namespace Tests\Feature;

use App\Models\Ecosystem;
use App\Models\Organism;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Services\ProgressService;
use App\Services\QuizService;
use Database\Seeders\EksploraSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LearningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EksploraSeeder::class);
    }

    public function test_all_public_pages_and_slugs_render(): void
    {
        $urls = ['/', '/eksplorasi', '/tentang-lahan-basah', '/flora-fauna', '/flora-fauna?category=fauna', '/perbandingan', '/kuis', '/masuk', '/daftar', '/pencarian?q=haruan'];
        foreach (Ecosystem::all() as $e) {
            $urls[] = '/eksplorasi/'.$e->slug;
        }
        foreach (Organism::all() as $o) {
            $urls[] = '/flora-fauna/'.$o->slug;
        }
        foreach (QuizService::TYPES as $type) {
            $urls[] = '/kuis/'.$type;
        }
        foreach ($urls as $url) {
            $this->get($url)->assertOk()->assertSee('Perbandingan');
        }
        $this->get('/eksplorasi/tidak-ada')->assertNotFound();
        $this->get('/flora-fauna/tidak-ada')->assertNotFound();
        $this->get('/kuis/tidak-ada')->assertNotFound();
    }

    public function test_catalog_combines_category_habitat_and_scientific_name_search(): void
    {
        $this->get('/flora-fauna?category=fauna&ecosystem=sungai&q=Channa')->assertOk()->assertSee('Ikan Haruan')->assertDontSee('Ikan Papuyu');
        $this->get('/flora-fauna?category=flora&ecosystem=sungai&q=Channa')->assertOk()->assertSee('Belum ada hasil');
        $this->get('/pencarian?q=rawa')->assertOk()->assertSee('Rawa Gambut');
    }

    public function test_comparison_rejects_same_ecosystem_and_changes_data(): void
    {
        $this->get('/perbandingan?first=sungai&second=sungai')->assertSessionHasErrors('second');
        $this->get('/perbandingan?first=rawa-gambut&second=sungai')->assertOk()->assertSee('cenderung asam');
    }

    public function test_guests_cannot_save_or_view_private_data(): void
    {
        foreach (['/profil', '/koleksi'] as $url) {
            $this->get($url)->assertRedirect('/masuk');
        }
        $this->post('/progres', ['activity' => 'basic', 'subject' => 'tentang-lahan-basah'])->assertRedirect('/masuk');
        $this->patch('/profil', ['name' => 'Nama'])->assertRedirect('/masuk');
    }

    public function test_register_login_update_and_logout(): void
    {
        $this->post('/daftar', ['name' => 'Siswa', 'email' => 'siswa@example.test', 'password' => 'aman12345', 'password_confirmation' => 'aman12345'])->assertRedirect('/profil');
        $user = User::first();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('aman12345', $user->password));
        $this->patch('/profil', ['name' => 'Penjelajah'])->assertSessionHas('status');
        $this->assertSame('Penjelajah', $user->fresh()->name);
        $this->post('/keluar')->assertRedirect('/');
        $this->assertGuest();
        $this->post('/masuk', ['email' => 'siswa@example.test', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/masuk', ['email' => 'siswa@example.test', 'password' => 'aman12345'])->assertRedirect('/profil');
        $this->assertAuthenticated();
    }

    public function test_registration_validates_and_profile_does_not_edit_other_users(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $this->post('/daftar', ['name' => '', 'email' => $a->email, 'password' => 'x', 'password_confirmation' => 'y'])->assertSessionHasErrors(['name', 'email', 'password']);
        $this->actingAs($a)->patch('/profil', ['name' => 'Baru', 'user_id' => $b->id, 'email' => 'changed@example.test'])->assertRedirect();
        $this->assertSame('Baru', $a->fresh()->name);
        $this->assertNotSame('Baru', $b->fresh()->name);
        $this->get('/profil')->assertOk()->assertDontSee($b->email);
        $this->get('/koleksi')->assertOk();
    }

    public function test_reading_requires_explicit_completion_and_only_rewards_once(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->get('/tentang-lahan-basah')->assertOk();
        $this->assertSame(0, $u->total_points);
        for ($i = 0; $i < 3; $i++) {
            $this->post('/progres', ['activity' => 'basic', 'subject' => 'tentang-lahan-basah', 'points' => 9999])->assertRedirect();
        }
        $this->assertSame(20, $u->total_points);
        $this->assertSame(1, $u->progress()->count());
        $this->assertSame(1, $u->badges()->count());
        $this->post('/progres', ['activity' => 'ecosystem', 'subject' => 'made-up'])->assertStatus(422);
        $this->post('/progres', ['activity' => 'comparison', 'subject' => 'first', 'first' => 'sungai', 'second' => 'sungai'])->assertStatus(422);
    }

    public function test_badges_are_based_on_distinct_real_activities(): void
    {
        $u = User::factory()->create();
        $service = app(ProgressService::class);
        foreach (Ecosystem::all() as $e) {
            $service->complete($u, 'ecosystem', $e->slug);
            $service->complete($u, 'conservation', $e->slug);
        }
        foreach (Organism::all() as $o) {
            $service->complete($u, 'organism', $o->slug);
        }
        $service->complete($u, 'comparison', 'first');
        $service->complete($u, 'basic', 'tentang-lahan-basah');
        $this->assertSame(6, $u->badges()->count());
        $this->assertSame(190, $u->total_points);
        $this->assertSame(2, $u->level);
    }

    public function test_quiz_score_is_server_calculated_and_repeated_results_do_not_farm_points(): void
    {
        $u = User::factory()->create();
        $s = app(QuizService::class);
        for ($try = 0; $try < 2; $try++) {
            $g = $s->start('pilihan-ganda');
            foreach ($g['ids'] as $index => $id) {
                $s->answer($g, $index, QuizQuestion::find($id)->answer);
            }
            $result = $s->finish($g, [], $u);
            $this->assertSame(100, $result['score']);
            $s->finish($g, [], $u);
        }
        $this->assertSame(20, $u->total_points);
        $this->assertSame(2, $u->attempts()->count());
        $this->assertTrue($u->badges()->where('slug', 'jago-kuis')->exists());
    }

    public function test_guest_quiz_endpoints_feedback_score_and_replay_protection(): void
    {
        $start = $this->postJson('/permainan/tebak-gambar/mulai')->assertOk();
        $g = session('game');
        $start->assertJsonMissingPath('questions.0.answer');
        foreach ($g['ids'] as $i => $id) {
            $this->postJson('/permainan/jawab', ['token' => $g['token'], 'index' => $i, 'answer' => QuizQuestion::find($id)->answer])->assertOk()->assertJson(['correct' => true]);
        }
        $this->postJson('/permainan/selesai', ['token' => $g['token'], 'score' => 0])->assertOk()->assertJson(['score' => 100, 'saved' => false, 'points' => 0]);
        $this->postJson('/permainan/selesai', ['token' => $g['token']])->assertStatus(419);
        $this->assertDatabaseCount('quiz_attempts', 0);
    }

    public function test_incomplete_and_out_of_order_answers_are_rejected(): void
    {
        $this->postJson('/permainan/pilihan-ganda/mulai')->assertOk();
        $g = session('game');
        $this->postJson('/permainan/jawab', ['token' => $g['token'], 'index' => 1, 'answer' => 'x'])->assertUnprocessable();
        $this->postJson('/permainan/selesai', ['token' => $g['token'], 'score' => 100])->assertUnprocessable();
    }

    public function test_wrong_answers_score_zero(): void
    {
        $s = app(QuizService::class);
        $g = $s->start('tebak-gambar');
        foreach ($g['ids'] as $i => $id) {
            $q = QuizQuestion::find($id);
            $wrong = collect($q->options)->first(fn ($o) => $o !== $q->answer);
            $f = $s->answer($g, $i, $wrong);
            $this->assertFalse($f['correct']);
        }
        $this->assertSame(0, $s->finish($g, [], null)['score']);
    }

    public function test_matching_accepts_every_linked_habitat(): void
    {
        $s = app(QuizService::class);
        $o = Organism::where('slug', 'ikan-haruan')->first();
        foreach ($o->ecosystems as $e) {
            $g = ['type' => 'mencocokkan', 'ids' => [$o->id], 'answers' => [], 'feedback' => []];
            $this->assertTrue($s->answer($g, 0, $e->slug)['correct']);
        }
        $g = ['type' => 'mencocokkan', 'ids' => [$o->id], 'answers' => [], 'feedback' => []];
        $this->assertFalse($s->answer($g, 0, 'hutan-mangrove')['correct']);
    }

    public function test_puzzle_verifies_move_sequence_and_awards_once(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->postJson('/permainan/puzzle/mulai')->assertOk();
        $g = session('game');
        $this->postJson('/permainan/selesai', ['token' => $g['token'], 'score' => 100, 'moves' => []])->assertUnprocessable();
        $tiles = $g['tiles'];
        $moves = [];
        for ($i = 0; $i < 9; $i++) {
            if ($tiles[$i] !== $i) {
                $j = array_search($i, $tiles, true);
                $moves[] = [$i, $j];
                [$tiles[$i],$tiles[$j]] = [$tiles[$j], $tiles[$i]];
            }
        }
        $this->postJson('/permainan/selesai', ['token' => $g['token'], 'moves' => $moves])->assertOk()->assertJson(['score' => 100, 'points' => 15, 'saved' => true]);
        $this->assertTrue($u->badges()->where('slug', 'penyusun-puzzle')->exists());
    }

    public function test_seed_is_repeatable_and_all_images_exist(): void
    {
        $this->seed(EksploraSeeder::class);
        $this->assertDatabaseCount('ecosystems', 5);
        $this->assertDatabaseCount('organisms', 7);
        $this->assertDatabaseCount('badges', 8);
        foreach (Ecosystem::all()->concat(Organism::all()) as $item) {
            $this->assertFileExists(public_path($item->image));
        }
        foreach (Ecosystem::all() as $e) {
            $this->assertTrue($e->organisms()->where('category', 'flora')->exists());
            $this->assertTrue($e->organisms()->where('category', 'fauna')->exists());
        }
    }

    public function test_all_eight_badges_and_two_awards_can_be_earned(): void
    {
        $u = User::factory()->create();
        $p = app(ProgressService::class);
        $s = app(QuizService::class);
        $p->complete($u, 'basic', 'tentang-lahan-basah');
        $p->complete($u, 'comparison', 'first');
        foreach (Ecosystem::all() as $e) {
            $p->complete($u, 'ecosystem', $e->slug);
            $p->complete($u, 'conservation', $e->slug);
        }
        foreach (Organism::all() as $o) {
            $p->complete($u, 'organism', $o->slug);
        }
        foreach (QuizService::TYPES as $type) {
            $g = $s->start($type);
            $moves = [];
            if ($type === 'puzzle') {
                $tiles = $g['tiles'];
                for ($i = 0; $i < 9; $i++) {
                    if ($tiles[$i] !== $i) {
                        $j = array_search($i, $tiles, true);
                        $moves[] = [$i, $j];
                        [$tiles[$i],$tiles[$j]] = [$tiles[$j], $tiles[$i]];
                    }
                }
            } else {
                foreach ($g['ids'] as $i => $id) {
                    $answer = $type === 'mencocokkan' ? Organism::find($id)->ecosystems->first()->slug : QuizQuestion::find($id)->answer;
                    $s->answer($g, $i, $answer);
                }
            }
            $this->assertSame(100, $s->finish($g, $moves, $u)['score']);
        }
        $this->assertSame(8, $u->badges()->count());
        $this->assertSame(265,$u->total_points);
        $this->assertSame(3,$u->level);
        $this->assertSame(2,DB::table('user_awards')->where('user_id',$u->id)->count());
        $this->actingAs($u)->get('/koleksi')->assertOk()->assertSee('Didapatkan')->assertSee('Penjelajah Serbabisa');
    }
}
