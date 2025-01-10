<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $refunds = Feedback::all();
        return response()->json($refunds);
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

       public function deleteFeedback($id)
    {
        $image = Feedback::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        
    
        $image->delete();
    
        return response()->json(['message' => 'Image deleted successfully']);
    }
}
