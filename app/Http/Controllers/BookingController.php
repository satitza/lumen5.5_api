<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createBooking(Request $request)
    {
        try {

            $this->validate($request, [
                'booking_id' => 'required',
                'booking_hotel_id' => 'required',
                'booking_restaurant_id' => 'required',
                'booking_offer_id' => 'required',
                'booking_date' => 'required',
                'booking_guest' => 'required',
                'booking_contact_title' => 'required',
                'booking_contact_firstname' => 'required',
                'booking_contact_lastname' => 'required',
                'booking_contact_email' => 'required',
            ]);


            return response()->json([
                'msg' => 'create booking'
            ]);

        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }
}