<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mockery\Exception;

class BalanceController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllBalances()
    {
        try {

            $balances = DB::table('book_check_balances')
                ->select('book_check_balances.id', 'book_offer_id', 'offers.offer_name_en')
                ->join('offers', 'book_check_balances.book_offer_id', '=', 'offers.id')
                ->orderBy('book_check_balances.id', 'asc')->get();

            return response()->json($balances, 200);

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

    public function GetBalance($offer_id, $offer_date, $offer_time_type)
    {
        return response()->json([
            'message' => 'get balance'
        ]);
    }
}