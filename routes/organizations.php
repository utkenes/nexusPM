<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('organizations', OrganizationController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('organizations/{organization}/switch', [OrganizationController::class, 'switch'])->name('organizations.switch');
});
