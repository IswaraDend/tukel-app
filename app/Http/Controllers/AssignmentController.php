<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Distribution;
use App\Models\Question;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['team.members', 'questions', 'distributions'])
            ->where('created_by', auth()->id()) 
            ->get();

        $assignments->transform(function ($assignment) {
            $scoreTotal = $assignment->distributions
                ->where('is_confirmed', true)
                ->sum(fn($d) => optional($d->question)->weight);

            $scoreMax = $assignment->questions->sum('weight');

            return [
                'id' => $assignment->id,
                'team' => $assignment->team,
                'questions_count' => $assignment->questions->count(),
                'score_total' => $scoreTotal,
                'score_max' => $scoreMax,
                'created_at' => $assignment->created_at,
            ];
        });

        return response()->json($assignments);
    }

    public function create()
    {
        return Inertia::render('Assignments/Create');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load([
            'team.members:id,team_id,member_name',
            'questions:id,assignment_id,question_index,question_text,weight',
            'distributions.member:id,member_name',
        ]);

        $totalScore = $assignment->distributions()
            ->where('is_confirmed', true)
            ->join('questions', 'distributions.question_id', '=', 'questions.id')
            ->sum('questions.weight');

        $maxScore = $assignment->questions()->sum('weight');

        return Inertia::render('Assignments/Show', [
            'assignment' => $assignment,
            'team' => $assignment->team,
            'questions' => $assignment->questions,
            'distributions' => $assignment->distributions->map(function ($d) {
                return [
                    'id' => $d->id,
                    'question_id' => $d->question_id,
                    'member_name' => $d->member?->member_name,
                    'is_confirmed' => $d->is_confirmed,
                ];
            }),
            'score' => [
                'total' => $totalScore,
                'max' => $maxScore,
            ],
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|uuid|exists:teams,id',
            'title'   => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.weight' => 'nullable|numeric|min:0',
        ], [
            'team_id.required' => 'Team harus dipilih.',
            'questions.required' => 'Minimal 1 pertanyaan.',
            'questions.*.text.required' => 'Teks pertanyaan wajib diisi.',
        ]);

        $owned = Team::where('id', $validated['team_id'])
            ->where('owner_id', auth()->id())
            ->exists();

        if (!$owned) {
            return response()->json(['message' => 'Anda tidak berhak pada team ini'], 403);
        }

        $assignment = Assignment::create([
            'id'         => Str::uuid(),
            'team_id'    => $validated['team_id'],
            'title'      => $validated['title'],
            'notes'      => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        $index = 1;
        foreach ($validated['questions'] as $q) {
            $text = trim($q['text'] ?? '');
            if ($text === '') continue;

            Question::create([
                'id'             => Str::uuid(),
                'assignment_id'  => $assignment->id,
                'question_index' => $index++,
                'question_text'  => $text,
                'weight'         => isset($q['weight']) ? floatval($q['weight']) : 1.0,
            ]);
        }

        $assignment->load(['team:id,name', 'questions:id,assignment_id,question_index,question_text']);

        return response()->json([
            'message'    => 'Assignment created',
            'assignment' => $assignment,
        ], 201);
    }

    public function distributionPage(Assignment $assignment)
    {
        $assignment->load(['questions', 'team.members']);

        return Inertia::render('Assignments/Distribution', [
            'assignment' => $assignment,
            'questions' => $assignment->questions,
            'members' => $assignment->team->members,
        ]);
    }

    public function saveDistribution(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'pairs' => 'required|array',
            'pairs.*.question_id' => 'required|uuid|exists:questions,id',
            'pairs.*.member_id' => 'required|uuid|exists:team_members,id',
        ]);

        Distribution::where('assignment_id', $assignment->id)->delete();

        foreach ($validated['pairs'] as $p) {
            Distribution::create([
                'id' => Str::uuid(),
                'assignment_id' => $assignment->id,
                'question_id' => $p['question_id'],
                'member_id' => $p['member_id'],
                'status' => 'assigned',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Distribution saved successfully');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully']);
    }
}
