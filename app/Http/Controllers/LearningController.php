<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Ecosystem;
use App\Models\Organism;
use App\Services\ProgressService;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LearningController extends Controller
{
    public function home()
    {
        return view('pages.home', ['stats' => [Ecosystem::count(), Organism::count(), count(QuizService::TYPES), Badge::count()]]);
    }

    public function exploration()
    {
        return view('pages.exploration', ['ecosystems' => Ecosystem::all()]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function ecosystem(Ecosystem $ecosystem)
    {
        return view('pages.ecosystem', compact('ecosystem'));
    }

    public function organisms(Request $r)
    {
        $r->validate(['category' => ['nullable', Rule::in(['flora', 'fauna'])], 'q' => 'nullable|string|max:100', 'ecosystem' => 'nullable|string|max:100']);
        $category = $r->input('category', 'flora');
        $query = Organism::where('category', $category);
        if ($q = $r->input('q')) {
            $query->where(fn ($b) => $b->where('name', 'like', '%'.$q.'%')->orWhere('scientific_name', 'like', '%'.$q.'%'));
        }
        if ($slug = $r->input('ecosystem')) {
            $query->whereHas('ecosystems', fn ($b) => $b->where('slug', $slug));
        }

        return view('pages.organisms', ['organisms' => $query->get(), 'ecosystems' => Ecosystem::all(), 'category' => $category]);
    }

    public function organism(Organism $organism)
    {
        return view('pages.organism', compact('organism'));
    }

    public function comparison(Request $r)
    {
        $r->validate(['first' => 'nullable|exists:ecosystems,slug', 'second' => 'nullable|exists:ecosystems,slug|different:first']);
        $ecosystems = Ecosystem::all();
        $first = $ecosystems->firstWhere('slug', $r->input('first', 'rawa-lebak'));
        $second = $ecosystems->firstWhere('slug', $r->input('second', 'hutan-mangrove'));

        return view('pages.comparison', compact('ecosystems', 'first', 'second'));
    }

    public function search(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:100']);
        $q = trim($r->input('q', ''));

        return view('pages.search', ['q' => $q, 'ecosystems' => $q ? Ecosystem::where('name', 'like', '%'.$q.'%')->get() : collect(), 'organisms' => $q ? Organism::where(fn ($b) => $b->where('name', 'like', '%'.$q.'%')->orWhere('scientific_name', 'like', '%'.$q.'%'))->get() : collect()]);
    }

    public function complete(Request $r, ProgressService $service)
    {
        $d = $r->validate(['activity' => ['required', Rule::in(['basic', 'ecosystem', 'organism', 'comparison', 'conservation'])], 'subject' => 'required|string|max:100', 'first' => 'nullable|string', 'second' => 'nullable|string']);
        $valid = match ($d['activity']) {
            'basic' => $d['subject'] === 'tentang-lahan-basah',
            'ecosystem','conservation' => Ecosystem::where('slug', $d['subject'])->exists(),
            'organism' => Organism::where('slug', $d['subject'])->exists(),
            'comparison' => $d['subject'] === 'first' && isset($d['first'],$d['second']) && $d['first'] !== $d['second'] && Ecosystem::whereIn('slug', [$d['first'], $d['second']])->count() === 2,
        };
        abort_unless($valid, 422);
        $service->complete($r->user(), $d['activity'], $d['subject']);

        return back()->with('status', 'Progres tersimpan! Poin diberikan untuk aktivitas baru.');
    }

    public function collection(Request $r)
    {
        return view('pages.collection', ['user' => $r->user(), 'badges' => Badge::all(), 'earned' => $r->user()->badges()->pluck('badges.id'), 'transactions' => $r->user()->points()->latest()->paginate(20), 'awards' => DB::table('user_awards')->where('user_id', $r->user()->id)->get()]);
    }

    public function profile(Request $r)
    {
        return view('pages.profile', ['user' => $r->user(), 'history' => $r->user()->progress()->latest()->limit(15)->get()]);
    }
}
