<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Seat;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTicketController extends Controller
{
    public function getTicketSales()
    {
        try {
            $transactions = Transaction::select(
                'transactions.*',
                DB::raw('COUNT(seats.seat_id) as total_seats')
            )
            ->leftJoin('seats', 'seats.ticket_id', '=', 'transactions.transaction_id')
            ->groupBy('transactions.transaction_id')
            ->orderBy('transactions.created_at', 'desc')
            ->get();

            // Calculate summary statistics
            $summary = [
                'total_sales' => $transactions->sum('total_price'),
                'total_tickets' => $transactions->sum('total_seats'),
                'pending_payments' => $transactions->where('payment_status', 'PENDING')->count(),
                'completed_payments' => $transactions->where('payment_status', 'COMPLETED')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ticket sales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTicketStatus(Request $request, $transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            $transaction->update([
                'payment_status' => $request->payment_status,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTicketDetails($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            $seats = Seat::where('ticket_id', $transactionId)->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction' => $transaction,
                    'seats' => $seats
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

    public function getRefundRequests()
    {
        try {
            $refundRequests = RefundRequest::with(['transaction'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $refundRequests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve refund requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processRefund(Request $request, $transactionId)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($transactionId);
            $refundRequest = RefundRequest::where('transaction_id', $transactionId)->first();

            if (!$refundRequest) {
                throw new \Exception('No refund request found for this transaction');
            }

            // Update transaction status
            $transaction->update([
                'status' => 'REFUNDED',
                'payment_status' => 'REFUNDED'
            ]);

            // Update refund request status
            $refundRequest->update([
                'status' => 'APPROVED',
                'processed_by' => $request->admin_id,
                'processed_at' => now(),
                'refund_amount' => $transaction->total_price
            ]);

            // Free up the seats
            Seat::where('ticket_id', $transactionId)
                ->update([
                    'is_booked' => false,
                    'ticket_id' => null
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'data' => [
                    'transaction' => $transaction,
                    'refund_request' => $refundRequest
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectRefund(Request $request, $transactionId)
    {
        try {
            $refundRequest = RefundRequest::where('transaction_id', $transactionId)->first();

            if (!$refundRequest) {
                throw new \Exception('No refund request found for this transaction');
            }

            $refundRequest->update([
                'status' => 'REJECTED',
                'processed_by' => $request->admin_id,
                'processed_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request rejected',
                'data' => $refundRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 