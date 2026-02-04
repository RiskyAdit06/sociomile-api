<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ConversationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ===== AUTH =====
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh-token', [AuthController::class, 'refreshToken']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');


// ===== PROTECTED ROUTES =====
Route::middleware(['auth.jwt'])->group(function () {

    // =======================
    // Ticket Management
    // =======================

    // Admin & Agent → list ticket
    Route::get('tickets', [TicketController::class, 'index'])
        ->middleware('role:admin,agent');

    // Customer → create ticket
    Route::post('tickets', [TicketController::class, 'store'])
        ->middleware('role:admin');

    // Admin & Agent → update status
    Route::patch('tickets/{id}/status', [TicketController::class, 'updateStatus'])
        ->middleware('role:admin,agent');

    // Admin only → assign ticket
    Route::patch('tickets/{id}/assign', [TicketController::class, 'assign'])
        ->middleware('role:admin');


    // =======================
    // Conversation / Chat
    // =======================

    // Admin, Agent, Customer → lihat conversation
    Route::get('tickets/{ticket_id}/conversations', [ConversationController::class, 'index'])
        ->middleware('role:admin,agent');

    // Admin, Agent, Customer → kirim message
    Route::post('tickets/{ticket_id}/conversations', [ConversationController::class, 'store'])
        ->middleware('role:admin,agent');
});