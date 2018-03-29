<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Testing\HttpException;
use Laravel\Lumen\Routing\Controller as Basecontroller;

class GetAllController extends Basecontroller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAll()
    {
        try {

            $where = ['hotels.active_id' => '1'];
            $hotels = DB::table('hotels')
                ->select('hotels.id', 'hotel_name', 'actives.active', 'hotel_comment')
                ->join('actives', 'hotels.active_id', '=', 'actives.id')
                ->orderBy('hotels.id', 'asc')->where($where)->get();

            $where = ['restaurants.active_id' => '1'];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();

            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'set_menus.restaurant_id','restaurants.restaurant_name', 'languages.language',
                    'menu_name', 'image','menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->join('languages', 'set_menus.language_id', '=', 'languages.id')
                ->orderBy('set_menus.id', 'asc')->get();

            return response()->json([
                'hotels' => $hotels,
                'restaurant' => $restaurants,
                'set_menu' => $set_menus
            ], 200);

        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }
}