<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Helpers\Booker;
use Carbon\Carbon;

class CalendarTest extends TestCase
{
    public function test_date_checker_available_dates() {
        $randomTimestamp = rand(1721395626, 1752931626);
        $dateTime = date('Y-m-d', $randomTimestamp);
        $dateChecked = Booker::dateChecker($dateTime);
        $bookedDates = Booker::bookedDates();
        $this->assertFalse($dateChecked->isPast());
        $this->assertFalse($dateChecked->dayOfWeek === Carbon::SUNDAY);
        $this->assertFalse(in_array($dateChecked, $bookedDates));
    }

}
