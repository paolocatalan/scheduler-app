@extends('layouts.app')
@section('content')
<main id="site-content">
    <div class="bookers-details">
        <div>
            <h3>Meet with Paolo</h3>
            <p>Date: <?php echo date('l, F j, Y', $timestamp); ?></p>
            <p>Time: <?php echo date('g:i', $timestamp) .' - ' . date('g:i a', strtotime('+30 minutes', $timestamp));  ?></p>
            <p>Duration: 30 minutes</p>
            <p>Timezone: <?php echo $timezone; ?></p>
            <p>Google Meet</p>
        </div>
        <div>
            <div id="response"></div>
            <form method="post" action="/schedule-a-call/store" hx-post="/schedule-a-call/store" hx-target="#response" hx-swap="innerHTML">
                @csrf
                <input type="hidden" id="schedule_call" name="schedule_call" value="<?php echo date('Y-m-d H:i:s', $timestamp); ?>">
                <input type="hidden" id="timezone" name="timezone" value="<?php echo $timezone; ?>"> 
                <label for="name">Your name</label>
                <br>
                <input type="text" id="name" name="name">
                <br>
                <label for="email">Email address</label>
                <br>
                <input type="email" id="email" name="email">
                <br>
                <label for="notes">Additional notes</label>
                <br>
                <textarea id="notes" name="notes" rows="5" cols="50"></textarea>
                <br>
                <a href="/schedule-a-call/?date=<?php echo $date . '&time=' . $timestamp . '&timezone=' . $timezone; ?>">Back</a>
                <input type="submit" value="Confirm">
            </form>
        </div>
    </div>
</main>
@endsection
