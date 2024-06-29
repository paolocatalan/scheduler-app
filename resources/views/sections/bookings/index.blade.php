<?php

use App\Http\Helpers\Booker;
?>
@extends('layouts.app')
@section('content')
<main>
    <div class="calendar" hx-boost="true">
        <div class="calendar-date-picker">
            <?php Booker::buildCalendar($year, $month, $timezone_selected); ?>

            <label for="timezone">Timezone:</label>
            <select name="timezone" id="timezone" hx-get="/schedule-a-call/<?php echo '?year=' . $year . '&month=' . $month . '&date=' . Booker::dateChecker($date, $timezone_selected) . '&timezone=' . $timezone_selected; ?>" hx-push-url="/schedule-a-call/?date=<?php echo Booker::dateChecker($date, $timezone_selected); ?>" hx-trigger="change" hx-target="body">
                @foreach (timezone_identifiers_list() as $timezone)
                <option value="{{ $timezone }}" {{ $timezone == old('timezone') || $timezone == $timezone_selected ? ' selected' : '' }}>{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
        <div class="calendar-timeslots">
            <?php Booker::buildTimeslot($date, $timezone_selected); ?>
        </div>
    </div>
</main>
@endsection
