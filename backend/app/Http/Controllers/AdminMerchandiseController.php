<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminMerchandiseController
{
    public function getMerchandiseSummary()
    {
        try {
            // Debug: Get all merchandise transactions
            $allTransactions = Transaction::where('transaction_type', 'MERCHANDISE')->get();
            \Log::info('All Merchandise Transactions:', $allTransactions->toArray());

            // Less restrictive queries
            $totalOrders = Transaction::where('transaction_type', 'MERCHANDISE')->count();
            \Log::info('Total Orders:', ['count' => $totalOrders]);

            $pendingOrders = Transaction::where('transaction_type', 'MERCHANDISE')
                ->whereIn('status', ['PENDING', 'pending'])
                ->count();
            \Log::info('Pending Orders:', ['count' => $pendingOrders]);

            $totalSales = Transaction::where('transaction_type', 'MERCHANDISE')
                ->whereIn('payment_status', ['COMPLETED', 'completed'])
                ->sum('total_price');
            \Log::info('Total Sales:', ['amount' => $totalSales]);

            $pendingRefunds = Transaction::where('transaction_type', 'MERCHANDISE')
                ->whereIn('status', ['REFUND_PENDING', 'refund_pending'])
                ->count();
            \Log::info('Pending Refunds:', ['count' => $pendingRefunds]);

            // Raw SQL debug
            $query = Transaction::where('transaction_type', 'MERCHANDISE')
                ->whereIn('payment_status', ['COMPLETED', 'completed'])
                ->toSql();
            \Log::info('Raw SQL Query:', ['query' => $query]);

            $summary = [
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'totalSales' => $totalSales,
                'pendingRefunds' => $pendingRefunds,
                'debug' => [
                    'transactionCount' => $allTransactions->count(),
                    'rawQuery' => $query
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getMerchandiseSummary:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch merchandise summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 