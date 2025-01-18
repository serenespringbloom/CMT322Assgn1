<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function store(Request $request)
    {
        Log::info('Request Data:', $request->all()); // Log incoming request
    
        $validatedData = $request->validate([
            'event_title' => 'required|string|max:255',
            'event_subtitle' => 'nullable|string|max:255',
            'event_venue' => 'required|string|max:255',
            'event_datetime' => 'required|date',
            'event_about' => 'nullable|string',
            'event_email' => 'required|email',
            'event_phone' => 'required|string|max:15',
        ]);
    
        try {
            $event = Event::create($validatedData);
            return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
        } catch (\Exception $e) {
            Log::error('Error creating event:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating event', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $request->validate([
            'event_title' => 'required|string|max:255',
            'event_subtitle' => 'nullable|string|max:255',
            'event_venue' => 'nullable|string|max:255',
            'event_datetime' => 'nullable|date',
            'event_about' => 'nullable|string',
            'event_email' => 'nullable|email',
            'event_phone' => 'nullable|string|max:15',
            'last_updated_by' => 'nullable|integer',
        ]);

        $event->update($request->all());

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
    }

    public function destroy($event_id)
    {
        $event = Event::findOrFail($event_id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

}