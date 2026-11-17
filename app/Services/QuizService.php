<?php

namespace App\Services;

use App\Models\LearningRule;
use App\Models\Organism;
use App\Models\QuizPuzzle;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class QuizService
{
    public const TYPES = ['pilihan-ganda', 'tebak-gambar', 'mencocokkan', 'puzzle'];

    public function start(string $type): array
    {
        abort_unless(in_array($type, self::TYPES, true), 404);
        $game = ['token' => (string) Str::uuid(), 'type' => $type, 'answers' => [], 'feedback' => []];
        if (in_array($type, ['pilihan-ganda', 'tebak-gambar'])) {
            $game['ids'] = QuizQuestion::where('type', $type)->inRandomOrder()->limit(5)->pluck('id')->all();
        } elseif ($type === 'mencocokkan') {
            $game['ids'] = Organism::inRandomOrder()->limit(5)->pluck('id')->all();
        } else {
            $puzzle = QuizPuzzle::inRandomOrder()->firstOrFail();
            $game['ecosystem_id'] = $puzzle->ecosystem_id;
            $game['puzzle_id'] = $puzzle->id;
            do {
                $tiles = range(0, 8);
                shuffle($tiles);
            } while ($tiles === range(0, 8));
            $game['tiles'] = $tiles;
        }

        return $game;
    }

    public function answer(array &$game, int $index, string $answer): array
    {
        if ($index !== count($game['answers']) || ! isset($game['ids'][$index])) {
            throw ValidationException::withMessages(['answer' => 'Jawab soal sesuai urutan.']);
        }
        if ($game['type'] === 'mencocokkan') {
            $o = Organism::with('ecosystems')->findOrFail($game['ids'][$index]);
            $f = ['correct' => $o->ecosystems->contains('slug', $answer), 'answer' => $o->ecosystems->pluck('name')->join(', '), 'explanation' => $o->content['habitat']];
        } else {
            $q = QuizQuestion::findOrFail($game['ids'][$index]);
            if (! in_array($answer, $q->options, true)) {
                throw ValidationException::withMessages(['answer' => 'Pilihan tidak valid.']);
            }
            $f = ['correct' => $answer === $q->answer, 'answer' => $q->answer, 'explanation' => $q->explanation];
        }
        $game['answers'][] = $answer;
        $game['feedback'][] = $f;

        return $f;
    }

    public function finish(array $game, array $moves, ?User $user): array
    {
        if ($game['type'] === 'puzzle') {
            $tiles = $game['tiles'];
            foreach ($moves as $move) {
                if (! is_array($move) || count($move) !== 2 || ! is_int($move[0]) || ! is_int($move[1]) || min($move) < 0 || max($move) > 8) {
                    throw ValidationException::withMessages(['moves' => 'Gerakan puzzle tidak valid.']);
                }
                [$a,$b] = $move;
                [$tiles[$a],$tiles[$b]] = [$tiles[$b], $tiles[$a]];
            }
            if (! $moves || $tiles !== range(0, 8)) {
                throw ValidationException::withMessages(['moves' => 'Puzzle belum tersusun.']);
            }
            $score = 100;
        } else {
            if (count($game['answers']) !== count($game['ids']) || ! count($game['ids'])) {
                throw ValidationException::withMessages(['quiz' => 'Selesaikan seluruh soal.']);
            }
            $score = (int) round(100 * count(array_filter($game['feedback'], fn ($f) => $f['correct'])) / count($game['ids']));
        }
        $points = 0;
        if ($user) {
            DB::transaction(function () use ($game, $moves, $user, $score, &$points) {
                User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                $a = $user->attempts()->firstOrCreate(['token' => $game['token']], ['type' => $game['type'], 'score' => $score, 'answers' => $game['type'] === 'puzzle' ? $moves : $game['answers']]);
                if ($a->wasRecentlyCreated) {
                    $max = $game['type'] === 'puzzle' ? LearningRule::valueFor('puzzle') : (int) floor($score * LearningRule::valueFor('quiz_max') / 100);
                    $old = (int) $user->points()->where('source', 'like', 'quiz:'.$game['type'].':%')->sum('points');
                    $points = max(0, $max - $old);
                    if ($points) {
                        $user->points()->create(['source' => 'quiz:'.$game['type'].':'.$game['token'], 'activity' => 'Kuis '.str_replace('-', ' ', $game['type']), 'points' => $points]);
                    }
                }
                app(ProgressService::class)->badges($user);
            });
        }

        return compact('score','points');
    }
}
