<?php

namespace App\Http\Controllers;

use App\Models\Agendum;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $program = Program::create([
            'title' => $request->title,
            'code' => strtoupper(Str::random(6)),
        ]);

        $program->code  = $program->id . "-" . $program->code;
        $program->save();

        return redirect()->route('programs.show', $program);
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function search()
    {
        return view('programs.search');
    }

    public function showByCode(Request $request)
    {
        $code = $request->get('code');

        $program = Program::where('code', trim($code))->first();

        if (empty($program)) {
            return redirect()->route('home')->with('global-error', "Could not find the program");
        }

        return redirect()->route('programs.show', $program);
    }

    public function start(Program $program)
    {
        $program->update(['started_at' => now()]);
        return redirect()->route('programs.run', $program);
    }

    public function run(Program $program, ?Agendum $agendum = null)
    {
        if ($program->hasEnded()) {
            return redirect()->route('programs.show', $program);
        }

        // If no specific agenda is passed, start with the first one
        $currentAgendum = $agendum ?? $program->agenda()
            ->orderBy('started_at', 'desc')
            ->orderBy('order')          
            ->first();

        // Get the previous and next agenda based on 'order'
        $prevAgendum = $program->agenda()
            ->where('order', '<=', $currentAgendum->order)
            ->whereNot('id', $currentAgendum->id)
            ->orderBy('order', 'desc')
            ->first();

        $nextAgendum = $program->agenda()
            ->where('order', '>=', $currentAgendum->order)
            ->whereNot('id', $currentAgendum->id)
            ->orderBy('order', 'asc')
            ->first();

        if ($prevAgendum) {
            $prevAgendum->update(['ended_at' => now()]);
        }

        return view('programs.run', [
            'program' => $program,
            'currentAgendum' => $currentAgendum,
            'prevAgendum' => $prevAgendum,
            'nextAgendum' => $nextAgendum,
        ]);
    }


    public function end(Program $program)
    {
        if (! $program->ended_at) {
            $program->update(['ended_at' => now()]);
        }

        return redirect()
            ->route('programs.show', compact('program'))
            ->with('success', 'Program has been successfully ended.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        //
    }
}
