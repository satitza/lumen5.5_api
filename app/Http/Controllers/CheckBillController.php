<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mockery\Exception;

class CheckBillController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}