@extends('layouts.app')
@section('content')
  <main>
    <h1>Projects</h1>
    <div class="grid-cards">
        @foreach ($projects as $project)
        <div class="cards">
            <figure>
              <img src="{{ asset('storage/' . $project->thumbnail) }}">
            </figure>
            <a href="/projects/{{ $project->slug }}"><h3>{{ $project->title }}</h3></a>
            <p>{{ $project->excerpt }}</p>
        </div>
        @endforeach
    </div>
    <div>
      {{ $projects->links(); }}
    </div>
  </main>
@endsection
