<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        return response()->json(Seat::all());
    }

    public function book(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);

        if ($seat->is_booked) {
            return response()->json(['message' => 'Seat is already booked'], 400);
        }

        $seat->is_booked = true;
        $seat->save();

        return response()->json(['message' => 'Seat booked successfully']);
    }
}
