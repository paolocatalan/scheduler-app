<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Helpers\Booker;
use Carbon\Carbon;

class CalendarTest extends TestCase
{
    public function test_date_checker_past_dates() {
        $dateChecker = Booker::dateChecker('2024-09-01', 'Europe/Warsaw');
        $dateTime = new Carbon($dateChecker, date_default_timezone_get());
        $this->assertTrue(!$dateTime->isPast());
        $this->assertTrue($dateTime->dayOfWeek != Carbon::SUNDAY);
        $bookedDates = array('2024-08-01 00:00:00', '2024-08-02 00:00:00', '2024-08-03 00:00:00');
        $this->assertTrue(!in_array($dateTime, $bookedDates));
    }
}
