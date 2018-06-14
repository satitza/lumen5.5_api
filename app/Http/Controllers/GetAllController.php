<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\QueryException;
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
                ->select('hotels.id', 'hotel_name', 'hotels.mid', 'hotels.secret_key', 'actives.active', 'hotel_comment')
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
                ->select('offers.id', 'offers.hotel_id','hotels.hotel_name', 'restaurant_id', 'restaurants.restaurant_name',
                    'attachments', 'offer_name_th', 'offer_name_en', 'offer_name_cn', 'offer_date_start', 'offer_date_end', 'offer_day_select',
                    'offer_time_lunch_start', 'offer_time_lunch_end', 'offer_lunch_price', 'offer_lunch_guest', 'offer_time_dinner_start',
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_short_th', 'offer_short_en', 'offer_short_cn',
                    'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->orderBy('offers.id', 'asc')->get();

            $all_offers = array();
            foreach ($offers as $offer) {

                $terms_th = DB::table('termsths')
                    ->select('term_header_th', 'term_content_th')->where('offer_id', '=', $offer->id)
                    ->join('offers', 'termsths.offer_id', '=', 'offers.id')
                    ->orderBy('termsths.id', 'asc')->get();

                $terms_th_field = array();
                foreach ($terms_th as $term_th) {
                    array_push($terms_th_field, [$term_th->term_header_th => $term_th->term_content_th]);
                }

                $terms_en = DB::table('termsens')
                    ->select('term_header_en', 'term_content_en')->where('offer_id', '=', $offer->id)
                    ->join('offers', 'termsens.offer_id', '=', 'offers.id')
                    ->orderBy('termsens.id', 'asc')->get();

                $terms_en_field = array();
                foreach ($terms_en as $term_en) {
                    array_push($terms_en_field, [$term_en->term_header_en => $term_en->term_content_en]);
                }


                $terms_cn = DB::table('termscns')
                    ->select('term_header_cn', 'term_content_cn')->where('offer_id', '=', $offer->id)
                    ->join('offers', 'termscns.offer_id', '=', 'offers.id')
                    ->orderBy('termscns.id', 'asc')->get();

                $terms_cn_field = array();
                foreach ($terms_cn as $term_cn) {
                    array_push($terms_cn_field, [$term_cn->term_header_cn => $term_cn->term_content_cn]);
                }

                array_push(
                    $all_offers, [
                    'id' => $offer->id,
                    'hotel_id' => $offer->hotel_id,
                    'hotel_name' => $offer->hotel_name,
                    'restaurant_id' => $offer->restaurant_id,
                    'restaurant_name' => $offer->restaurant_name,
                    'attachments' => $offer->attachments,
                    'offer_name_th' => $offer->offer_name_th,
                    'offer_name_en' => $offer->offer_name_en,
                    'offer_name_cn' => $offer->offer_name_cn,
                    'offer_date_start' => $offer->offer_date_start,
                    'offer_date_end' => $offer->offer_date_end,
                    'offer_day_select' => $offer->offer_day_select,
                    'offer_time_lunch_start' => $offer->offer_time_lunch_start,
                    'offer_time_lunch_end' => $offer->offer_time_lunch_end,
                    'offer_lunch_price' => $offer->offer_lunch_price,
                    'offer_lunch_guest' => $offer->offer_lunch_guest,
                    'offer_time_dinner_start' => $offer->offer_time_dinner_start,
                    'offer_time_dinner_end' => $offer->offer_time_dinner_end,
                    'offer_dinner_price' => $offer->offer_dinner_price,
                    'offer_dinner_guest' => $offer->offer_dinner_guest,
                    'offer_short_th' => $offer->offer_short_th,
                    'offer_short_en' => $offer->offer_short_en,
                    'offer_short_cn' => $offer->offer_short_cn,
                    'offer_comment_th' => $offer->offer_comment_th,
                    'offer_comment_en' => $offer->offer_comment_en,
                    'offer_comment_cn' => $offer->offer_comment_cn,
                    'term_th' => $terms_th_field,
                    'term_en' => $terms_en_field,
                    'term_cn' => $terms_cn_field,
                    //'conditions' => [$terms_th_field, $terms_en_field, $terms_cn_field]
                ]);
            }

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
                'offers' => $all_offers,
                'images' => $images
            ], 200);

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
}