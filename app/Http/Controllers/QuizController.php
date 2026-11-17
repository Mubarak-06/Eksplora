<?php

namespace App\Http\Controllers;

use App\Models\Ecosystem;
use App\Models\Organism;
use App\Models\QuizQuestion;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return view('pages.quizzes');
    }

    public function play(string $type)
    {
        abort_unless(in_array($type, QuizService::TYPES, true), 404);

        return view('pages.play', compact('type'));
    }

    public function start(Request $r, string $type, QuizService $s)
    {
        $g = $s->start($type);
        $r->session()->put('game', $g);
        $p = ['token' => $g['token'], 'type' => $type];
        if ($type === 'puzzle') {
            $p['tiles'] = $g['tiles'];
            $p['image'] = asset(Ecosystem::findOrFail($g['ecosystem_id'])->image);
        } elseif ($type === 'mencocokkan') {
            $p['questions'] = collect($g['ids'])->map(fn ($id) => Organism::findOrFail($id))->map(fn ($o) => ['question' => 'Pilih salah satu habitat yang sesuai untuk '.$o->name, 'image' => asset($o->image), 'options' => Ecosystem::all()->map(fn ($e) => ['value' => $e->slug, 'label' => $e->name])]);
        } else {
            $p['questions'] = collect($g['ids'])->map(fn ($id) => QuizQuestion::findOrFail($id))->map(fn ($q) => ['question' => $q->question, 'image' => $q->image ? asset($q->image) : null, 'options' => collect($q->options)->map(fn ($v) => ['value' => $v, 'label' => $v])]);
        }

        return response()->json($p);
    }

    private function game(Request $r): array
    {
        $g = $r->session()->get('game');
        abort_unless($g && hash_equals($g['token'], (string) $r->input('token')), 419, 'Sesi permainan berubah. Mulai ulang kuis.');

        return $g;
    }

    public function answer(Request $r, QuizService $s)
    {
        $d = $r->validate(['index' => 'required|integer|min:0', 'answer' => 'required|string|max:200']);
        $g = $this->game($r);
        abort_if($g['type'] === 'puzzle', 422);
        $f = $s->answer($g, (int) $d['index'], $d['answer']);
        $r->session()->put('game', $g);

        return response()->json($f);
    }

    public function finish(Request $r, QuizService $s)
    {
        $r->validate(['moves' => 'sometimes|array|max:500', 'moves.*' => 'array|size:2', 'moves.*.*' => 'integer|min:0|max:8']);
        $result = $s->finish($this->game($r), $r->input('moves', []), $r->user());
        $r->session()->forget('game');

        return response()->json($result + ['saved' => (bool) $r->user()]);
    }
}
