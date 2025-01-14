<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Feedback;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $tickets = Transaction::all();
        return response()->json($tickets);
    }

    public function index2()
    {
        $tickets = Transaction::all();
        return response()->json($tickets);
    }

 

    public function store( Request $request)
    {
       
        $feedback = new Feedback();
        
        $feedback->ticket_id = $request->input('ticket_id');
        $feedback->name = $request->input('name');
        $feedback->programme = $request->input('programme');
        $feedback->rating = $request->input('rating');
        $feedback->feedback_text = $request->input('feedback_text');
        $feedback->save();

      

    }

       public function update(Request $request,$id)
    {
        $tickets = Transaction::find($id);
        if (!$tickets) {
            return response()->json(['message' => 'Tickets not found'], 404);
        }
    
        
        
        $tickets->update([
            'ticket_category' => $request->ticket_category,
            
        ]);
    
        return response()->json(['message' => 'Image deleted successfully']);
    }
}
