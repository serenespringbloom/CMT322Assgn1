<?php

namespace App\Http\Controllers;

use App\Models\MerchandiseOrderItem;
use App\Models\Transaction;
use App\Models\Feedback;
use App\Models\MerchandiseOrder;
use App\Models\Seat;
use Illuminate\Http\Request;

class AdminController
{
    public function getSummary()
    {
        try {
            // Debug: Get all transactions to verify data
            $allTransactions = Transaction::all();
            \Log::info('All Transactions:', $allTransactions->toArray());

            // Get ticket sales with less restrictive conditions
            $ticketSales = Transaction::where('transaction_type', 'TICKET')
                ->whereIn('payment_status', ['COMPLETED', 'PENDING'])
                ->sum('total_price');
            
            \Log::info('Raw Ticket Sales Query:', [
                'sql' => Transaction::where('transaction_type', 'TICKET')
                    ->whereIn('payment_status', ['COMPLETED', 'PENDING'])
                    ->toSql(),
                'bindings' => Transaction::where('transaction_type', 'TICKET')
                    ->whereIn('payment_status', ['COMPLETED', 'PENDING'])
                    ->getBindings()
            ]);
            
            \Log::info('Ticket Sales Amount:', ['amount' => $ticketSales]);

            // Get ticket count
            $ticketCount = Seat::where('is_booked', '1')
                ->count();

            // Get merchandise sales
            $merchandiseSales = MerchandiseOrder::where('status', ['COMPLETED'])
                ->sum('total_amount');

            $merchandiseCount = MerchandiseOrderItem::all()->sum('quantity');
              

            // Get feedback stats
            $feedbacks = Feedback::orderBy('created_at', 'desc')->get();
            $newFeedbackCount = $feedbacks->count();
            $averageRating = $feedbacks->avg('rating') ?? 0;

            // Get recent activities with less restrictive conditions
            $recentActivities = Transaction::orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->transaction_id,
                        'date' => $transaction->created_at,
                        'type' => $transaction->transaction_type,
                        'details' => "Transaction by {$transaction->cust_email}",
                        'amount' => floatval($transaction->total_price),
                        'status' => $transaction->payment_status
                    ];
                });

                $merchActivities = MerchandiseOrder::with('orderItems')->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($merchOrder) {
                    return [
                        'id' => $merchOrder->id,
                        'date' => $merchOrder->created_at,
                        'phone' => $merchOrder->customer_phone,
                        'details' => "Transaction by {$merchOrder->customer_email}",
                        'amount' => floatval($merchOrder->total_amount),
                        'status' => $merchOrder->status
                    ];
                });


            $response = [
                'success' => true,
                'ticketSales' => floatval($ticketSales),
                'merchandiseSales' => floatval($merchandiseSales),
                'ticketCount' => intval($ticketCount),
                'merchandiseCount' => intval($merchandiseCount),
                'newFeedbackCount' => intval($newFeedbackCount),
                'averageRating' => round(floatval($averageRating), 1),
                'recentActivities' => $recentActivities,
                'merchActivities' => $merchActivities,
                'debug' => [
                    'totalTransactions' => $allTransactions->count(),
                    'rawTicketSales' => $ticketSales,
                    'rawMerchandiseSales' => $merchandiseSales
                ]

            ];

            \Log::info('Final Response:', $response);

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Error in getSummary:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 