@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Edit: {{ $event->name }}</h1>
  <p>Date: {{ $event->startDateTime }}</p>
  <p>Summary: {{ $event->description }}</p>
  <p>Link: <a href="{{ $event->htmlLink }}" target="_blank">{{ $event->htmlLink }}</a></p>
  <form method="post" action="{{ route('booking.destroy', ['id' => $event->id]) }}" id="delete-form">
    @csrf
    @method('DELETE')
    <button form="delete-form" type="submit">Delete</button>
  </form>
</main>
@endsection
