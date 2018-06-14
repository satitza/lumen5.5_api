<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mockery\Exception;

class CheckBillController extends BaseController
{
    /**
     * CheckBillController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        try {

            $this->middleware('auth');
            $this->validate($request, [
                'booking_id' => 'required',
            ]);

            $GLOBALS['book_id'] = $request->booking_id;

        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     */
    public function check_bill()
    {
        /**
         * Check booking exists
         */
        if ($this->CheckBookingExists($GLOBALS['book_id']) == true) {

            try {
                /**
                 * Update booking status
                 */
                $this->update_report($GLOBALS['book_id']);
                return response()->json([
                    'message' => 'Update booking status success',
                    'information' => $this->get_information($GLOBALS['book_id'])
                ], 200);
            } catch (QueryException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Booking id not found'
            ], 500);
        }
    }

    /**
     * @param $book_id
     */
    public function CheckBookingExists($book_id)
    {
        try {
            return DB::table('reports')->where('booking_id', $book_id)
                ->exists();
        } catch (QueryException $e) {
            throw new QueryException("Check offer exists query exception");
        } catch (Exception $e) {
            throw new Exception("Check offer exists exception");
        }
    }

    public function update_report($book_id)
    {
        try {
            DB::beginTransaction();
            DB::table('reports')->where('booking_id', $book_id)
                ->update([
                    'booking_status' => 2
                ]);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            throw new QueryException("Update booking status query exception");
        } catch (Exception $e) {
            throw new Exception("Update booking status exception");
        }
    }

    public function get_information($book_id)
    {
        try {
            $informations = DB::table('reports')->select(
                'booking_id',
                'hotels.hotel_name',
                'restaurants.restaurant_name',
                'offers.offer_name_en',
                'reports.booking_date',
                'reports.booking_guest',
                'reports.booking_contact_title',
                'reports.booking_contact_firstname',
                'reports.booking_contact_lastname',
                'reports.booking_contact_email',
                'reports.booking_contact_phone',
                'reports.booking_contact_request',
                'reports.booking_price',
                'reports.booking_time_type'
            )
                ->join('hotels', 'reports.booking_hotel_id', 'hotels.id')
                ->join('restaurants', 'reports.booking_restaurant_id', 'restaurants.id')
                ->join('offers', 'reports.booking_offer_id', 'offers.id')
                ->where('booking_id', $book_id)->first();
            return $informations;
        } catch (QueryException $e) {
            throw new QueryException("Update booking status query exception");
        } catch (Exception $e) {
            throw new Exception("Update booking status exception");
        }
    }
}