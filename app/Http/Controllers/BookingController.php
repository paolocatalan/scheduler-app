<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\Booker;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingSuccessMail;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{

    public function index(Request $request)
    {
        $dateSelected = ($request->date) ? new Carbon($request->date) : Carbon::now();
        $dateChecked = Booker::dateChecker($dateSelected->format('Y-m-d'));
        $timezone = (Cookie::has('timezone')) ? Cookie::get('timezone') : 'Europe/Kyiv'; // get the clients timezone via cloudflare server variable
        $calendar = new Booker($dateChecked->format('Y'), $dateChecked->format('m'), $dateChecked->format('Y-m-d'), $timezone);
        
        if (!$request->header('HX-Request')) {
            if (!$request->date || $dateSelected->format('Y-m-d') != $dateChecked->format('Y-m-d')) {
                return redirect( request()->url() . '/?date=' . $dateChecked->format('Y-m-d') );
            }
        }

        return view('bookings.index', [
            'calendar' => $calendar,
            'timezone' => $timezone,
            'date' => $dateChecked->format('Y-m-d'),
            'year' => $dateChecked->format('Y'),
            'month'=>  $dateChecked->format('m')
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->date || !$request->time || !$request->timezone) {
            return redirect('/schedule-a-call/?date' . date('Y-m-d'));
        }

        return view('bookings.create', [
            'date' => $request->date,
            'timestamp' => $request->time,
            'timezone' => $request->timezone
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        Booking::create([
            'schedule_call' => $request->schedule_call,
            'timezone' => $request->timezone,
            'name' => $request->name,
            'email' => $request->email,
            'notes' => $request->notes
        ]);

        // $meetingLink = BookingService::calendarEvent($request->schedule_call, $request->timezone, $request->email);
        // Mail::to('paolo_catalan@yahoo.com')->send(new BookingSuccessMail($request->name, $request->schedule_call, $meetingLink));

        return response()->noContent()
                ->header('HX-Redirect', route('booking.success', [
                    'date' => $request->timestamp,
                    'timezone' => $request->timezone,
                ]));

    }

    public function success(Request $request)
    {
        if ( !$request->date || !$request->timezone ) {
            abort(404);
        }

        return view('bookings.success', [
            'date' => $request->date,
            'timezone' => $request->timezone
        ]);
    }

    public function setTimezone(Request $request)
    {
        Session::put('timezone', $request->timezone);

        return back();
    }

}
