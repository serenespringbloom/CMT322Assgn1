<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
//     public function index()
//     {
//         $events = Event::all(); // Fetch all events from the database
//         return response()->json($events);
//     }

//     public function show($id)
//  {
//     $event = Event::find($id);

//     if ($event) {
//         return response()->json($event, 200);
//     } else {
//         return response()->json(['message' => 'Event not found'], 404);
//     }
//  }
    public function getEvent($id = null)
    {
        if ($id) {
            $event = Event::find($id); // Fetch the specific event by ID
        } else {
            $event = Event::all(); // Or fetch all events
        }

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event, 200);
    }
}