<?php

namespace App\Http\Controllers;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index() {
        $sections = Section::all()->except([1]);
        return view('section.index')->with('sections', $sections);
    }

    public function create() {
        return view('section.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
           'name' => ['required', 'min:5', 'max:255']
        ]);

        Section::create($data);

        return redirect(route('section.index'));
    }

    public function edit(Section $section) {
        return view('section.edit')->with('section', $section);
    }

    public function update(Request $request, Section $section) {
        $data = $request->validate([
            'name' => ['required', 'min:5', 'max:255']
        ]);

        $section->update($data);

        return redirect(route('section.index'));
    }

    public function destroy(Section $section) {
        $section->delete();
        return redirect(route('section.index'));
    }
}
