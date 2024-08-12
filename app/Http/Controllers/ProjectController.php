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
            new Middleware('isAdmin', only: ['create', 'edit'])
        ];
    }

    public function index()
    {
        $projects = Project::orderBy('created_at', 'DESC')->simplePaginate(9);

        return view('projects.index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());

        return redirect('/projects')->with('message', 'Your project has been added.');
    }

    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project
        ]);
    }

    public function edit(Project $project)
    {
        return view('projects.edit', [
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
