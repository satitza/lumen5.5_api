<?php

namespace App\Http\Controllers;

use DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class RestaurantsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllRestaurants()
    {
        try {
            $where = ['restaurants.active_id' => '1'];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (HttpRequestException $e) {
            return response()->json($e, 500);
        }
    }

    public function GetRestaurantId($id)
    {
        try {
            $where = ['restaurants.active_id' => '1', 'restaurants.id' => $id];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    /*public function GetRestaurantName($name)
    {
        try {
            $where = ['restaurants.active_id' => '1', 'restaurants.restaurant_name' => $name];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }*/

    public function GetAllRestaurantHotelId($id)
    {
        try {
            $where = ['restaurants.active_id' => '1', 'restaurants.hotel_id' => $id];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    /*public function GetAllRestaurantHotelName($name)
    {
        try {
            $where = ['restaurants.active_id' => '1', 'hotel_name' => $name];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }*/
}
