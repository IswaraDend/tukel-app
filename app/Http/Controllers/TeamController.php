<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Ambil semua team milik user yang login.
     */
    public function index()
    {
        $teams = Team::where('owner_id', auth()->id())
            ->with('members')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($teams);
    }

    /**
     * Buat team baru dengan anggota.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'members' => 'required|array|min:1',
            'members.*.name' => 'required|string|max:100',
            'members.*.email' => 'nullable|email|max:100',
        ]);

        $team = Team::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'owner_id' => auth()->id(),
        ]);

        foreach ($validated['members'] as $m) {
            TeamMember::create([
                'id' => Str::uuid(),
                'team_id' => $team->id,
                'member_name' => $m['name'],
                'member_email' => $m['email'] ?? null,
            ]);
        }

        return response()->json($team, 201);
    }
}
