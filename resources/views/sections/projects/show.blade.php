@extends('layouts.app')
@section('content')
  <main>
    <h1>{{ $project->title }}</h1>
    @if ($project->thumbnail)
      <img src="{{ asset('storage/' . $project->thumbnail) }}" width="225">
    @endif
    <div>
        {!! $project->body !!}
    </div>
    @can('update', $project)
    <a href="/projects/{{ $project->slug }}/edit">Edit Project</a>
    @endcan
  </main>
@endsection
