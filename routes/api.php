<?php

use App\Http\Controllers\AgentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CallController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/calls', [CallController::class, 'index']);
Route::post('/calls', [CallController::class, 'store']);
Route::get('/tickets', [TicketController::class, 'index']);
Route::post('/tickets', [TicketController::class, 'store']);
Route::put('/tickets/{id}', [TicketController::class, 'update']);
Route::post('/tickets/{id}/comment', [TicketController::class, 'addComment']);
Route::get('/agents', [AgentController::class, 'index']);
Route::get('/tickets/reports', [TicketController::class, 'getReports']);
Route::get('/test', function () {
    $ticket = App\Models\Ticket::first();
    Mail::to('hilaliyounes.dev@gmail.com')->send(new App\Mail\TicketCreatedmail($ticket));
    return 'Test email sent!';
});
