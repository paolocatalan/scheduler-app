@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>{{ $event->name }}</h1>
  @if ($event->startDate)
    <p>Full day: {{ $event->startDate }} - {{ $event->endDate }}</p>
  @else
    <p>Time: {{ $event->startDateTime }} - {{ $event->endDateTime }}</p>
  @endif
  <p>Summary:<p>
  {!! $event->description !!}
  <p>Meet Link: {{ $event->hangoutLink }}</p>
  <p>Link: <a href="{{ $event->htmlLink }}" target="_blank">{{ $event->htmlLink }}</a></p>
  @if ($event->attendees)
    <p>Attendee</p>
    <ul>
    @foreach ($event->attendees as $attendee)
      <li>{{ $attendee->email }} [{{ $attendee->responseStatus }}]</li>
    @endforeach
    </ul>
  @endif
  <form method="post" action="{{ route('events.destroy', ['id' => $event->id]) }}" id="delete-form">
    @csrf
    @method('DELETE')
    <button form="delete-form" type="submit">Delete</button>
  </form>
</main>
@endsection
