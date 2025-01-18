<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\MerchandiseOrder;
use App\Models\MerchandiseOrderItem;
use App\Models\MerchandiseRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Mail;

class MerchandiseController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            \Log::info('Creating merchandise order:', $request->all());
            
            DB::beginTransaction();

            // Create the main order first
            $order = MerchandiseOrder::create([
                'merchandise_id' => $request->items[0]['merchandiseId'], // First item's merchandise
                'customer_name' => $request->customerName,
                'customer_email' => $request->customerEmail,
                'customer_phone' => $request->customerPhone,
                'size' => $request->items[0]['size'],
                'quantity' => $request->items[0]['quantity'],
                'unit_price' => Merchandise::find($request->items[0]['merchandiseId'])->price,
                'total_amount' => 0, // Will update after calculating total
                'status' => 'PENDING'
            ]);

            $totalAmount = 0;

            // Create order items and calculate total
            foreach ($request->items as $item) {
                $merchandise = Merchandise::findOrFail($item['merchandiseId']);
                $itemTotal = $merchandise->price * $item['quantity'];
                $totalAmount += $itemTotal;

                MerchandiseOrderItem::create([
                    'order_id' => $order->id,
                    'merchandise_id' => $item['merchandiseId'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $merchandise->price,
                    'total_amount' => $itemTotal
                ]);
            }

            // Update the main order with total amount
            $order->total_amount = $totalAmount;
            $order->save();

            DB::commit();
            
            // Send confirmation email
            $this->sendOrderConfirmationEmail($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order->load('merchandise')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating order:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function sendOrderConfirmationEmail($order)
    {
        try {
            $items = $order->orderItems->map(function($item) {
                return [
                    'name' => $item->merchandise->name,
                    'size' => $item->size,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                    'total' => $item->total_amount
                ];
            });

            Mail::send('emails.merchandise-order', [
                'order' => $order,
                'items' => $items,
                'customerName' => $order->customer_name,
                'orderNumber' => $order->id,
                'totalAmount' => $order->total_amount
            ], function($message) use ($order) {
                $message->to($order->customer_email)
                       ->subject('MCB Merchandise Order Confirmation #' . $order->id);
            });

            \Log::info('Order confirmation email sent:', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email:', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function requestRefund(Request $request)
    {
        $request->validate([
            'orderId' => 'required|exists:merchandise_orders,id',
            'reason' => 'required|string|min:10'
        ]);

        try {
            DB::beginTransaction();

            $order = MerchandiseOrder::findOrFail($request->orderId);
            
            // Validate order status
            if ($order->status !== 'COMPLETED') {
                throw new \Exception('Only completed orders can be refunded');
            }

            // Check if refund already exists
            if ($order->refunds()->exists()) {
                throw new \Exception('A refund request already exists for this order');
            }

            // Check refund eligibility (7 days)
            if (now()->diffInDays($order->created_at) > 7) {
                throw new \Exception('Order is no longer eligible for refund (more than 7 days old)');
            }

            // Create refund request
            $refund = MerchandiseRefund::create([
                'order_id' => $order->id,
                'reason' => $request->reason,
                'refund_amount' => $order->total_amount,
                'status' => 'PENDING'
            ]);

            // Update order status
            $order->update(['status' => 'REFUND_REQUESTED']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund request submitted successfully',
                'data' => $refund
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function processRefund(Request $request, $id)
    {
        try {
            $request->validate([
                'action' => 'required|in:APPROVE,REJECT',
                'adminId' => 'required|numeric'
            ]);

            $refund = MerchandiseRefund::findOrFail($id);
            
            if ($request->action === 'APPROVE') {
                $refund->status = 'COMPLETED';
                $refund->order->status = 'REFUNDED';
            } else {
                $refund->status = 'REJECTED';
                $refund->order->status = 'COMPLETED';
            }

            $refund->processed_by = $request->adminId;
            $refund->processed_at = now();
            $refund->save();
            $refund->order->save();

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

    public function getOrder($id)
    {
        try {
            \Log::info('Fetching order details', ['id' => $id]);

            $order = MerchandiseOrder::with(['merchandise'])
                ->findOrFail($id);

            $orderData = [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'merchandise' => [
                    'id' => $order->merchandise->id,
                    'name' => $order->merchandise->name,
                    'price' => $order->merchandise->price,
                ],
                'size' => $order->size,
                'quantity' => $order->quantity,
                'unit_price' => $order->unit_price,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ];

            \Log::info('Order fetched successfully', ['order' => $orderData]);

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => $orderData,
                    'items' => $order->orderItems,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching order:', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getAllOrders()
    {
        try {
            $orders = MerchandiseOrder::with('merchandise')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'customer_name' => $order->customer_name ?? '',
                        'customer_email' => $order->customer_email ?? '',
                        'customer_phone' => $order->customer_phone ?? '',
                        'merchandise' => $order->merchandise ? [
                            'id' => $order->merchandise->id,
                            'name' => $order->merchandise->name
                        ] : null,
                        'size' => $order->size ?? '',
                        'quantity' => $order->quantity ?? 0,
                        'unit_price' => $order->unit_price ?? 0,
                        'total_amount' => $order->total_amount ?? 0,
                        'status' => $order->status ?? 'PENDING',
                        'created_at' => $order->created_at ? $order->created_at->toDateTimeString() : null,
                        'updated_at' => $order->updated_at ? $order->updated_at->toDateTimeString() : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'orders' => $orders
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching orders:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdminOrders()
    {
        try {
            $orders = MerchandiseOrder::with(['items.merchandise', 'refund'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders'
            ], 500);
        }
    }

    public function getAdminRefunds()
    {
        try {
            $refunds = MerchandiseRefund::with(['order.merchandise'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($refund) {
                    return [
                        'id' => $refund->id,
                        'order_id' => $refund->order_id,
                        'order' => [
                            'customer_name' => $refund->order->customer_name,
                            'customer_email' => $refund->order->customer_email,
                            'merchandise' => [
                                'name' => $refund->order->merchandise->name,
                                'price' => $refund->order->merchandise->price
                            ],
                            'size' => $refund->order->size,
                            'quantity' => $refund->order->quantity,
                            'total_amount' => $refund->order->total_amount
                        ],
                        'refund_amount' => $refund->refund_amount,
                        'reason' => $refund->reason,
                        'status' => $refund->status,
                        'processed_by' => $refund->processed_by,
                        'processed_at' => $refund->processed_at,
                        'created_at' => $refund->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $refund->updated_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $refunds
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching refunds:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
            $totalOrders = MerchandiseOrder::count();
            $pendingOrders = MerchandiseOrder::where('status', 'PENDING')->count();
            $totalSales = MerchandiseOrder::where('status', 'COMPLETED')->sum('total_amount');
            $pendingRefunds = MerchandiseOrder::where('status', 'REFUND_PENDING')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalOrders' => $totalOrders,
                    'pendingOrders' => $pendingOrders,
                    'totalSales' => $totalSales,
                    'pendingRefunds' => $pendingRefunds
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllRefunds()
    {
        try {
            $refunds = MerchandiseRefund::with(['order'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($refund) {
                    return [
                        'id' => $refund->id,
                        'order_id' => $refund->order_id,
                        'order' => $refund->order ? [
                            'customer_name' => $refund->order->customer_name,
                            'customer_email' => $refund->order->customer_email
                        ] : null,
                        'refund_amount' => $refund->refund_amount,
                        'reason' => $refund->reason,
                        'status' => $refund->status ?? 'PENDING',
                        'created_at' => $refund->created_at ? $refund->created_at->toDateTimeString() : null,
                        'updated_at' => $refund->updated_at ? $refund->updated_at->toDateTimeString() : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'refunds' => $refunds
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching refunds:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch refunds',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processOrder($id)
    {
        try {
            $order = MerchandiseOrder::findOrFail($id);
            $order->status = 'COMPLETED';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
