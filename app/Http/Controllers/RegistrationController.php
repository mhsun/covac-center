<?php

namespace App\Http\Controllers;

use App\Http\Requests\VaccineRegistrationRequest;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('register', [
            'centers' => VaccineCenter::all(['id', 'name']),
        ]);
    }

    public function store(VaccineRegistrationRequest $request): RedirectResponse
    {
        try {
            User::create($request->validated());

            return redirect(route('search'))->with('message', 'Registered successfully!');
        } catch (\Exception $e) {
            return redirect(route('register'))->with('message', 'Registration failed!');
        }
    }
}
