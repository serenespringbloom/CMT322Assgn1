<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Seat;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTicketDetails($transactionId)
    {
        try {
            // Get transaction details
            $transaction = Transaction::findOrFail($transactionId);
            
            // Get booked seats for this transaction
            $seats = Seat::where('ticket_id', $transactionId)->get();
            
            // Format seat numbers into sections (left, center, right)
            $seatsBySection = [];
            foreach ($seats as $seat) {
                $seatNumber = (int)substr($seat->seat_id, 1);
                if ($seatNumber <= 8) {
                    $seatsBySection['left'][] = $seat->seat_id;
                } elseif ($seatNumber <= 16) {
                    $seatsBySection['center'][] = $seat->seat_id;
                } else {
                    $seatsBySection['right'][] = $seat->seat_id;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction' => $transaction,
                    'seats' => $seatsBySection,
                    'ticketId' => sprintf('TIX%06d', $transactionId)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ticket details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 