<?php

namespace App\Http\Controllers;

use DB;
use http\Env\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class BookCheckBalancesController extends BaseController
{
     public function __construct()
     {
         $this->middleware('auth', ['only' => [
             'createBalance'
         ]]);
     }

     public function createBalance(Request $request){
         try{
             return response()->json($request->input('id'));
         }
         catch (HttpException $e){
             return response()->json($e, 500);
         }
     }
}