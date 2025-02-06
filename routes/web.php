<?php

use Illuminate\Support\Facades\Route;
use Namu\WireChat\Livewire\Index;
use Namu\WireChat\Livewire\View;

Route::middleware(config('wirechat.routes.middleware'))
    ->prefix(config('wirechat.routes.prefix'))
    ->group(function () {
        Route::get('/', Index::class)->name('chats');
        Route::get('/{conversation_id}', View::class)->middleware('belongsToConversation')->name('chat');
    });
