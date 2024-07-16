@extends('layouts.blank')
@section('content')
<section id="content-area">
    <div class="calendar" hx-boost="true">
        <div class="calendar-date-picker">
            <?php $calendar->buildCalendar(); ?>
            <form method="post" action="{{ route('booking.timezone') }}" hx-post="{{ route('booking.timezone') }}" hx-trigger="change" hx-target="#content-area" hx-select=".calendar">
                @csrf
                <label for="timezone">Timezone:</label>
                <select name="timezone" id="timezone">
                    @foreach (timezone_identifiers_list() as $tz )
                    <option value="{{ $tz }}" {{ $tz == old('timezone') || $tz == $timezone ? ' selected' : '' }}>{{ $tz }}</option>
                    @endforeach
                </select>
        </div>
        <div class="calendar-timeslots">
            <?php $calendar->buildTimeslot(); ?>
        </div>
    </div>
</section>
@endsection
