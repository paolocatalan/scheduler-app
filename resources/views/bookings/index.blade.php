@extends('layouts.blank')
@section('content')
<main class="site-main">
    <section id="content-area">
        <div class="calendar" hx-boost="true">
            <div class="calendar-date-picker">
                <?php $calendar->buildCalendar(); ?>
                <form method="post" action="{{ route('booking.timezone') }}" hx-post="{{ route('booking.timezone') }}" hx-trigger="change" hx-target="#content-area" hx-select=".calendar">
                    @csrf
                    <label for="timezone">Timezone:</label>
                    <select name="timezone" id="timezone">
                        @foreach (timezone_identifiers_list() as $timezoneName )
                        <option value="{{ $timezoneName }}" {{ $timezoneName == old('timezone') || $timezoneName == $timezone ? ' selected' : '' }}>{{ $timezoneName }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="calendar-timeslots">
                <?php $calendar->buildTimeslot(); ?>
            </div>
        </div>
    </section>
</main>
@endsection
