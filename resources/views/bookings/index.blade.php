@extends('layouts.blank')
@section('content')
<main class="site-main">
    <section id="content-area">
        <div class="calendar" hx-boost="true">
            <div class="calendar-date-picker">
                <div class="month-header">
                    <h2>{{ $dateTime->monthName }} {{ $dateTime->year }}</h2>
                    <nav>
                        <button hx-get="/schedule-a-call/?date={{ $buildCalendar['prevNavLink'] }}" hx-push-url="true" hx-target="#content-area" hx-select=".calendar"@if ($dateTime->month == now()->format('m')) disabled="disabled"@endif>&lsaquo;</button><button hx-get="/schedule-a-call/?date={{ $buildCalendar['nextNavLink'] }}" hx-push-url="true" hx-target="#content-area" hx-select=".calendar">&rsaquo;</button>
                    </nav>
                </div>
                <table>
                    <tr>
                        @foreach ($buildCalendar['daysOfWeek'] as $dayName)
                            <th>{{ $dayName }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @if ($buildCalendar['dayOfWeek'] > 0)
                            @for ($i = 0; $i < $buildCalendar['dayOfWeek']; $i++)
                                <td></td>
                            @endfor
                        @endif

                        @while ($buildCalendar['startOfCalendar'] <= $buildCalendar['endOfCalendar'])
                            @if ($buildCalendar['dayOfWeek'] == 7)
                                @php
                                    $buildCalendar['dayOfWeek'] = 0;
                                @endphp
                                </tr><tr>
                            @endif
                            @if ($buildCalendar['startOfCalendar'] < now(config('app.timezone_display'))->subDay() || $buildCalendar['startOfCalendar']->isSunday())
                                <td class="not-available"><span>{{ $buildCalendar['startOfCalendar']->format('j') }}</span></td>
                            @else
                                <td
                                @if ($dateTime->format('Y-m-d') == $buildCalendar['startOfCalendar']->format('Y-m-d'))
                                    class="active"
                                @endif
                                >
                                    <a hx-get="/schedule-a-call/?date={{ $buildCalendar['startOfCalendar']->format('Y-m-d') }}" hx-push-url="true"  hx-target="#content-area" hx-select=".calendar">
                                        <span
                                        @if ($buildCalendar['startOfCalendar']->isToday())
                                             class="today"
                                        @endif
                                        >
                                            {{ $buildCalendar['startOfCalendar']->format('j') }}
                                        </span>
                                    </a>
                                </td>
                            @endif
                            @php
                                $buildCalendar['startOfCalendar']->addDay();
                                $buildCalendar['dayOfWeek']++;
                            @endphp
                        @endwhile

                        @if ($buildCalendar['dayOfWeek'] != 7)
                            @for ($i = 0; $i < 7 - $buildCalendar['dayOfWeek']; $i++)
                                <td></td>
                            @endfor
                        @endif

                    </tr>
                </table>

                <form method="post" action="{{ route('booking.timezone') }}" hx-post="{{ route('booking.timezone') }}" hx-trigger="change" hx-target="#content-area" hx-select=".calendar">
                    @csrf
                    <label for="timezone">Timezone:</label>
                    <select name="timezone" id="timezone">
                        @foreach (timezone_identifiers_list() as $timezoneName )
                        <option value="{{ $timezoneName }}" {{ $timezoneName == old('timezone') || $timezoneName == $buildCalendar['timezone'] ? ' selected' : '' }}>{{ $timezoneName }}</option>
                        @endforeach
                    </select>
                </form>

            </div>
            <div class="calendar-timeslots">
                <p><strong>{{ $dateTime->format('D') }}</strong> {{ $dateTime->format('j') }}</p>
                <ul>
                    @foreach ($buildCalendar['listTimeslot'] as $timeslot)
                        @if (in_array($timeslot, $buildCalendar['retriveTimeslot']))
                            <li>{{ $timeslot->format('g:i a') }}</li>
                        @else
                            <li><a hx-get="/schedule-a-call/introduction?date={{ $dateTime->format('Y-m-d') }}&time={{ $timeslot->timestamp }}&timezone={{ $buildCalendar['timezone'] }}" hx-push-url="true" hx-target="#content-area" hx-select=".bookers-details">{{ $timeslot->format('g:i a') }}</a></li>
                        @endif
                    @endforeach
                </ul>

                @if ($dateTime->isToday() && empty($buildCalendar['listTimeslot']))
                    <p>Please consider rescheduling for tomorrow.</p>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection
