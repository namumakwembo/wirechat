<?php

use App\Livewire\Test;
use Illuminate\Support\Facades\Route;
use Namu\WireChat\Livewire\Chat\Chat;
use Namu\WireChat\Livewire\Chat\Chats;
use Namu\WireChat\Livewire\Index;
use Namu\WireChat\Livewire\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Needed for testing purposes
Route::get('/', function () {
    return 'welcome';
});

//Needed for testing purposes
Route::middleware('guest')->get('/login', function () {
    return 'login page';
})->name('login');

Route::middleware(config('wirechat.routes.middleware'))
    ->prefix(config('wirechat.routes.prefix'))
    ->group(function () {
        Route::get('/', Index::class)->name('chats');
        Route::get('/{conversation_id}', View::class)->name('chat');
    });
