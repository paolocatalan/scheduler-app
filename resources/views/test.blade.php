@extends('layouts.blank')
@section('content')

<?php echo $dateTime->addHours(3); ?>
<br>
<?php echo $dateTime->tzName; ?>
<br>
<?php echo $date->tzName; ?>

<form method="post" action="{{ route('booking.timezone') }}" hx-post="{{ route('booking.timezone') }}" hx-trigger="change" hx-target="body">
  @csrf
  <label for="timezone">Timezone:</label>
  <select name="timezone" id="timezone">
      @foreach (timezone_identifiers_list() as $tz )
      <option value="{{ $tz }}" {{ $tz == old('timezone') || $tz == $timezone ? ' selected' : '' }}>{{ $tz }}</option>
      @endforeach
  </select>
</form>
@endsection
