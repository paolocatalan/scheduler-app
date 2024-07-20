<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Helpers\Booker;
use Carbon\Carbon;

class CalendarTest extends TestCase
{
    public function test_date_checker_available_dates() {
        $dateChecked = Booker::dateChecker('2024-08-01');
        $bookedDates = Booker::bookedDates();
        $this->assertFalse($dateChecked->isPast());
        $this->assertFalse($dateChecked->dayOfWeek === Carbon::SUNDAY);
        $this->assertFalse(in_array($dateChecked, $bookedDates));
    }

}
