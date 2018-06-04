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

            array_filter($all_offers);
            return response()->json($all_offers, 200);
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
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_short_th', 'offer_short_en', 'offer_short_cn',
                    'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();

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

            return response()->json($all_offers, 200);
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
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_short_th', 'offer_short_en', 'offer_short_cn',
                    'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();

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

            return response()->json($all_offers, 200);
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
                    'offer_time_dinner_end', 'offer_dinner_price', 'offer_dinner_guest', 'offer_short_th', 'offer_short_en', 'offer_short_cn',
                    'offer_comment_th', 'offer_comment_en', 'offer_comment_cn')
                ->join('hotels', 'offers.hotel_id', '=', 'hotels.id')
                ->join('restaurants', 'offers.restaurant_id', '=', 'restaurants.id')
                ->where($where)->get();

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

            return response()->json($all_offers, 200);
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
