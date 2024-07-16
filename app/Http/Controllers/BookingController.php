<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\Booker;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingSuccessMail;
use App\Models\Booking;
use App\Services\BookingServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $timezone = (Cookie::has('timezone')) ? Cookie::get('timezone') : 'Asia/Manila'; // get the clients timezone

        $dateSelected = ($request->date) ? new Carbon($request->date, $timezone) : Carbon::now()->inUserTimezone();
        $dateChecked = Booker::dateChecker($dateSelected->format('Y-m-d'));
        $calendar = new Booker($dateChecked->format('Y'), $dateChecked->format('m'), $dateChecked->format('Y-m-d'), $timezone);
        
        if (!$request->date || $dateSelected->format('Y-m-d') != $dateChecked->format('Y-m-d')) {
            return redirect( request()->url() . '/?date=' . $dateChecked->format('Y-m-d') );
        }

        return view('sections.bookings.index', [
            'calendar' => $calendar,
            'timezone' => $timezone,
            'date' => $dateChecked->format('Y-m-d'),
            'year' => $dateChecked->format('Y'),
            'month'=>  $dateChecked->format('m')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // add if the page is refreshed
        // $is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');

        // if (!$request->header('HX-Request')) {
        //     return redirect('/schedule-a-call/?date' . date('Y-m-d'));
        // }

        return view('sections.bookings.create', [
            'date' => $request->date,
            'timestamp' => $request->time,
            'timezone' => $request->timezone
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        Booking::create([
            'schedule_call' => $request->schedule_call,
            'timezone' => $request->timezone,
            'name' => $request->name,
            'email' => $request->email,
            'notes' => $request->notes
        ]);

        // $meetingLink = BookingServices::calendarEvent($request->schedule_call, $request->timezone, $request->email);
        // Mail::to('paolo_catalan@yahoo.com')->send(new BookingSuccessMail($request->name, $request->schedule_call, $meetingLink));

        return response()->noContent()
                ->header('HX-Redirect', route('booking.success', [
                    'date' => $request->timestamp,
                    'timezone' => $request->timezone
                ]));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function success(Request $request)
    {
        if ( !$request->date || !$request->timezone ) {
            abort(404);
        }

        return view('sections.bookings.success', [
            'date' => $request->date,
            'timezone' => $request->timezone
        ]);
    }

    public function setTimezone(Request $request)
    {
        $timezoneCookie = Cookie::make('timezone', $request->timezone, 60);
        return back()->withCookie($timezoneCookie);
    }
}
