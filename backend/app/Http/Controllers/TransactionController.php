<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        
       
        $ticket = new Transaction();
        
        $ticket->student_tickets = $request->input('student_tickets');
        $ticket->public_tickets = $request->input('public_tickets');
        $ticket->total_price = $request->input('total_price');
        $ticket->selected_bank = $request->input('selected_bank');
        $ticket->save();

        return response()->json([
            'testing'=>'test',
            'message' => 'Refund uploaded successfully',
          
        ], 200);
    
    
    

    }

}
