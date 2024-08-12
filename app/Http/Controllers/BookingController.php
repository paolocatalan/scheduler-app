<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\Calendar;
use App\Services\Scheduler;
use App\Services\Booker;
use Illuminate\Support\Facades\Session;
use Carbon\CarbonImmutable;

class BookingController extends Controller
{

    public function index(Request $request, Calendar $calendar, Scheduler $scheduler)
    {
        $dateTime = $scheduler->checkDate($request->date);

        if (!$request->header('HX-Request') && $request->date != $dateTime->format('Y-m-d')) {
            return redirect(request()->url() . '/?date=' . $dateTime->format('Y-m-d'));
        }

        return view('bookings.index', [
            'dateTime' => $dateTime,
            'buildCalendar' => $calendar->buildCalendar($dateTime)
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->has(['date', 'time', 'timezone'])) {
            abort(404);
        }

        $dateTime = CarbonImmutable::createFromTimestamp($request->time, $request->timezone);

        return view('bookings.create', [
            'date' => $request->date,
            'dateTime' => $dateTime,
            'timezone' => $request->timezone
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        Booking::create($request->validated());

        (new Booker($request->validated()))->process();

        return response()->noContent()
                ->header('HX-Redirect', route('booking.success', [
                    'date' => $request->timestamp,
                    'timezone' => $request->timezone
                ]));
    }

    public function success(Request $request)
    {
        if (!$request->has(['date', 'timezone'])) {
            abort(404);
        }

        $dateTime = CarbonImmutable::createFromTimestamp($request->date, $request->timezone);

        return view('bookings.success', [
            'date' => $dateTime,
            'timezone' => $request->timezone
        ]);
    }

    public function setTimezone(Request $request)
    {
        Session::put('timezone', $request->timezone);

        return back();
    }
}
