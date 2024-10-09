@extends('layout')

@section('title')
    <title>Vaccine Registration</title>
@endsection

@section('content')

<div class="bg-white p-8 rounded shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-semibold text-center mb-6">Vaccine Registration</h1>

    @if(session('message'))
        <div class="mb-4 p-4 rounded-md bg-green-100">
            <p class="text-center text-green-700 font-semibold">{{ session('message') }}</p>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
        @csrf
        @honeypot

        <!-- Name Input -->
        <div class="flex flex-col space-y-2">
            <label for="name" class="text-gray-700 font-medium">Full Name</label>
            <input type="text" name="name" id="name" placeholder="Your full name" required
                   class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                   value="{{ old('name') }}">

            @error('name')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Input -->
        <div class="flex flex-col space-y-2">
            <label for="email" class="text-gray-700 font-medium">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Your email" required
                   class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                   value="{{ old('email') }}">

            @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- NID Input -->
        <div class="flex flex-col space-y-2">
            <label for="nid" class="text-gray-700 font-medium">National ID (NID)</label>
            <input type="number" name="nid" id="nid" placeholder="Your NID" required
                   class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                   value="{{ old('nid') }}">

            @error('nid')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Vaccine Center Dropdown -->
        <div class="flex flex-col space-y-2">
            <label for="vaccine_center_id" class="text-gray-700 font-medium">Select Vaccine Center</label>
            <select name="vaccine_center_id" id="vaccine_center_id" required
                    class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Select a center</option>
                @foreach($centers as $center)
                    <option value="{{ $center->id }}" {{ old('vaccine_center_id') == $center->id ? 'selected' : '' }}>
                        {{ $center->name }}
                    </option>
                @endforeach
            </select>

            @error('vaccine_center_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-md font-semibold transition duration-300">
            Register
        </button>
    </form>

    <p class="pt-4 text-center">Registered? <a href="{{ route('search') }}" class="!text-blue-600">Check Status Now!</a></p>
</div>

@endsection
