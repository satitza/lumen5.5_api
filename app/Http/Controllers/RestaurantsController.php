<?php

namespace App\Http\Controllers;

use DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class RestaurantsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');

        $GLOBALS['enable'] = 1;
        $GLOBALS['disable'] = 2;

    }

    public function GetAllRestaurants()
    {
        try {
            $where = ['restaurants.active_id' => $GLOBALS['enable']];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'restaurant_phone', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function GetRestaurantId($id)
    {
        try {
            $where = ['restaurants.active_id' => $GLOBALS['enable'], 'restaurants.id' => $id];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'restaurant_phone', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
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
            $where = ['restaurants.active_id' => $GLOBALS['enable'], 'restaurants.hotel_id' => $id];
            $restaurants = DB::table('restaurants')
                ->select('restaurants.id', 'restaurant_name', 'restaurant_email', 'restaurant_phone', 'hotel_name', 'actives.active', 'restaurant_comment')
                ->join('hotels', 'restaurants.hotel_id', '=', 'hotels.id')
                ->join('actives', 'restaurants.active_id', '=', 'actives.id')
                ->orderBy('restaurants.id', 'asc')->where($where)->get();
            return response()->json($restaurants, 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
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
