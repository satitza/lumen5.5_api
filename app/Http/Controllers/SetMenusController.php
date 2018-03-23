<?php

namespace App\Http\Controllers;

use DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class SetMenusController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllMenus()
    {
        try {
            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name', 'languages.language',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->join('languages', 'set_menus.language_id', '=', 'languages.id')
                ->orderBy('set_menus.id', 'asc')->get();

            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    public function GetMenuId($id)
    {
        try {
            $where = ['set_menus.id' => $id];
            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name', 'languages.language',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->join('languages', 'set_menus.language_id', '=', 'languages.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    /*public function GetMenuName($name)
    {
        try {
            $where = ['set_menus.menu_name' => $name];
            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }*/

    public function GetAllMenuHotelId($id)
    {
        try {
            $where = ['set_menus.hotel_id' => $id];
            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name', 'languages.language',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->join('languages', 'set_menus.language_id', '=', 'languages.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    /*public function GetAllMenuHotelName($name)
    {
        try {
            $hotel_name = DB::table('hotels')->where('hotel_name', '=', $name)->first();
            $where = ['set_menus.hotel_id' => $hotel_name->id];

            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }*/

    public function GetAllMenuRestaurantId($id)
    {
        try {
            $where = ['set_menus.restaurant_id' => $id];
            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name', 'languages.language',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->join('languages', 'set_menus.language_id', '=', 'languages.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    /*public function GetAllMenuRestaurantName($name)
    {
        try {
            $res_name = DB::table('restaurants')->where('restaurant_name', '=', $name)->first();
            $where = ['set_menus.restaurant_id' => $res_name->id];

            $set_menus = DB::table('set_menus')
                ->select('set_menus.id', 'hotels.hotel_name', 'restaurants.restaurant_name',
                    'menu_name', 'menu_date_start', 'menu_date_end', 'menu_date_select',
                    'menu_time_lunch_start', 'menu_time_lunch_end', 'menu_time_dinner_start',
                    'menu_time_dinner_end', 'menu_price', 'menu_guest', 'menu_comment')
                ->join('hotels', 'set_menus.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'set_menus.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($set_menus, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }*/
}
