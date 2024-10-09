<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function showSearchPage()
    {
        return view('search');
    }

    public function search(Request $request): View
    {
        $request->validate(['nid' => 'required|numeric']);

        $nid = $request->input('nid');

        $status = Cache::remember("user_status_$nid", now()->addMinutes(10), function () use ($nid) {
            $user = User::where('nid', $nid)->first();

            if (! $user) {
                return ['status' => 'Not registered'];
            }

            if ($user->vaccination_date) {
                return $user->vaccination_date->isPast()
                    ? ['status' => 'Vaccinated']
                    : ['status' => 'Scheduled', 'date' => $user->vaccination_date];
            }

            return ['status' => 'Not scheduled'];
        });

        return view('search', [
            'status' => $status['status'],
            'scheduled_date' => $status['date'] ?? null,
        ]);
    }
}
