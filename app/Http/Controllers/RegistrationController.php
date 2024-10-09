<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\VaccineRegistrationRequest;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('register', [
            'centers' => Cache::remember('vaccine_centers', now()->addHour(), function () {
                return VaccineCenter::all();
            }),
        ]);
    }

    public function store(VaccineRegistrationRequest $request): RedirectResponse
    {
        try {
            $user = User::create($request->validated());

            UserRegistered::dispatch($user->load('vaccineCenter'));

            return redirect(route('search'))->with('message', 'Registered successfully!');
        } catch (\Exception $e) {
            report($e);

            return redirect(route('register'))->with('message', 'Registration failed!');
        }
    }
}
