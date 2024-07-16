<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
            new Middleware('isAdmin', only: ['create', 'store'])
        ];
    }

    public function index()
    {
        $projects = Project::orderBy('updated_at', 'DESC')->simplePaginate(9);

        return view('sections.projects.index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Project::class);

        return view('sections.projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        Gate::authorize('create', Project::class);

        Project::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'thumbnail' => $request->file('thumbnail')->store('thumbnails'),
            'user_id' => auth()->user()->id
        ]);

        return redirect('/projects')->with('message', 'Your project has been added.');
    }

    public function show(Project $project)
    {
        return view('sections.projects.show', [
            'project' => $project
        ]);
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        return view('sections.projects.edit', [
            'project' => $project
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        //authorize
        Gate::authorize('update', $project);

        //validate
        $validated = $request->validated();

        if (isset($validated['thumbnail'])) {
            $validated['thumbnail'] = request()->file('thumbnail')->store('thumbnails');
        }

        // and persist
        $project->update($validated);

        // redirect
        return redirect('/projects/' . $project->slug )->with('message', 'Your project has been updated.');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect('/projects')->with('message', 'Your project has been deleted.');
    }
}
