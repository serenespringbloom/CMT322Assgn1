<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function getAgendasByEvent($id)
{
    // Fetch agendas for the given event ID
    $agendas = Agenda::where('event_id', $id)->get();

    if ($agendas->isEmpty()) {
        return response()->json(['message' => 'No agendas found for this event.'], 404);
    }

    return response()->json($agendas, 200);

}


    public function store(Request $request, $eventId)
    {
        $request->validate([
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'agenda_title' => 'required|string|max:255',
            'agenda_description' => 'nullable|string',
        ]);

        $agenda = new Agenda($request->all());
        $agenda->event_id = $eventId;
        $agenda->save();

        return response()->json(['message' => 'Agenda added successfully', 'agenda' => $agenda]);
    }

}
