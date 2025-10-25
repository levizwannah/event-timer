<?php

namespace App\Http\Controllers;

use App\Models\Agendum;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgendumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'duration' => 'required|integer|min:0',
        ]);

        // Determine order
        $nextOrder = $program->agenda()->max('order') + 1;

        $program->agenda()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'],
            'order' => $nextOrder,
        ]);

        return redirect()->route('programs.show', $program)->with('success', 'Agendum added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendum $agendum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agendum $agendum)
    {
        //
    }

    public function update(Request $request, Program $program, Agendum $agendum)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'duration' => 'required|integer|min:0',
            'order' => 'nullable|integer|min:1',
        ]);

        // Optional: ensure agendum belongs to program
        if ($agendum->program_id !== $program->id) {
            abort(404);
        }

        $agendum->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'],
            'order' => $validated['order'] ?? $agendum->order,
        ]);

        return redirect()->route('programs.show', $program)->with('success', 'Agendum updated.');
    }

    public function destroy(Program $program, Agendum $agendum)
    {
        // Ensure agendum belongs to program
        if ($agendum->program_id !== $program->id) {
            abort(404);
        }

        $agendum->delete();

        Session::flash('success', "Agendum deleted");

        return response()->json(compact('agendum'));
    }

    public function startAgendum(Program $program, Agendum $agendum)
    {
        if (is_null($agendum->started_at)) {
            $agendum->update(['started_at' => now()]);
        }

        // Optionally store in localStorage via JS
        return redirect()->route('programs.run', ['program' => $program, 'agendum' => $agendum]);
    }
}
