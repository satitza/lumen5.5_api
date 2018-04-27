<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mockery\Exception;

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
                'booking_offer_id' => 'required|integer',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'booking_guest' => 'required|integer|between:1,1000',
                'booking_contact_title' => 'required',
                'booking_contact_firstname' => 'required',
                'booking_contact_lastname' => 'required',
                'booking_contact_email' => 'required|email',
                'booking_contact_phone' => 'required|numeric',
                'booking_time_type' => 'required'
            ]);

            if ($this->CheckDateFromOfferId($request->booking_offer_id, $request->booking_date) == true) {

                $this->InsertBooking(
                    $request->booking_id,
                    $request->booking_offer_id,
                    $request->booking_date,
                    $request->booking_guest,
                    $request->booking_contact_title,
                    $request->booking_contact_firstname,
                    $request->booking_contact_lastname,
                    $request->booking_contact_email,
                    $request->booking_contact_phone,
                    $request->booking_contact_request,
                    $request->booking_time_type
                );


                if ($this->BookCheckBalanceExists($request->booking_offer_id, $request->booking_date, $request->booking_time_type) == true) {
                    // update balance rows

                    $where = ['book_offer_id' => $request->booking_offer_id, 'book_time_type' => $request->booking_time_type, 'active_id' => 1];
                    $old_guests = DB::table('book_check_balances')->select('book_offer_balance', 'active_id')
                        ->where($where)
                        ->whereDate('book_offer_date', $request->booking_date)
                        ->first();

                    $offer_guest = $request->booking_guest;

                    if (!isset($old_guests)) {
                        return response()->json([
                            'message' => 'Cannot update balance because this balance is disable'
                        ], 500);
                    } else if ((int)$offer_guest > (int)$old_guests->book_offer_balance) {
                        return response()->json([
                            'message' => 'Offer guest is over'
                        ], 500);
                    } else {

                        $new_guest = (int)$old_guests->book_offer_balance - (int)$offer_guest;

                        DB::beginTransaction();
                        DB::table('book_check_balances')->where($where)
                            ->whereDate('book_offer_date', $request->booking_date)->update([
                                'book_offer_guest' => $offer_guest,
                                'book_offer_balance' => $new_guest
                            ]);
                        DB::commit();
                    }
                } else {

                    // create balance rows
                    $this->CreateBalances($request->booking_offer_id, $request->booking_date, $request->booking_guest, $request->booking_time_type);

                }
            } else {
                return response()->json([
                    'message' => 'Cannot find offer from date request'
                ], 500);
            }

            return response()->json([
                'message' => 'Create booking success'
            ], 200);


        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function InsertBooking($booking_id, $offer_id, $booking_date, $booking_guest, $booking_title, $booking_firstname, $booking_lastname, $booking_email, $booking_phone, $booking_request, $time_type)
    {
        if ($time_type == 'lunch' || $time_type == 'dinner') {
            try {

                $offers = DB::table('offers')->where('id', $offer_id)->first();

                DB::beginTransaction();
                DB::table('reports')->insert([
                    'booking_id' => $booking_id,
                    'booking_hotel_id' => $offers->hotel_id,
                    'booking_restaurant_id' => $offers->restaurant_id,
                    'booking_offer_id' => $offer_id,
                    'booking_date' => Carbon::parse(date('Y-m-d', strtotime(strtr($booking_date, '/', '-')))),
                    'booking_guest' => $booking_guest,
                    'booking_contact_title' => $booking_title,
                    'booking_contact_firstname' => $booking_firstname,
                    'booking_contact_lastname' => $booking_lastname,
                    'booking_contact_email' => $booking_email,
                    'booking_contact_phone' => $booking_phone,
                    'booking_contact_request' => $booking_request,
                    'booking_time_type' => $time_type,
                    'booking_status' => 1
                ]);
                DB::commit();
            } catch (QueryException $e) {
                DB::rollback();
                throw new QueryException("Insert booking query exception");
            } catch (Exception $e) {
                throw new Exception("Insert booking exception");
            }

        } else {
            throw new Exception("Invalid time type");
        }
    }


    public function CheckDateFromOfferId($offer_id, $offer_date)
    {
        try {
            //if check exists this return true
            return DB::table('offers')->where('id', $offer_id)
                ->whereDate('offer_date_start', '<=', $offer_date)
                ->whereDate('offer_date_end', '>=', $offer_date)
                ->exists();

        } catch (QueryException $e) {
            throw new QueryException("Check date from offer id query exception");
        } catch (Exception $e) {
            throw new Exception("Check date from offer id exception");
        }
    }

    public function BookCheckBalanceExists($offer_id, $offer_date, $time_type)
    {
        try {
            $where = ['book_offer_id' => $offer_id, 'book_time_type' => $time_type];
            return DB::table('book_check_balances')->where($where)
                ->whereDate('book_offer_date', $offer_date)->exists();
        } catch (QueryException $e) {
            throw new QueryException("Book check balances exists query exception");
        } catch (Exception $e) {
            throw new Exception("Book check balances exists exception");
        }
    }

    public function CreateBalances($offer_id, $offer_date, $offer_guest, $time_type)
    {
        $offers = DB::table('offers')->where('id', $offer_id)->first();
        $book_balance = null;

        if ($time_type == 'lunch') {
            if ((int)$offer_guest > (int)$offers->offer_lunch_guest) {
                throw new Exception("Invalid operator offer guest over lunch balance");
            } else {
                $book_balance = (int)$offers->offer_lunch_guest - (int)$offer_guest;
            }
        } else if ($time_type == 'dinner') {
            if ((int)$offer_guest > (int)$offers->offer_dinner_guest) {
                throw new Exception("Invalid operator offer guest over dinner balance");
            } else {
                $book_balance = (int)$offers->offer_dinner_guest - (int)$offer_guest;
            }
        }

        try {

            DB::beginTransaction();
            DB::table('book_check_balances')->insert([
                'book_offer_id' => $offer_id,
                'book_time_type' => $time_type,
                'book_offer_date' => Carbon::parse(date('Y-m-d', strtotime(strtr($offer_date, '/', '-')))),
                'book_offer_guest' => $offer_guest,
                'book_offer_balance' => $book_balance,
                'active_id' => 1
            ]);
            DB::commit();

        } catch (QueryException $e) {
            DB::rollback();
            throw new QueryException("Create balance query exception");
        } catch
        (Exception $e) {
            throw new Exception("Create balance exception");
        }
    }
}


































