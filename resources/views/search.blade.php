<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Vaccine Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-semibold text-center mb-4">Check Vaccination Status</h1>

    <form action="{{ route('search.result') }}" method="POST" class="space-y-4">
        @csrf

        <div class="flex flex-col space-y-2">
            <label for="nid" class="text-gray-700 font-medium">Enter NID</label>
            <input type="text" name="nid" id="nid" placeholder="Your NID" required
                   class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                   value="{{ old('nid') }}">
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-md font-semibold transition duration-300">
            Check Status
        </button>
    </form>

    @if(isset($status))
        <div class="mt-4 p-4 rounded-md bg-{{ $status === 'Vaccinated' ? 'green' : ($status === 'Scheduled' ? 'yellow' : 'red') }}-100">
            <p class="text-gray-800 font-semibold text-center">
                Status: {{ $status }}
                @if($status === 'Scheduled')
                    <br>
                    Your vaccination date is: <strong>{{ $scheduled_date->format('d M, Y') }}</strong>
                @endif

                @if($status === 'Not registered')
                    <br>
                    <span class="pt-4"><a href="/register" class="!text-blue-600">Register Now!</a></span>
                @endif
            </p>
        </div>
    @else
        <p class="pt-4 text-center">Not Registered? <a href="/register" class="!text-blue-600">Register Now!</a></p>
    @endif
</div>

</body>
</html>
