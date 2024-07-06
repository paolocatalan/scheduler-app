@extends('layouts.app')
@section('content')
<main id="site-content">
    <div class="bookers-details">
        <div>
            <h3>Meet with Paolo</h3>
            <p>Date: <?php echo date('l, F j, Y', $timestamp); ?></p>
            <p>Time: <?php echo date('g:i', $timestamp) .' - ' . date('g:i a', strtotime('+30 minutes', $timestamp));  ?></p>
            <p>Duration: 30 minutes</p>
            <p>Timezone: <?php echo str_replace('_', ' ' , $timezone); ?></p>
            <p>Google Meet</p>
        </div>
        <div>
            <form method="post" action="/schedule-a-call" hx-post="/schedule-a-call" hx-target="body" hx-indicator="#loading-indicator" hx-disinherit="hx-indicator">
                @csrf
                <input type="hidden" id="schedule_call" name="schedule_call" value="<?php echo date('Y-m-d H:i:s', $timestamp); ?>">
                <input type="hidden" id="timezone" name="timezone" value="<?php echo $timezone; ?>"> 
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
                    <a href="/schedule-a-call/?date=<?php echo $date . '&time=' . $timestamp . '&timezone=' . $timezone; ?>" hx-get="/schedule-a-call/?date=<?php echo $date . '&time=' . $timestamp . '&timezone=' . $timezone; ?>" hx-push-url="true" target="body">Back</a>
                    <input type="submit" value="Confirm">
                </div>
                <p id="loading-indicator" class="htmx-indicator">Sending... Your form is on a mission through the interwebs! 🌐</p>
            </form>
        </div>
    </div>
</main>
@endsection
