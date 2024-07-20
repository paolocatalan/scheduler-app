@extends('layouts.app')
@section('content')
<main class="site-main">
  <article>
    <header class="entry-header">
      <h1>{{ $project->title }}</h1>
      @if ($project->thumbnail)
      <figure>
        <img src="{{ asset('storage/' . $project->thumbnail) }}">
      </figure>
      @endif
    </header>
    <div class="entry-content">
        {!! $project->body !!}
    </div>
    <footer class="entry-footer">
      @can('update', $project)
      <a href="/projects/{{ $project->slug }}/edit">Edit Project</a>
      @endcan
    </footer>
    <hr>
  </article>
  <div>
    <p>Do you need help with your PHP project?</p>
    <a class="button" href="/schedule-a-call/?date={{ date('Y-m-d') }}" hx-get="/schedule-a-call/?date={{ date('Y-m-d') }}" hx-target="body" hx-push-url="true">Schedule a Call</a>
  </div>
</main>
@endsection
