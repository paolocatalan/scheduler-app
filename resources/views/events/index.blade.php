@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Events</h1>
  <ul>
  @foreach ($events as $event)
    <li><a href="/events/{{ $event->id }}">{{ $event->id }}</a>: {{ $event->start->dateTime }}</li>
  @endforeach
  </ul>
</main>
@endsection
