<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function update(Request $request, Distribution $distribution)
    {
        $validated = $request->validate([
            'is_confirmed' => 'required|boolean',
        ]);

        $distribution->update(['is_confirmed' => $validated['is_confirmed']]);
        return response()->json(['message' => 'Updated']);
    }
}
