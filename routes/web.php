<?php


use BinomeWay\NovaContactTool\Http\Controllers\GmailAuthController;
use BinomeWay\NovaContactTool\Http\Controllers\UnsubscribeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {

    // Newsletter
    Route::get('/newsletter/unsubscribe/{subscriber}', UnsubscribeController::class)->name('nova-contact-tool.unsubscribe');

    if (config('nova-contact-tool.enable_gmail_api')) {
        Route::get('/oauth/gmail', [GmailAuthController::class, 'login'])->name('gmail.login');
        Route::get('/oauth/gmail/callback', [GmailAuthController::class, 'callback']);
        Route::get('/oauth/gmail/logout', [GmailAuthController::class, 'logout'])->name('gmail.logout');
    }

});
