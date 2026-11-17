<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Ecosystem;
use App\Models\LearningRule;
use App\Models\Organism;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    public function complete(User $user, string $activity, string $subject): void
    {
        DB::transaction(function () use ($user, $activity, $subject) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $p = $user->progress()->firstOrCreate(compact('activity', 'subject'));
            if ($p->wasRecentlyCreated) {
                $user->points()->firstOrCreate(['source' => $activity.':'.$subject], ['activity' => ['basic' => 'Materi dasar', 'ecosystem' => 'Belajar ekosistem', 'organism' => 'Belajar organisme', 'comparison' => 'Perbandingan pertama', 'conservation' => 'Materi pelestarian'][$activity], 'points' => LearningRule::valueFor($activity)]);
            }
            $this->badges($user);
        });
    }

    public function badges(User $user): void
    {
        $has = fn ($a) => $user->progress()->where('activity', $a)->count();
        $org = $user->progress()->where('activity', 'organism')->pluck('subject');
        $conditions = [
            'pembelajar-aktif' => $has('basic') > 0,
            'pengenal-ekosistem' => $has('ecosystem') >= Ecosystem::count(),
            'pengenal-flora' => Organism::whereIn('slug', $org)->where('category', 'flora')->count() >= 3,
            'pengenal-fauna' => Organism::whereIn('slug', $org)->where('category', 'fauna')->count() >= 3,
            'ahli-perbandingan' => $has('comparison') > 0,
            'jago-kuis' => $user->attempts()->where('score', '>=', 80)->exists(),
            'penyusun-puzzle' => $user->attempts()->where('type', 'puzzle')->where('score', 100)->exists(),
            'pelestari-lahan-basah' => $has('conservation') >= Ecosystem::count(),
        ];
        foreach ($conditions as $slug => $earned) {
            if ($earned && $b = Badge::where('slug', $slug)->first()) {
                $user->badges()->syncWithoutDetaching([$b->id]);
            }
        }
        if ($user->attempts()->distinct()->count('type') >= 4) {
            $this->award($user, 'serbabisa', 'Penjelajah Serbabisa — selesaikan 4 jenis kuis');
        }
        if ($user->total_points >= 200) {
            $this->award($user, 'bintang', 'Bintang Belajar — kumpulkan 200 poin');
        }
    }

    private function award(User $user, string $slug, string $name): void
    {
        DB::table('user_awards')->insertOrIgnore(['user_id' => $user->id, 'slug' => $slug, 'name' => $name, 'created_at' => now(), 'updated_at' => now()]);
    }
}
