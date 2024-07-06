<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\Booker;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingSuccessMail;
use App\Models\Booking;
use App\Services\BookingServices;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $timezone_selected = (isset($_GET['timezone'])) ? $_GET['timezone'] : 'Europe/Kyiv'; // get the clients timezone
        $date_request = $request->query('date');
        $current_date_time = date('Y-m-d H:i:s');
        $current_date_time = new \DateTime($current_date_time, new \DateTimeZone($timezone_selected));
        $current_date = $current_date_time->format('Y-m-d');
        $date_request = ($date_request == true) ? $date_request : $current_date;
        $date = Booker::dateChecker($date_request, $timezone_selected);
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));

        if ($date_request == true && $date_request != $date) {
            return redirect( request()->url() . '/?date=' . $date );
        }

        $booking = Booking::where('schedule_call', '>', date('Y-m-d H:i:s'))->get();

        return view('sections.bookings.index', [
            'booking' => $booking,
            'timezone_selected' => $timezone_selected,
            'date' => $date,
            'year' => $year,
            'month'=> $month
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $date = (isset($_GET['date'])) ? $_GET['date'] : '';
        $timestamp = (isset($_GET['time'])) ? $_GET['time'] : '';
        $timezone = (isset($_GET['timezone'])) ? $_GET['timezone'] : '';

        // add if the page is refreshed
        // $is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');

        if ($request->header('HX-Request')) {
            return view('sections.bookings.create', [
            'date' => $date,
            'timestamp' => $timestamp,
            'timezone' => $timezone
            ]);
        }

        return redirect('/schedule-a-call');
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

        $htmlLink = BookingServices::calendarEvent($request->schedule_call, $request->timezone, $request->email);
        Mail::to('paolo_catalan@yahoo.com')->send(new BookingSuccessMail($request->name, $request->schedule_call, $htmlLink));

        return response()->noContent()
                ->header('HX-Redirect', route('booking.success'));

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

    public function success()
    {
        return view('sections.bookings.success');
    }
}
