@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-blue-50 px-4">
    <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-blue-600 mb-2 text-center">Create a New Program</h1>
        <p class="text-gray-500 text-center mb-6">Start by naming your event program.</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('programs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Program Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 border border-blue-100"
                       placeholder="e.g., Annual General Meeting">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                Create Program
            </button>

            <a href="{{ url('/') }}" class="block text-center text-sm text-gray-500 hover:underline">
                ← Back to Home
            </a>
        </form>
    </div>
</div>
@endsection
