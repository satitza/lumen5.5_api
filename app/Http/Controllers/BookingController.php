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
    /**
     * BookingController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        try {
            $this->middleware('auth');

            $this->validate($request, [
                'booking_id' => 'required',
                'booking_offer_id' => 'required|integer',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'booking_time' => 'required',
                'booking_guest' => 'required|integer|between:1,1000',
                'booking_contact_title' => 'required',
                'booking_contact_firstname' => 'required',
                'booking_contact_lastname' => 'required',
                'booking_contact_email' => 'required|email',
                'booking_contact_phone' => 'required|numeric',
                'booking_time_type' => 'required',
                'voucher' => 'required'
            ]);

            $GLOBALS['book_id'] = $request->booking_id;
            $GLOBALS['offer_id'] = $request->booking_offer_id;
            $GLOBALS['book_date'] = $request->booking_date;
            $GLOBALS['book_time'] = $request->booking_time;
            $GLOBALS['book_guest'] = $request->booking_guest;
            $GLOBALS['contact_title'] = $request->booking_contact_title;
            $GLOBALS['contact_firstname'] = $request->booking_contact_firstname;
            $GLOBALS['contact_lastname'] = $request->booking_contact_lastname;
            $GLOBALS['contact_email'] = $request->booking_contact_email;
            $GLOBALS['contact_phone'] = $request->booking_contact_phone;
            $GLOBALS['contact_request'] = $request->booking_contact_request;
            $GLOBALS['time_type'] = $request->booking_time_type;
            $GLOBALS['voucher'] = $request->voucher;
            $GLOBALS['voucher_status'] = 1;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function booking(Request $request)
    {
        /**
         * Check voucher value
         */
        if ((int)$GLOBALS['voucher'] != 1 && (int)$GLOBALS['voucher'] != 2) {
            return response()->json([
                'message' => 'Invalid voucher value'
            ]);
        } else if ($GLOBALS['voucher'] == 2) {

            $this->validate($request, [
                'booking_contact_title_v' => 'required',
                'booking_contact_firstname_v' => 'required',
                'booking_contact_lastname_v' => 'required',
                'booking_contact_email_v' => 'required',
                'booking_contact_phone_v' => 'required',
            ]);

            $GLOBALS['contact_title_v'] = $request->booking_contact_title_v;
            $GLOBALS['contact_firstname_v'] = $request->booking_contact_firstname_v;
            $GLOBALS['contact_lastname_v'] = $request->booking_contact_lastname_v;
            $GLOBALS['contact_email_v'] = $request->booking_contact_email_v;
            $GLOBALS['contact_phone_v'] = $request->booking_contact_phone_v;
            $GLOBALS['contact_request_v'] = $request->booking_contact_request_v;
            $GLOBALS['voucher_status'] = 2;
        }


        /**
         * Verify time type between lunch or dinner
         */
        if ($GLOBALS['time_type'] == "lunch" || $GLOBALS['time_type'] == "dinner") {
            /**
             * Verify booking id
             */
            if (DB::table('reports')->where('booking_id', $GLOBALS['book_id'])->exists()) {
                return response()->json([
                    'message' => 'Booking id was already'
                ], 500);
            } else {
                /**
                 * Check offer exists
                 */
                try {
                    if ($this->CheckOfferExists($GLOBALS['offer_id'], $GLOBALS['book_date']) == true) {
                        /**
                         * Check balance exists
                         */
                        if ($this->CheckBalanceExists($GLOBALS['offer_id'], $GLOBALS['book_date'], $GLOBALS['time_type']) == true) {
                            /**
                             * Check guest over balance
                             */
                            if ($this->CheckGuestOverBalance($GLOBALS['offer_id'], $GLOBALS['time_type'], $GLOBALS['book_date'], $GLOBALS['book_guest']) == false) {
                                /**
                                 * Create booking
                                 */
                                $this->create_booking(
                                    $GLOBALS['book_id'],
                                    $GLOBALS['offer_id'],
                                    $GLOBALS['book_date'],
                                    $GLOBALS['book_time'],
                                    $GLOBALS['book_guest'],
                                    $GLOBALS['contact_title'],
                                    $GLOBALS['contact_firstname'],
                                    $GLOBALS['contact_lastname'],
                                    $GLOBALS['contact_email'],
                                    $GLOBALS['contact_phone'],
                                    $GLOBALS['contact_request'],
                                    $GLOBALS['time_type'],
                                    $GLOBALS['voucher_status']
                                );

                                /**
                                 * Create Voucher user if voucher == true
                                 */
                                if ((int)$GLOBALS['voucher'] == 2) {
                                    $this->create_voucher(
                                        $GLOBALS['book_id'],
                                        $GLOBALS['contact_title_v'],
                                        $GLOBALS['contact_firstname_v'],
                                        $GLOBALS['contact_lastname_v'],
                                        $GLOBALS['contact_email_v'],
                                        $GLOBALS['contact_phone_v'],
                                        $GLOBALS['contact_request_v']
                                    );
                                }else{
                                    $this->update_balance(
                                        $GLOBALS['offer_id'],
                                        $GLOBALS['book_date'],
                                        $GLOBALS['book_guest'],
                                        $GLOBALS['time_type']
                                    );
                                }

                                return response()->json([
                                    'message' => 'Create booking success'
                                ]);

                            } else {
                                return response()->json([
                                    'message' => 'Booking guest is over balance'
                                ]);
                            }
                        } else {
                            /**
                             * Check guest over offer
                             */
                            if ($this->CheckGuestOverOffer($GLOBALS['offer_id'], $GLOBALS['time_type'], $GLOBALS['book_guest']) == false) {
                                /**
                                 * Create booking
                                 */
                                $this->create_booking(
                                    $GLOBALS['book_id'],
                                    $GLOBALS['offer_id'],
                                    $GLOBALS['book_date'],
                                    $GLOBALS['book_time'],
                                    $GLOBALS['book_guest'],
                                    $GLOBALS['contact_title'],
                                    $GLOBALS['contact_firstname'],
                                    $GLOBALS['contact_lastname'],
                                    $GLOBALS['contact_email'],
                                    $GLOBALS['contact_phone'],
                                    $GLOBALS['contact_request'],
                                    $GLOBALS['time_type'],
                                    $GLOBALS['voucher_status']
                                );

                                /**
                                 * Create Voucher user if voucher == true
                                 */
                                if ((int)$GLOBALS['voucher'] == 2) {
                                    $this->create_voucher(
                                        $GLOBALS['book_id'],
                                        $GLOBALS['contact_title_v'],
                                        $GLOBALS['contact_firstname_v'],
                                        $GLOBALS['contact_lastname_v'],
                                        $GLOBALS['contact_email_v'],
                                        $GLOBALS['contact_phone_v'],
                                        $GLOBALS['contact_request_v']
                                    );
                                }else{
                                    /**
                                     * Create balance
                                     */
                                    $this->create_balance(
                                        $GLOBALS['offer_id'],
                                        $GLOBALS['book_date'],
                                        $GLOBALS['book_guest'],
                                        $GLOBALS['time_type']
                                    );
                                }

                                return response()->json([
                                    'message' => 'Create booking success'
                                ], 200);

                            } else {
                                return response()->json([
                                    'message' => 'Booking guest is over offer'
                                ], 500);
                            }
                        }

                    } else {
                        return response()->json([
                            'message' => 'Offers not found'
                        ], 500);
                    }

                } catch (QueryException $e) {
                    return response()->json([
                        'message' => $e->getMessage()
                    ], 500);

                } catch (Exception $e) {
                    return response()->json([
                        'message' => $e->getMessage()
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'message' => 'Invalid time type'
            ], 503);
        }
    }

    /**
     * @param $offer_id
     * @param $offer_date
     * @return true or false
     */
    public function CheckOfferExists($offer_id, $book_date)
    {
        try {
            return DB::table('offers')->where('id', $offer_id)
                ->whereDate('offer_date_start', '<=', $book_date)
                ->whereDate('offer_date_end', '>=', $book_date)
                ->exists();

        } catch (QueryException $e) {
            throw new QueryException("Check offer exists query exception");
        } catch (Exception $e) {
            throw new Exception("Check offer exists exception");
        }
    }

    /**
     * @param $offer_id
     * @param $offer_date
     * @param $time_type
     * @return true or false
     */
    public function CheckBalanceExists($offer_id, $book_date, $time_type)
    {
        try {
            $where = ['book_offer_id' => $offer_id, 'book_time_type' => $time_type];
            return DB::table('book_check_balances')->where($where)
                ->whereDate('book_offer_date', $book_date)->exists();
        } catch (QueryException $e) {
            throw new QueryException("Check balances exists query exception");
        } catch (Exception $e) {
            throw new Exception("Check balances exists exception");
        }
    }

    /**
     * @param $offer_id
     * @param $time_type
     * @param $book_guest
     * @return true or false
     */
    public function CheckGuestOverOffer($offer_id, $time_type, $book_guest)
    {
        try {
            $offer_guest = null;
            if ($time_type == 'lunch') {
                $offer_guest = 'offer_lunch_guest';
            } else if ($time_type == 'dinner') {
                $offer_guest = 'offer_dinner_guest';
            }

            $where = ['id' => $offer_id];
            $offers = DB::table('offers')->where($where)->first();

            if ((int)$book_guest > (int)$offers->$offer_guest) {
                return true;
            } else {
                return false;
            }

        } catch (QueryException $e) {
            throw new QueryException("Check guest over offer query exception");
        } catch (Exception $e) {
            throw new Exception("Check guest over offer exists exception");
        }
    }

    /**
     * @param $offer_id
     * @param $time_type
     * @param $book_date
     * @param $book_guest
     * @return true or false
     */
    public function CheckGuestOverBalance($offer_id, $time_type, $book_date, $book_guest)
    {
        try {
            $where = ['book_offer_id' => $offer_id, 'book_time_type' => $time_type];
            $balances = DB::table('book_check_balances')->where($where)
                ->WhereDate('book_offer_date', $book_date)->first();
            if ((int)$book_guest > (int)$balances->book_offer_balance) {
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            throw new QueryException("Check guest over balances query exception");
        } catch (Exception $e) {
            throw new Exception("Check guest over balances exists exception");
        }
    }

    /**
     * @param $book_id
     * @param $offer_id
     * @param $book_date
     * @param $book_guest
     * @param $book_title
     * @param $book_firstname
     * @param $book_lastname
     * @param $book_email
     * @param $book_phone
     * @param $book_request
     * @param $time_type
     */
    public function create_booking(
        $book_id,
        $offer_id,
        $book_date,
        $book_time,
        $book_guest,
        $book_title,
        $book_firstname,
        $book_lastname,
        $book_email,
        $book_phone,
        $book_request,
        $time_type,
        $voucher_status
    )
    {
        try {

            $book_price = null;
            $offers = DB::table('offers')->where('id', $offer_id)->first();

            if ($time_type == 'lunch') {
                $book_price = (int)$offers->offer_lunch_price * (int)$book_guest;
            } else if ($time_type == 'dinner') {
                $book_price = (int)$offers->offer_dinner_price * (int)$book_guest;
            }

            DB::beginTransaction();
            DB::table('reports')->insert([
                'booking_id' => $book_id,
                'booking_hotel_id' => $offers->hotel_id,
                'booking_restaurant_id' => $offers->restaurant_id,
                'booking_offer_id' => $offer_id,
                'booking_date' => Carbon::parse(date('Y-m-d', strtotime(strtr($book_date, '/', '-')))),
                'booking_time' => $book_time,
                'booking_guest' => $book_guest,
                'booking_contact_title' => $book_title,
                'booking_contact_firstname' => $book_firstname,
                'booking_contact_lastname' => $book_lastname,
                'booking_contact_email' => $book_email,
                'booking_contact_phone' => $book_phone,
                'booking_contact_request' => $book_request,
                'booking_price' => $book_price,
                'booking_time_type' => $time_type,
                'booking_voucher' => $voucher_status,
                'booking_status' => 1
            ]);
            DB::commit();

        } catch (QueryException $e) {
            DB::rollback();
            throw new QueryException("Insert booking query exception");
        } catch (Exception $e) {
            throw new Exception("Insert booking exception");
        }
    }


    /**
     * @param $book_id
     * @param $book_title_v
     * @param $book_firstname_v
     * @param $book_lastname_v
     * @param $book_email_v
     * @param $book_phone_v
     * @param $book_request_v
     */
    public function create_voucher(
        $book_id,
        $book_title_v,
        $book_firstname_v,
        $book_lastname_v,
        $book_email_v,
        $book_phone_v,
        $book_request_v
    )
    {
        try {

            DB::beginTransaction();
            DB::table('vouchers')->insert([
                'voucher_booking_id' => $book_id,
                'voucher_contact_title' => $book_title_v,
                'voucher_contact_firstname' => $book_firstname_v,
                'voucher_contact_lastname' => $book_lastname_v,
                'voucher_contact_email' => $book_email_v,
                'voucher_contact_phone' => $book_phone_v,
                'voucher_contact_request' => $book_request_v,

            ]);
            DB::commit();

        } catch (QueryException $e) {
            DB::rollback();
            throw new QueryException("Insert booking query exception");
        } catch (Exception $e) {
            throw new Exception("Insert booking exception");
        }
    }

    /**
     * @param $offer_id
     * @param $book_date
     * @param $book_guest
     * @param $time_type
     */
    public function create_balance($offer_id, $book_date, $book_guest, $time_type)
    {
        $offers = DB::table('offers')->where('id', $offer_id)->first();
        $book_balance = null;

        if ($time_type == 'lunch') {
            $book_balance = (int)$offers->offer_lunch_guest - (int)$book_guest;
        } else if ($time_type == 'dinner') {
            $book_balance = (int)$offers->offer_dinner_guest - (int)$book_guest;
        }

        try {

            DB::beginTransaction();
            DB::table('book_check_balances')->insert([
                'book_offer_id' => $offer_id,
                'book_time_type' => $time_type,
                'book_offer_date' => Carbon::parse(date('Y-m-d', strtotime(strtr($book_date, '/', '-')))),
                'book_offer_guest' => $book_guest,
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

    /**
     * @param $offer_id
     * @param $book_date
     * @param $book_guest
     * @param $time_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_balance($offer_id, $book_date, $book_guest, $time_type)
    {
        try {
            $where = ['book_offer_id' => $offer_id, 'book_time_type' => $time_type, 'active_id' => 1];
            $old_guests = DB::table('book_check_balances')->select('book_offer_balance')
                ->where($where)
                ->whereDate('book_offer_date', $book_date)
                ->first();


            if (!isset($old_guests)) {
                return response()->json([
                    'message' => 'Cannot update balance because this balance is disable'
                ], 500);
            } else if ((int)$book_guest > (int)$old_guests->book_offer_balance) {
                return response()->json([
                    'message' => 'Cannot update balance because booking guest is over'
                ], 500);
            } else {

                $new_guest = (int)$old_guests->book_offer_balance - (int)$book_guest;

                DB::beginTransaction();
                DB::table('book_check_balances')->where($where)
                    ->whereDate('book_offer_date', $book_date)->update([
                        'book_offer_guest' => $book_guest,
                        'book_offer_balance' => $new_guest
                    ]);
                DB::commit();
            }
        } catch (QueryException $e) {
            DB::rollback();
            throw new QueryException("Create balance query exception");
        } catch
        (Exception $e) {
            throw new Exception("Create balance exception");
        }
    }
}


































