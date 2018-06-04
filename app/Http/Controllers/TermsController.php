<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\QueryException;
use Laravel\Lumen\Routing\Controller as BaseController;

class TermsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllConditions()
    {
        try {

            $offers = DB::table('offers')
                ->select('offers.id', 'offer_name_en')
                ->orderBy('offers.id', 'asc')->get();

            $all_conditions = array();
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
                    $all_conditions, [
                    'offer_id' => $offer->id,
                    'offer_name_en' => $offer->offer_name_en,
                    'term_th' => $terms_th_field,
                    'term_en' => $terms_en_field,
                    'term_cn' => $terms_cn_field,
                ]);
            }

            array_filter($all_conditions);
            return response()->json($all_conditions, 200);

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

    public function GetConditionsId($offer_id)
    {
        try {

            $offers = DB::table('offers')
                ->select('offers.id', 'offer_name_en')
                ->where('offers.id', '=', $offer_id)->get();

            $all_conditions = array();
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
                    $all_conditions, [
                    'offer_id' => $offer->id,
                    'offer_name_en' => $offer->offer_name_en,
                    'term_th' => $terms_th_field,
                    'term_en' => $terms_en_field,
                    'term_cn' => $terms_cn_field,
                ]);
            }

            array_filter($all_conditions);
            return response()->json($all_conditions, 200);

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