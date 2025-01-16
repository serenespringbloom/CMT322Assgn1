<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        try {
            $feedbacks = Feedback::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $feedbacks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch feedbacks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'ticket_id' => 'required|string',
                'name' => 'required|string|max:255',
                'programme' => 'required|string|max:255',
                'feedback_text' => 'required|string',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            // Check if ticket exists
            $ticket = Transaction::where('transaction_id', $request->ticket_id)->first();
            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ticket ID'
                ], 400);
            }

            // Check if feedback already exists for this ticket
            $existingFeedback = Feedback::where('ticket_id', $request->ticket_id)->first();
            if ($existingFeedback) {
                return response()->json([
                    'success' => false,
                    'message' => 'Feedback already submitted for this ticket'
                ], 400);
            }

            // Create new feedback
            $feedback = Feedback::create([
                'ticket_id' => $request->ticket_id,
                'name' => $request->name,
                'programme' => $request->programme,
                'feedback_text' => $request->feedback_text,
                'rating' => $request->rating,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'data' => $feedback
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Feedback deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
