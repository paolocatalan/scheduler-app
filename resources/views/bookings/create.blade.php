@extends('layouts.blank')
@section('content')
<main class="site-main">
    <section id="content-area">
        <div class="bookers-details">
            <div>
                <h2>Introduction and Diagnosis</h2>
                <p>Date: {{ $dateTime->format('l, F j, Y') }}</p>
                <p>Time: {{ $dateTime->format('g:i') .' - ' . $dateTime->addMinutes(30)->format('g:i a') }}</p>
                <p>Duration: 30 minutes</p>
                <p>Timezone: {{ str_replace('_', ' ', $dateTime->tzName) }}</p>
                <p>Where: Google Meet</p>
            </div>
            <div>
                <form method="post" action="/schedule-a-call" hx-post="/schedule-a-call" hx-target="#content-area" hx-select=".bookers-details" hx-indicator="#loading-indicator" hx-disinherit="hx-indicator">
                    @csrf
                    <input type="hidden" id="schedule_call" name="schedule_call" value="{{ $dateTime->format('Y-m-d H:i:s') }}">
                    <input type="hidden" id="timestamp" name="timestamp" value="{{ $dateTime->timestamp }}">
                    <input type="hidden" id="timezone" name="timezone" value="{{ $dateTime->tzName }}">
                    @error('schedule_call')
                        <span style="color:#d32f2f;">{{ $message }}</span>
                    @enderror
                    @error('timezone')
                        <span style="color:#d32f2f;">{{ $message }}</span>
                    @enderror
                    <label for="name">Your name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                        <span style="color:#d32f2f;">{{ $message }}</span>
                    @enderror
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <span style="color:#d32f2f;">{{ $message }}</span>
                    @enderror
                    <label for="notes">Additional notes</label>
                    <textarea id="notes" name="notes" rows="5" cols="50">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span style="color:#d32f2f;">{{ $message }}</span>
                    @enderror
                    <div class="form-footer">
                        <a href="/schedule-a-call/?date={{ $date }}" hx-get="/schedule-a-call/?date={{ $date }}" hx-push-url="true" hx-target="#content-area" hx-select=".calendar">Back</a>
                        <input type="submit" value="Confirm">
                    </div>
                    <p id="loading-indicator" class="htmx-indicator">Sending<span class="dots"></span> Your form is on a mission through the interwebs! 🌐</p>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
