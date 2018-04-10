<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Testing\HttpException;
use Laravel\Lumen\Routing\Controller as Basecontroller;

class RestaurantPdfController extends Basecontroller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllPdf()
    {
        try {
            //$where = ['active_id' => '1'];
            $restaurant_pdfs = DB::table('restaurant_pdfs')
                ->select('restaurant_pdfs.id', 'restaurants.restaurant_name', 'pdf_file_name', 'pdf_title_th', 'pdf_title_en', 'pdf_title_cn')
                ->join('restaurants', 'restaurant_pdfs.restaurant_id', '=', 'restaurants.id')
                ->orderBy('restaurant_pdfs.id', 'ASC')->get();
            return response()->json($restaurant_pdfs, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    public function GetPdfId($id)
    {
        try {
            $where = ['restaurant_pdfs.id' => $id];
            $restaurant_pdfs = DB::table('restaurant_pdfs')
                ->select('restaurant_pdfs.id', 'restaurants.restaurant_name', 'pdf_file_name', 'pdf_title_th', 'pdf_title_en', 'pdf_title_cn')
                ->join('restaurants', 'restaurant_pdfs.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($restaurant_pdfs, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }

    public function GetPdfRestaurantId($id)
    {
        try {
            $where = ['restaurant_pdfs.restaurant_id' => $id];
            $restaurant_pdfs = DB::table('restaurant_pdfs')
                ->select('restaurant_pdfs.id', 'restaurants.restaurant_name', 'pdf_file_name', 'pdf_title_th', 'pdf_title_en', 'pdf_title_cn')
                ->join('restaurants', 'restaurant_pdfs.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();
            return response()->json($restaurant_pdfs, 200);
        } catch (HttpException $e) {
            return response()->json($e, 500);
        }
    }
}