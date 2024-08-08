@extends('layouts.blank')
@section('content')
<main class="site-main">
    <section id="content-area">
        <div class="calendar" hx-boost="true">
            <div class="calendar-date-picker">
                <div class="month-header">
                    <h2>{{ $dateTime->monthName }} {{ $dateTime->year }}</h2>
                    <nav>
                        <button hx-get="/calendar/?date={{ $buildCalendar['prevNavLink'] }}" hx-push-url="true" hx-target="#content-area" hx-select=".calendar"@if ($dateTime->month == now()->format('m')) disabled="disabled"@endif>&lsaquo;</button><button hx-get="/calendar/?date={{ $buildCalendar['nextNavLink'] }}" hx-push-url="true" hx-target="#content-area" hx-select=".calendar">&rsaquo;</button>
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

                        @while ($buildCalendar['currentDay'] <= $dateTime->daysInMonth)
                        
                            @if ($buildCalendar['dayOfWeek'] == 7)
                                @php
                                    $buildCalendar['dayOfWeek'] = 0;
                                @endphp
                                </tr><tr>
                            @endif

                            @if ($calendar->dayInCarbon($dateTime, $buildCalendar['currentDay'])->isPast() || $calendar->dayInCarbon($dateTime, $buildCalendar['currentDay'])->isSunday())
                                <td class="not-available"><span>{{ $buildCalendar['currentDay'] }}</span></td>
                            @else
                                <td
                                    @if ($dateTime->format('Y-m-d') == $calendar->dayInCarbon($dateTime, $buildCalendar['currentDay'])->format('Y-m-d'))
                                        class="active"
                                    @endif
                                >
                                    <a hx-get="/calendar/?date={{ $calendar->dayInCarbon($dateTime, $buildCalendar['currentDay'])->format('Y-m-d') }}" hx-push-url="true"  hx-target="#content-area" hx-select=".calendar">
                                        <span
                                        @if ($calendar->dayInCarbon($dateTime, $buildCalendar['currentDay'])->format('Y-m-d') == now(config('app.timezone_display'))->format('Y-m-d'))
                                             class="today"
                                        @endif
                                        >
                                            {{ $buildCalendar['currentDay'] }}
                                        </span>
                                    </a>
                                </td>
                            @endif

                            @php
                                $buildCalendar['currentDay']++;
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

            </div>
            <div class="calendar-timeslots">
                <p><strong>{{ $dateTime->format('D') }}</strong> {{ $dateTime->format('j') }}</p>
                <ul>
                    @foreach ($buildTimeslots['convertTimeslots'] as $timeslot)
                        @if (!empty($buildTimeslots['listTimeslots']) && in_array($timeslot, $buildTimeslots['listTimeslots']))
                            <li>{{ date('g:i a', strtotime($timeslot)) }}</li>
                        @else
                            <li><a hx-get="/schedule-a-call/introduction" hx-push-url="true" hx-target="#content-area" hx-select=".bookers-details">{{ date('g:i a', strtotime($timeslot)) }}</a></li>
                        @endif
                    @endforeach
                </ul>

                @if ($dateTime->isToday() && empty($buildTimeslots['convertTimeslots']))
                    <p>Please consider rescheduling for tomorrow.</p>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection
