<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\QueryException;
use Laravel\Lumen\Routing\Controller as BaseController;

class HotelsController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function GetAllHotels()
    {
        try {
            $where = ['active_id' => '1'];
            $hotels = DB::table('hotels')
                ->select('hotels.id', 'hotel_name', 'actives.active', 'hotel_comment')
                ->join('actives', 'hotels.active_id', '=', 'actives.id')
                ->orderBy('hotels.id', 'asc')->where($where)->get();
            return response()->json($hotels, 200);
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

    public function GetHotelId($id)
    {
        try {
            $where = ['active_id' => '1', 'hotels.id' => $id];
            $hotels = DB::table('hotels')
                ->select('hotels.id', 'hotel_name', 'actives.active', 'hotel_comment')
                ->join('actives', 'hotels.active_id', '=', 'actives.id')
                ->orderBy('hotels.id', 'asc')->where($where)->get();
            return response()->json($hotels, 200);
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

    /*public function GetHotelName($name)
    {
        try {
            $where = ['active_id' => '1', 'hotels.hotel_name' => $name];
            $hotels = DB::table('hotels')
                ->select('hotels.id', 'hotel_name', 'actives.active', 'hotel_comment')
                ->join('actives', 'hotels.active_id', '=', 'actives.id')
                ->orderBy('hotels.id', 'asc')->where($where)->get();
            return response()->json($hotels, 200);
        } catch (HttpRequestException $e) {
            return response()->json($e, 500);
        }
    }*/


}
