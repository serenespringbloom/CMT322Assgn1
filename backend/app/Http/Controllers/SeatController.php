<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SeatController extends Controller
{
    // Get all seats and their status
    public function index(Request $request)
    {
        try {
            $seats = Seat::where('event_id', $request->event_id)
                        ->where('enabled_flag', true)
                        ->get();
            
            return response()->json([
                'success' => true,
                'data' => $seats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch seats'
            ], 500);
        }
    }

    // Verify seat availability
    public function verifySeatAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seats' => 'required|array',
            'event_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $unavailableSeats = Seat::where('event_id', $request->event_id)
                                  ->whereIn('seat_id', $request->seats)
                                  ->where('is_booked', true)
                                  ->pluck('seat_id');

            return response()->json([
                'available' => $unavailableSeats->isEmpty(),
                'unavailableSeats' => $unavailableSeats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify seats'
            ], 500);
        }
    }

    // Create booking
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required|string',
            'selectedSeats' => 'required|array',
            'studentTickets' => 'required|integer',
            'publicTickets' => 'required|integer',
            'totalAmount' => 'required|numeric',
            'bankName' => 'required|string',
            'event_id' => 'required|integer',
            'customerName' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create transaction record
            $transaction = Transaction::create([
                'total_price' => $request->totalAmount,
                'transaction_type' => 'TICKET',
                'payment_status' => 'PENDING',
                'payment_method' => 'FPX',
                'cust_email' => $request->email,
                'cust_name' => $request->customerName,
                'cust_phone' => $request->phone,
                'status' => 'PENDING'
            ]);

            // Update seats
            foreach ($request->selectedSeats as $seatId) {
                $seat = Seat::where('seat_id', $seatId)
                          ->where('event_id', $request->event_id)
                          ->first();
                
                if (!$seat) {
                    throw new \Exception("Seat not found: {$seatId}");
                }

                if ($seat->is_booked) {
                    throw new \Exception("Seat already booked: {$seatId}");
                }

                // Update using where clause for both seat_id and event_id
                Seat::where('seat_id', $seatId)
                    ->where('event_id', $request->event_id)
                    ->update([
                        'is_booked' => true,
                        'ticket_id' => $transaction->transaction_id
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->transaction_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Booking failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 