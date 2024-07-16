@extends('layouts.blank')
@section('content')
<section id="content-area">
    <div class="calendar" hx-boost="true">
        <div class="calendar-date-picker">
            <?php $calendar->buildCalendar(); ?>
            
            <label for="timezone">Timezone:</label>
            <select name="timezone" id="timezone" hx-get="/schedule-a-call/?date={{ $date }}" hx-push-url="/schedule-a-call/?date={{ $date }}" hx-trigger="change" hx-target="#content-area" hx-select=".calendar">
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
