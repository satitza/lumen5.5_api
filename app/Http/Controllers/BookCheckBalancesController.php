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

            if ($this->CheckDateFromSetMenu($request->input('menu_id'), $request->input('menu_date')) == true) {
                if ($this->BookCheckBalanceExists($request->input('menu_id'), $request->input('menu_date')) == true) {
                    //This menu in date has created in book balance



                } else {
                    //Select guest from set_menus and create new rows


                }
            } else {
                return response()->json([
                    'msg' => 'cannot find menu from date request'
                ], 200);
            }


        } catch (HttpException $e) {
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    public function CheckDateFromSetMenu($menu_id, $menu_date)
    {
        try {
            return DB::table('set_menus')->where('id', $menu_id)
                ->whereDate('menu_date_start', '<=', $menu_date)
                ->whereDate('menu_date_end', '>=', $menu_date)
                ->exists();
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e
            ]);
        }
    }

    public function BookCheckBalanceExists($menu_id, $menu_date)
    {
        try {
            //$where = ['menu_id' => $menu_id, 'menu_date' => $menu_date];
            return DB::table('book_check_balances')->where('book_menu_id', $menu_id)
                ->whereDate('book_menu_date', $menu_date)->exists();
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e
            ]);
        }
    }
}