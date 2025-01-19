<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;

class SponsorController extends Controller
{
    public function getSponsorsByEvent($eventId)
{
    $sponsors = Sponsor::where('event_id', $eventId)->get();

    if ($sponsors->isEmpty()) {
        return response()->json(['message' => 'No sponsors found for this event.'], 404);
    }

    return response()->json($sponsors, 200);
}


    public function store(Request $request, $eventId)
    {
        $request->validate([
            'sponsor_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('sponsor_logo')) {
            $fileName = time() . '.' . $request->sponsor_logo->extension();
            $request->sponsor_logo->move(public_path('images'), $fileName);

            $sponsor = new Sponsor();
            $sponsor->event_id = $eventId;
            $sponsor->sponsor_logo = $fileName;
            $sponsor->save();

            return response()->json(['message' => 'Sponsor added successfully', 'sponsor' => $sponsor]);
        }

        return response()->json(['message' => 'Sponsor logo upload failed'], 400);
    }

}
