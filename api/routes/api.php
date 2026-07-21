<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\MenuCategoryController;
use App\Http\Controllers\Api\V1\MenuItemController;
use App\Http\Controllers\Api\V1\SpecialtyController;
use App\Http\Controllers\Api\V1\GalleryItemController;
use App\Http\Controllers\Api\V1\ContactMessageController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\PublicController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\CateringRequestController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\SmsLogController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SmsController;
use App\Http\Controllers\Api\V1\SmsSettingsController;
use App\Http\Controllers\Api\V1\NewsletterSubscriberController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\CustomerFeedbackController;

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

Route::prefix('v1')->group(function () {
    
    // Public routes (no authentication required)
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'API is running',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // Public API endpoints (for frontend site)
    Route::prefix('public')->group(function () {
        Route::get('/menu-categories', [PublicController::class, 'menuCategories']);
        Route::get('/menu-items', [PublicController::class, 'menuItems']);
        Route::get('/specialties', [PublicController::class, 'specialties']);
        Route::get('/gallery', [PublicController::class, 'gallery']);
        Route::post('/contact', [PublicController::class, 'contact']);
        Route::post('/orders', [PublicController::class, 'order']);
        Route::get('/orders/{code}', [OrderController::class, 'viewByCode']); // Public order lookup
        Route::get('/settings', [PublicController::class, 'settings']);
        Route::get('/latest-event', [PublicController::class, 'latestEvent']); // Get most recent active event
        Route::get('/active-events', [PublicController::class, 'activeEvents']); // Get all active events
        Route::post('/subscribe', [PublicController::class, 'subscribe']); // Subscribe email or phone number
        Route::post('/feedback', [PublicController::class, 'feedback']);
    });

    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });
    
    // Protected routes (require authentication)
    Route::middleware('auth:api')->group(function () {
        // Auth routes (protected)
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/me', [AuthController::class, 'me']);
        });

        // User abilities endpoint
        Route::get('/me/abilities', [UserController::class, 'abilities']);
        
        // Dashboard stats endpoint
        Route::get('/stats', [StatsController::class, 'index']);
        Route::get('/notifications', [StatsController::class, 'notifications']);
        
        // CMS Routes (protected with permissions via FormRequests)
        Route::post('menu-categories/import', [MenuCategoryController::class, 'import']);
        Route::apiResource('menu-categories', MenuCategoryController::class);
        Route::post('menu-items/import', [MenuItemController::class, 'import']);
        Route::apiResource('menu-items', MenuItemController::class);
        Route::post('specialties/import', [SpecialtyController::class, 'import']);
        Route::apiResource('specialties', SpecialtyController::class);
        Route::apiResource('gallery', GalleryItemController::class);
        Route::apiResource('messages', ContactMessageController::class);
        Route::post('/messages/{message}/read', [ContactMessageController::class, 'markAsRead']);
        Route::get('feedback', [CustomerFeedbackController::class, 'index']);
        Route::get('feedback/{feedback}', [CustomerFeedbackController::class, 'show']);
        Route::post('feedback/{feedback}/read', [CustomerFeedbackController::class, 'markAsRead']);
        Route::delete('feedback/{feedback}', [CustomerFeedbackController::class, 'destroy']);
        Route::apiResource('orders', OrderController::class);
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::get('/orders/export', [OrderController::class, 'export']);
        
        // Public order lookup by code (for customers)
        Route::get('/orders/code/{code}', [OrderController::class, 'viewByCode']);
        Route::get('/settings', [SettingController::class, 'index']);
        Route::get('/settings/{group}', [SettingController::class, 'getGroup']);
        Route::put('/settings', [SettingController::class, 'update']);
        Route::delete('/settings/group/{group}', [SettingController::class, 'deleteGroup']);
        Route::delete('/settings/{group}/{key}', [SettingController::class, 'deleteSetting']);
        Route::apiResource('media', MediaController::class)->parameters(['media' => 'medium']);
        
        // User Management Routes
        Route::post('users/import', [UserController::class, 'import']);
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::post('roles/{id}/permissions', [RoleController::class, 'syncPermissions']);
        Route::apiResource('permissions', PermissionController::class)->only(['index', 'store', 'destroy']);
        
        // Events, Catering, Customers, SMS Routes
        Route::post('events/import', [EventController::class, 'import']);
        Route::apiResource('events', EventController::class);
        Route::apiResource('catering-requests', CateringRequestController::class);
        Route::post('customers/import', [CustomerController::class, 'import']);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('sms-logs', SmsLogController::class);

        // Newsletter
        Route::get('newsletter/campaigns', [NewsletterController::class, 'campaigns']);
        Route::post('newsletter/send', [NewsletterController::class, 'send']);
        Route::apiResource('newsletter-subscribers', NewsletterSubscriberController::class)->only(['index', 'store', 'destroy']);
        
        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile', [ProfileController::class, 'update']);
        Route::delete('/profile/picture', [ProfileController::class, 'deleteProfilePicture']);
        
        // SMS Routes
        Route::prefix('sms')->group(function () {
            Route::post('/send', [SmsController::class, 'send']);
            Route::post('/send-bulk', [SmsController::class, 'sendBulk']);
            Route::get('/status', [SmsController::class, 'status']);
        });
        
        // SMS Settings Routes
        Route::prefix('sms-settings')->group(function () {
            Route::get('/', [SmsSettingsController::class, 'index']);
            Route::put('/', [SmsSettingsController::class, 'update']);
            Route::post('/test', [SmsSettingsController::class, 'test']);
        });
        
        // Test endpoint
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->user()
            ]);
        });
    });
    
});
