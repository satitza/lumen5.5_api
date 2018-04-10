<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Testing\HttpException;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllImages()
    {
        try {
            $images = DB::table('images')
                ->select('images.id', 'offer_id', 'offer_name_en', 'image', 'hotels.hotel_name', 'restaurants.restaurant_name')
                ->join('offers', 'offers.id', '=', 'images.offer_id')
                ->join('hotels', 'hotels.id', '=', 'offers.hotel_id')
                ->join('restaurants', 'restaurants.id', '=', 'offers.restaurant_id')
                ->orderBy('images.id', 'asc')->get();

            return response()->json([
                'images' => $images
            ]);

        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    public function GetImageOfferId($id)
    {
        try {
            $where = ['images.offer_id' => $id];
            $images = DB::table('images')
                ->select('images.id', 'offer_id', 'offer_name_en', 'image', 'hotels.hotel_name', 'restaurants.restaurant_name')
                ->join('offers', 'offers.id', '=', 'images.offer_id')
                ->join('hotels', 'hotels.id', '=', 'offers.hotel_id')
                ->join('restaurants', 'restaurants.id', '=', 'offers.restaurant_id')
                ->where($where)->get();

            return response()->json([
                'images' => $images
            ]);

        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

}