<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function update(Request $request, Distribution $distribution)
    {
        $value = $request->input('is_confirmed');

        $distribution->is_confirmed = $value;
        $distribution->save();

        return response()->json([
            'message' => 'Updated successfully',
            'distribution' => $distribution->fresh(),
        ]);
    }
}
