<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BookCheckBalancesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'createBalance'
        ]]);
    }

    public function createBalance(Request $request)
    {
        try {
            $this->validate($request, [
                'menu_id' => 'required',
                'menu_date' => 'required',
                'menu_guest' => 'required'
            ]);



            return response()->json($this->CheckMenuDate($request->input('menu_date')));

            /*return response()->json([
                'menu_id : ' => $request->input('menu_id'),
                'menu_date : ' => $request->input('menu_date'),
                'menu_guest : ' => $request->input('menu_guest')
            ], 200);*/


        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    public function CheckMenuDate($menu_date)
    {
        try {
            //Carbon::parse(date('Y-m-d', strtotime(strtr($request->menu_date_start, '/', '-'))));
            if (DB::table('book_check_balances')->where('book_menu_date', '=', $menu_date)->exists()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}