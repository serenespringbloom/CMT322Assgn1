<?php

namespace App\Http\Controllers;
use App\Models\Agenda;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function getAgendasByEvent($id)
    {
        // Fetch agendas for the given event ID
        $agendas = Agenda::where('event_id', $id)->get();
        return response()->json($agendas, 200);
    }
    
}
