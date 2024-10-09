<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchController extends Controller
{
    public function showSearchPage()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['nid' => 'required|string']);

        $user = User::where('nid', $validated['nid'])->first();

        if (!$user) {
            return view('search', ['status' => 'Not registered']);
        }

        if ($user->scheduled_date) {
            if (Carbon::now()->gt($user->scheduled_date)) {
                $status = 'Vaccinated';
            } else {
                $status = 'Scheduled';
            }
        } else {
            $status = 'Not scheduled';
        }

        return view('search', [
            'status' => $status,
            'scheduled_date' => $user->scheduled_date
        ]);
    }
}
