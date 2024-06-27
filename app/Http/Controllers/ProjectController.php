<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::orderBy('updated_at', 'DESC')->simplePaginate(10);

        return view('sections.projects.index', [
            'projects' => $projects
        ]);
    }

    public function create() {
        return view('sections.projects.create');
    }

    public function show($slug) {
        $project = Project::where('slug', $slug)->first();

        return view('sections.projects.show', [
            'project' => $project
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'min:3'],
            'body' => 'required',
            'slug' => ['required', Rule::unique('projects', 'slug')],
            'thumbnail' => 'required|image'
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['thumbnail'] = request()->file('thumbnail')->store('thumbnails');

        Project::create($validated);

        return redirect('/projects')->with('message', 'Your project has been added.');
    }

    public function edit($slug) {
        $project = Project::where('slug', $slug)->first();

        Gate::authorize('update', $project);

        return view('sections.projects.edit', [
            'project' => $project
        ]);
    }

    public function update(Request $request, $slug) {
        $project = Project::where('slug', $slug)->first();
        //Authorize
        Gate::authorize('update', $project);

        // validate
        $validated = $request->validate([
            'title' => ['required', 'min:3'],
            'slug' => ['required', Rule::unique('projects', 'slug')->ignore($project->id)],
            'body' => 'required',
            'thumbnail' => 'image'
        ]);

        if (isset($validated['thumbnail'])) {
            $validated['thumbnail'] = request()->file('thumbnail')->store('thumbnails');
        }

        // and persist
        $project->update($validated);

        // redirect
        return back()->with('message', 'Your project has been updated.');
    }

    public function destroy($slug) {
        $project = Project::where('slug', $slug)->first();
        
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect('/projects')->with('message', 'Your project has been deleted.');
    }
}
