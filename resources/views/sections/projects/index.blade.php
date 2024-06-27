@extends('layouts.app')
@section('content')
  <main>
    <h1>Projects</h1>
    <ul>
        @foreach ($projects as $project)
        <li>
            <a href="/projects/{{ $project->slug }}">
            {{ $project->title }}
            </a>
        </li>
        @endforeach
    <ul>
    {{ $projects->links(); }}
  </main>
@endsection
