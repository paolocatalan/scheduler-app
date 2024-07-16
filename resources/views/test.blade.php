@extends('layouts.blank')
@section('content')

<?php echo $dateTime; ?>
<br>
<?php echo $dateTime->tzName; ?>

<form method="post" action="{{ route('projects.store') }}" hx-post="{{ route('booking.timezone') }}" hx-trigger="change" hx-target="body">
  @csrf
  <label for="timezone">Timezone:</label>
  <select name="timezone" id="timezone">
      @foreach (timezone_identifiers_list() as $tz )
      <option value="{{ $tz }}" {{ $tz == old('timezone') || $tz == $timezone ? ' selected' : '' }}>{{ $tz }}</option>
      @endforeach
  </select>
</form>
@endsection
