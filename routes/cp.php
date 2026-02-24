<?php

use Illuminate\Support\Facades\Route;
use Pixelkode\Iconsets\Http\Controllers\IconsetFieldtypeController;

/*
|--------------------------------------------------------------------------
| Control Panel Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the ServiceProvider and are assigned to the
| "statamic.cp" middleware group.
|
*/

Route::post('/iconset-fieldtype', IconsetFieldtypeController::class)
    ->name('iconset-fieldtype');
