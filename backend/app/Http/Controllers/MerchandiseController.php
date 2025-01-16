<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\MerchandiseOrder;
use App\Models\MerchandiseOrderItem;
use App\Models\MerchandiseRefund;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class MerchandiseController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            \Log::info('Received order request:', $request->all());

            DB::beginTransaction();

            // Create transaction record
            $transaction = Transaction::create([
                'total_price' => $request->total,
                'transaction_type' => 'MERCHANDISE', // Make sure this is uppercase
                'payment_status' => 'PENDING',
                'payment_method' => 'PENDING',
                'cust_email' => $request->customerEmail,
                'cust_name' => $request->customerName,
                'cust_phone' => $request->customerPhone,
                'status' => 'PENDING'
            ]);

            \Log::info('Created transaction:', $transaction->toArray());

            // Create order items
            foreach ($request->items as $item) {
                MerchandiseOrder::create([
                    'transaction_id' => $transaction->transaction_id,
                    'merchandise_id' => $item['merchandiseId'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $transaction
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating order:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requestRefund(Request $request)
    {
        $request->validate([
            'orderId' => 'required|exists:merchandise_orders,order_id',
            'reason' => 'required|string|min:10'
        ]);

        try {
            // First find the order
            $order = MerchandiseOrder::with('items')->findOrFail($request->orderId);
            
            // Check if refund already exists
            if ($order->refund) {
                throw new \Exception('Refund request already exists for this order');
            }

            // Check if order is eligible for refund (e.g., within 7 days)
            $orderDate = new \DateTime($order->created_at);
            $now = new \DateTime();
            $daysDifference = $now->diff($orderDate)->days;
            
            if ($daysDifference > 7) {
                throw new \Exception('Order is no longer eligible for refund (more than 7 days old)');
            }

            // Calculate total refund amount from order
            $refundAmount = $order->total_amount;

            // Create the refund request with the amount
            $refund = MerchandiseRefund::create([
                'order_id' => $order->order_id,
                'reason' => $request->reason,
                'refund_amount' => $refundAmount,
                'status' => 'PENDING'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request submitted successfully',
                'data' => $refund
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit refund request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processRefund(Request $request, $refundId)
    {
        try {
            $refund = MerchandiseRefund::findOrFail($refundId);
            $order = $refund->order;

            DB::transaction(function () use ($refund, $order, $request) {
                // Update refund status
                $refund->update([
                    'status' => 'APPROVED',
                    'processed_by' => $request->admin_id,
                    'processed_at' => now()
                ]);

                // Update order status
                $order->update([
                    'order_status' => 'REFUNDED',
                    'payment_status' => 'REFUNDED'
                ]);

                // Return items to inventory
                foreach ($order->items as $item) {
                    $merchandise = $item->merchandise;
                    $inventory = $merchandise->sizes_inventory;
                    $inventory[$item->size] += $item->quantity;
                    $merchandise->update(['sizes_inventory' => $inventory]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdminOrders()
    {
        try {
            $orders = MerchandiseOrder::with(['items.merchandise'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdminRefunds()
    {
        try {
            $refunds = MerchandiseRefund::with(['order.items.merchandise'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $refunds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch refunds',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getOrderSummary()
    {
        try {
            $summary = [
                'totalOrders' => MerchandiseOrder::count(),
                'pendingOrders' => MerchandiseOrder::where('order_status', 'PENDING')->count(),
                'totalSales' => MerchandiseOrder::where('payment_status', 'PAID')->sum('total_amount'),
                'pendingRefunds' => MerchandiseRefund::where('status', 'PENDING')->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserOrders()
    {
        try {
            $orders = MerchandiseOrder::with(['items.merchandise'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserRefunds()
    {
        try {
            $refunds = MerchandiseRefund::with(['order'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $refunds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch refunds',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createRefund(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:merchandise_orders,order_id',
            'reason' => 'required|string',
            'refund_amount' => 'required|numeric|min:0'
        ]);

        try {
            $refund = MerchandiseRefund::create([
                'order_id' => $request->order_id,
                'reason' => $request->reason,
                'refund_amount' => $request->refund_amount,
                'status' => 'PENDING'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund request created successfully',
                'data' => $refund
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create refund request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectRefund(Request $request, $id)
    {
        try {
            $refund = MerchandiseRefund::findOrFail($id);
            
            // Check if refund is already processed
            if ($refund->status !== 'PENDING') {
                throw new \Exception('Refund has already been processed');
            }

            DB::beginTransaction();

            // Update refund status to rejected
            $refund->update([
                'status' => 'REJECTED',
                'processed_by' => 1, // Replace with actual admin ID when you have authentication
                'processed_at' => now()
            ]);

            // Update the order status to indicate refund was rejected
            $order = MerchandiseOrder::findOrFail($refund->order_id);
            $order->update([
                'order_status' => 'REFUND_REJECTED'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund rejected successfully',
                'data' => $refund->load('order')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getOrder($id)
    {
        try {
            $order = MerchandiseOrder::with([
                'items.merchandise' => function ($query) {
                    $query->select('merchandise_id', 'name', 'price', 'image_url', 'description');
                }
            ])
            ->select(
                'order_id',
                'customer_name',
                'customer_email',
                'customer_phone',
                'total_amount',
                'payment_status',
                'order_status',
                'created_at'
            )
            ->findOrFail($id);

            // Format the items data
            $formattedItems = $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'merchandise' => [
                        'merchandise_id' => $item->merchandise->merchandise_id,
                        'name' => $item->merchandise->name,
                        'price' => $item->merchandise->price,
                        'image_url' => $item->merchandise->image_url,
                        'description' => $item->merchandise->description
                    ],
                    'size' => $item->size,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->quantity * $item->unit_price
                ];
            });

            $orderDetails = [
                'order_id' => $order->order_id,
                'order_number' => str_pad($order->order_id, 6, '0', STR_PAD_LEFT),
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'total_amount' => $order->total_amount,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'created_at' => $order->created_at,
                'items' => $formattedItems
            ];

            return response()->json([
                'success' => true,
                'data' => $orderDetails
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadReceipt($id)
    {
        try {
            $order = MerchandiseOrder::with(['items.merchandise'])
                ->findOrFail($id);

            $pdf = PDF::loadView('receipts.merchandise', [
                'order' => $order,
                'orderNumber' => str_pad($order->order_id, 6, '0', STR_PAD_LEFT),
                'orderDate' => $order->created_at->format('F d, Y h:i A'),
                'items' => $order->items,
                'totalAmount' => number_format($order->total_amount, 2)
            ]);

            return $pdf->download('receipt-' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT) . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate receipt',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approveOrder($id)
    {
        try {
            $order = MerchandiseOrder::findOrFail($id);

            // Check if order is already approved
            if ($order->order_status === 'COMPLETED') {
                throw new \Exception('Order is already approved');
            }

            DB::beginTransaction();

            // Update order status
            $order->update([
                'order_status' => 'COMPLETED',
                'payment_status' => 'PAID',
                'approved_at' => now()
            ]);

            // You might want to send email notification to customer
            // TODO: Implement email notification

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order approved successfully',
                'data' => $order->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
