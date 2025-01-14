<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;

class SponsorController extends Controller
{
    public function getSponsorsByEvent($eventId)
    {
        $sponsors = Sponsor::where('event_id', $eventId)->get();

        return response()->json($sponsors, 200);
    }
}

