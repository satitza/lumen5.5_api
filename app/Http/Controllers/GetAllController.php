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

            /*$restaurant_pdfs = DB::table('restaurant_pdfs')
                ->select('restaurant_pdfs.id', 'restaurants.restaurant_name', 'pdf_file_name', 'pdf_title_th', 'pdf_title_en', 'pdf_title_cn')
                ->join('restaurants', 'restaurant_pdfs.restaurant_id', '=', 'restaurants.id')
                ->orderBy('restaurant_pdfs.id', 'ASC')->get();*/

            $offers = DB::table('offers')
                ->select('offers.id', 'hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'pdf', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->orderBy('offers.id', 'asc')->get();

            $images = DB::table('images')
                ->select('images.id', 'offer_id', 'offer_name_en', 'image', 'hotels.hotel_name', 'restaurants.restaurant_name')
                ->join('offers', 'offers.id', '=', 'images.offer_id')
                ->join('hotels', 'hotels.id', '=', 'offers.hotel_id')
                ->join('restaurants', 'restaurants.id', '=', 'offers.restaurant_id')
                ->orderBy('images.id', 'asc')->get();

            return response()->json([
                'hotels' => $hotels,
                'restaurant' => $restaurants,
                //'restaurant_pdf' => $restaurant_pdfs,
                'offers' => $offers,
                'images' => $images
            ], 200);

        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }
}