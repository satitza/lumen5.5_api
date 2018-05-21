<?php

namespace App\Http\Controllers;

use DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class OffersController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllOffers()
    {
        try {
            $offers = DB::table('offers')
                ->select('offers.id', 'hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'attachments', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->orderBy('offers.id', 'asc')->get();

            return response()->json($offers, 200);
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

    public function GetOfferId($id)
    {
        try {
            $where = ['offers.id' => $id];
            $offers = DB::table('offers')
                ->select('offers.id', 'hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'attachments', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($offers, 200);
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

    public function GetAllOfferHotelId($id)
    {
        try {
            $where = ['offers.hotel_id' => $id];
            $offers = DB::table('offers')
                ->select('offers.id', 'hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'attachments', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($offers, 200);
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

    public function GetAllOfferRestaurantId($id)
    {
        try {
            $where = ['offers.restaurant_id' => $id];
            $offers = DB::table('offers')
                ->select('offers.id', 'hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'attachments', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($offers, 200);
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
