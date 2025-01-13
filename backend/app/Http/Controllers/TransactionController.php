<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Transaction;

class TransactionController extends Controller
{


    public function index()
    {
        return Transaction::with('seats')->get(); // Include related seats if needed
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,seat_id',
            'ticket_category' => 'required|in:student,public'  // Add this validation
        ]);
    
        try {
          
    
            $seats = Seat::whereIn('seat_id', $request->seat_ids)
                        ->where('is_booked', false)
                        ->get();
    
            if ($seats->count() !== count($request->seat_ids)) {
                throw new \Exception('Some seats are no longer available');
            }
    
            // Calculate total amount based on ticket category
            $pricePerTicket = $request->ticket_category === 'student' ? 16 : 20;
            $totalAmount = $seats->count() * $pricePerTicket;
    
            $transaction = Transaction::create([
                'cust_name' => $request->customer_name,
                'cust_email' => $request->email,
                'cust_phone' => $request->phone,
                'ticket_category' => $request->ticket_category,
                'total_price' => $totalAmount,
                'status' => 'completed',
                
            ]);
                   // Update seats
                   foreach ($seats as $seat) {
                    $seat->is_booked = true;
                    $seat->transaction_id = $transaction->transaction_id;
                    $seat->save();
                }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Booking successful',
                'booking' => $transaction->load('seats')
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }


    public function listPurchasedSeats()
{
    // Retrieve all purchased seats with their associated booking and buyer details
    $bookedSeats = Seat::where('status', 'booked')
        ->with('bookings') // Load bookings associated with each seat
        ->get();

        return response()->json($bookedSeats);
}

public function refund($bookingId)
{
    $booking = Transaction::findOrFail($bookingId);

    if ($booking->refunded_at=='done') {
        return response()->json(['error' => 'Refund request has been done..'], 400);
    }

    // Mark booking as refunded
    $booking->update(['refunded_at' =>'done']);

    // Release associated seats
    foreach ($booking->seats as $seat) {
        $seat->update(['is_booked' => 0]);
    }

    return response()->json(['message' => 'Booking successfully refunded.']);
}
}