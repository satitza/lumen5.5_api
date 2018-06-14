<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
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
                    'email' => $this->get_email($GLOBALS['book_id'])
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

    public function get_email($book_id)
    {
        try {
            $email = DB::table('reports')->select('booking_contact_email')
                ->where('booking_id', $book_id)->first();
            return $email->booking_contact_email;
        } catch (QueryException $e) {
            throw new QueryException("Update booking status query exception");
        } catch (Exception $e) {
            throw new Exception("Update booking status exception");
        }
    }
}