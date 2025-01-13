<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\RefundRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TransactionController;
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
Route::post('/hello/feedback', [FeedbackController::class, 'store']);
Route::get('/admin/feedback', [FeedbackController::class, 'index']);

//Ticket API

Route::post('/purchase', [TransactionController::class, 'store']);
Route::get('/seats', [SeatController::class, 'index']);
Route::get('/bookings', [TransactionController::class, 'store']);

