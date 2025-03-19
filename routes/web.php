<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

// Route to encode a URL (shorten it)
Route::get('/encode', [UrlShortenerController::class, 'encode']);

// Route to decode a URL (retrieve the original URL using the short code)
Route::get('/decode/{shortCode}', [UrlShortenerController::class, 'decode']);
