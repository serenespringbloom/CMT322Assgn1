<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\RefundRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMerchandiseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/images', [ImageUploadController::class, 'listImages']);
Route::delete('/images/{id}', [ImageUploadController::class, 'deleteImage']);
Route::put('/images/{id}', [ImageUploadController::class, 'updateImage']);

Route::post('/upload-image', [ImageUploadController::class, 'uploadImage']);

// Default user route for authenticated API requests
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Example test route to verify API functionality
Route::get('/test', function () {
    // Fetch data from your table (replace 'your_table_name' with your actual table name)
    return DB::table('users')->get();
});

// Add your additional API routes below
Route::get('/example', function () {
    return response()->json(['message' => 'This is an example route']);
});
Route::get("/test-me", function () {
    return 'Hello from Laravel!';
});

Route::post('/store-item', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $item = Item::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Item stored successfully!',
        'data' => $item,
    ], 201);
});



//Refund API
    Route::get('/admin/refunds', [RefundRequestController::class, 'index']);
    Route::put('/admin/refunds/{refund_id}', [RefundRequestController::class, 'update']);
    Route::post('/user/refunds', [RefundRequestController::class, 'store']);
    Route::get('/validate-pid/{pid}', [RefundRequestController::class, 'validatePid']);

//Feedback API



//Billing API

Route::get('/admin/billing', [BillingController::class, 'index']);
Route::get('/admin/billing2', [BillingController::class, 'index2']);

//Merchandise API

Route::get('/admin/merchandise1', [MerchandiseController::class, 'index']);
Route::get('/admin/merchandise2', [MerchandiseController::class, 'index2']);

//seatr API
Route::prefix('seats')->group(function () {
    Route::get('/', [SeatController::class, 'index']);
    Route::post('/verify', [SeatController::class, 'verifySeatAvailability']);
    Route::post('/book', [SeatController::class, 'createBooking']);
});

Route::get('/ticket/{transactionId}', [TicketController::class, 'getTicketDetails']);




    // Other routes...

    Route::prefix('admin')->group(function () {
        Route::get('/tickets', [AdminTicketController::class, 'getTicketSales']);
        Route::get('/tickets/{transactionId}', [AdminTicketController::class, 'getTicketDetails']);
        Route::put('/tickets/{transactionId}', [AdminTicketController::class, 'updateTicketStatus']);
        
        // Refund routes
        Route::get('/refunds', [AdminTicketController::class, 'getRefundRequests']);
        Route::post('/refunds/{transactionId}/process', [AdminTicketController::class, 'processRefund']);
        Route::post('/refunds/{transactionId}/reject', [AdminTicketController::class, 'rejectRefund']);
    });

// Merchandise routes
Route::prefix('merchandise')->group(function () {
    Route::post('/orders', [MerchandiseController::class, 'createOrder']);
    Route::post('/refunds', [MerchandiseController::class, 'requestRefund']);
    Route::post('/refunds/{refundId}/process', [MerchandiseController::class, 'processRefund']);
});

Route::prefix('admin/merchandise')->group(function () {
    Route::get('/orders', [MerchandiseController::class, 'getAdminOrders']);
    Route::get('/refunds', [MerchandiseController::class, 'getAdminRefunds']);
    Route::get('/summary', [MerchandiseController::class, 'getOrderSummary']);
    Route::post('/refunds/{id}/process', [MerchandiseController::class, 'processRefund']);
    Route::post('/refunds/{id}/reject', [MerchandiseController::class, 'rejectRefund']);

});

Route::prefix('user/merchandise')->group(function () {
    Route::get('/orders', [MerchandiseController::class, 'getUserOrders']);
    Route::get('/refunds', [MerchandiseController::class, 'getUserRefunds']);
    Route::post('/refunds', [MerchandiseController::class, 'createRefund']);
});

Route::get('/merchandise/orders/{id}', [MerchandiseController::class, 'getOrder']);
Route::get('/merchandise/orders/{id}/receipt', [MerchandiseController::class, 'downloadReceipt']);

Route::post('/admin/merchandise/orders/{id}/approve', [MerchandiseController::class, 'approveOrder']);

// Feedback routes
// Feedback routes
Route::prefix('feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index']);
    Route::post('/', [FeedbackController::class, 'store']); // Add this line to allow POST requests

});
// Admin feedback routes
Route::prefix('admin/feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index']);

    Route::delete('/{id}', [FeedbackController::class, 'destroy']);
});

Route::get('/admin/summary', [AdminController::class, 'getSummary']);

