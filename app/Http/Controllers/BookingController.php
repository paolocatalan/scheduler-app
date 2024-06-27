<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\Booker;
use App\Http\Controllers\GoogleMeetController;
use App\Mail\BookingSuccess;
use App\Models\Booking;


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
    public function create()
    {
        $date = (isset($_GET['date'])) ? $_GET['date'] : '';
        $timestamp = (isset($_GET['time'])) ? $_GET['time'] : '';
        $timezone = (isset($_GET['timezone'])) ? $_GET['timezone'] : '';
        return view('sections.bookings.create', [
            'date' => $date,
            'timestamp' => $timestamp,
            'timezone' => $timezone
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_call' => 'required|date|date_format:Y-m-d H:s:i|after:now',
            'timezone' => 'required|timezone:all',
            'name' => 'required',
            'email' => 'required|email:strict',
            'notes' => 'required',
        ]);
    
        if ($validator->fails()) {
            return view('components.error-message', [
                'errors' => $validator->errors(),
            ]);

        } else {
            Booking::create([
                'schedule_call' => $request->schedule_call,
                'timezone' => $request->timezone,
                'name' => $request->name,
                'email' => $request->email,
                'notes' => $request->notes
            ]);

            $event_name = 'Introduction and Diagnosis';
            $startDateTime = $request->schedule_call;
            $timezone = $request->timezone;
            GoogleMeetController::createEvent($event_name, $startDateTime, $timezone);

            $name = $request->name;
            $date = date('l, F j, Y, g:i a', strtotime($request->schedule_call));
            Mail::to('paolo_catalan@yahoo.com')->send(new BookingSuccess($name, $date));

            return response()
                    // ->json(['message' => 'Form submitted successfully.'])
                    ->header('HX-Redirect', route('booking.success.route'));
        }
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
