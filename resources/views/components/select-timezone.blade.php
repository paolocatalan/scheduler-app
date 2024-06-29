<label for="timezone">Timezone:</label>
<select name="timezone" id="timezone" hx-get="/schedule-a-call/<?php echo '?year=' . $year . '&month=' . $month . '&date=' . Booker::dateChecker($date, $timezone_selected) . '&timezone=' . $timezone_selected; ?>" hx-push-url="/schedule-a-call/?date=<?php echo Booker::dateChecker($date, $timezone_selected); ?>" hx-trigger="change" hx-target="body">
  @foreach (timezone_identifiers_list() as $timezone)
  <option value="{{ $timezone }}" {{ $timezone == old('timezone') || $timezone == $timezone_selected ? ' selected' : '' }}>{{ $timezone }}</option>
  @endforeach
</select>