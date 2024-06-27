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
            <select name="timezone" id="timezone" hx-get="/schedule-a-call/<?php echo '?year=' . $year . '&month=' . $month . '&date=' . Booker::dateChecker($date, $timezone_selected) . '&timezone='. $timezone_selected; ?>" hx-trigger="change" hx-target="body" hx-swap="innerHTML" hx-push-url="/schedule-a-call/?date=<?php echo Booker::dateChecker($date, $timezone_selected); ?>">
                <?php
                    foreach (timezone_identifiers_list() as $timezone) {
                        $select_timezone = ($timezone == $timezone_selected) ? 'selected' : '';
                        echo '<option value="'. $timezone .'"'. $select_timezone .'>'. $timezone .'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="calendar-timeslots">
            <?php Booker::buildTimeslot($date, $timezone_selected); ?>
        </div>
    </div>
  </main>
@endsection
