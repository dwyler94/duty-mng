<?php

namespace App\Http\Controllers;

use App\Mail\MonthlySummaryApprove;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function __construct()
    {

    }
    public function test()
    {
        // Mail::to("devcrazy@hotmail.com")->queue(new MonthlySummaryApprove("Test Office", "2021", "2"));
        // return "ok";
        // return view('childcare');
        return response()->json(['test' => 'ok']);
    }
}
