<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{
    public function index()
    {
        $refunds = RefundRequest::all();
        return response()->json($refunds);
    }


    public function validatePid($pid)
    {
        $isValid = RefundRequest::where('transaction_id', $pid)->exists();
    
        return response()->json(['isValid' => $isValid]);
    }

    public function store( Request $request)
    {
       
        $refunds = new RefundRequest();
        
        $refunds->transaction_id = $request->input('transaction_id');
        $refunds->reason = $request->input('reason');
        $refunds->save();

        return response()->json([
            'testing'=>'test',
            'message' => 'Refund uploaded successfully',
          
        ], 200);
    
        
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'processing_comments' => 'nullable|string'
        ]);

        $refund = RefundRequest::findOrFail($id); // Throws 404 if not found
        $refund->update([
            'status' => $request->status,
            'processing_comments' => $request->processing_comments,
        ]);

        return response()->json(['message' => 'Refund status updated successfully']);
    }
}
