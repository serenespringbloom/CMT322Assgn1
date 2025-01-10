<?php

namespace App\Http\Controllers;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $seat = Seat::all();
        return response()->json($seat);
    }

}
