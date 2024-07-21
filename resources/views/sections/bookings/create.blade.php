@extends('layouts.blank')
@section('content')
<main class="site-main">
    <section id="content-area">
        <div class="bookers-details">
            <div>
                <h2>Introduction and Diagnosis</h2>
                <p>Date: <?php echo date('l, F j, Y', $timestamp); ?></p>
                <p>Time: <?php echo date('g:i', $timestamp) .' - ' . date('g:i a', strtotime('+30 minutes', $timestamp));  ?></p>
                <p>Duration: 30 minutes</p>
                <p>Timezone: <?php echo str_replace('_', ' ' , $timezone); ?></p>
                <p>Where: Google Meet</p>
            </div>
            <div>
                <form method="post" action="/schedule-a-call" hx-post="/schedule-a-call" hx-target="#content-area" hx-select=".bookers-details" hx-indicator="#loading-indicator" hx-disinherit="hx-indicator">
                    @csrf
                    <input type="hidden" id="schedule_call" name="schedule_call" value="<?php echo date('Y-m-d H:i:s', $timestamp); ?>">
                    <input type="hidden" id="timestamp" name="timestamp" value="{{ $timestamp }}">
                    <input type="hidden" id="timezone" name="timezone" value="{{ $timezone }}">
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
                    <p id="loading-indicator" class="htmx-indicator">Sending... Your form is on a mission through the interwebs! 🌐</p>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
