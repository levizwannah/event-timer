@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-blue-50 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-blue-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Event Time Planner</h1>
            <p class="text-gray-500 mt-2">
                Seamlessly plan and time your event agenda.
            </p>
        </div>

        {{-- Create New Program --}}
        <a href="{{ route('programs.create') }}"
           class="block w-full bg-blue-600 text-white font-semibold py-3 rounded-lg text-center hover:bg-blue-700 transition border border-blue-700">
            ➕ Create New Program
        </a>

        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-3 text-gray-400 text-sm">or</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        {{-- Join Existing Program --}}
        <form action="{{ route('programs.showByCode') }}" method="GET" class="space-y-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                    Enter Program Code
                </label>
                <input type="text" name="code" id="code" placeholder="e.g., ABC123"
                       class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 text-center uppercase transition" />
            </div>
            <button type="submit"
                    class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg border border-gray-300 hover:bg-gray-200 transition shadow-sm">
                🔍 Open Program
            </button>
        </form>
    </div>
</div>
@endsection
