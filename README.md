Here's the complete instruction to set up and use the URL Shortener service:

 
# URL Shortener Service

This is a simple URL shortener service built using Laravel. It allows you to encode and decode URLs via API endpoints. The service temporarily stores URLs using Laravel's caching system, but can be extended to use a database if necessary.

## Installation Instructions

Follow the steps below to get the service up and running:

### Step 1: Install Laravel

Make sure you have PHP and Composer installed on your system. Then, follow the instructions to install Laravel:
 
composer create-project --prefer-dist laravel/laravel url-shortener
 

### Step 2: Configure Environment

Navigate to the project folder:
 
cd url-shortener
 

Set up your environment variables in the `.env` file, setup the CACHE_STORE to use file
 CACHE_STORE=file


### Step 3: Install Dependencies

Run the following command to install all required dependencies:

 
composer install
 

### Step 4: Create the Controller

Run the following Artisan command to create a controller:

 
php artisan make:controller UrlShortenerController
 

This will generate a controller at `app/Http/Controllers/UrlShortenerController.php`. Open this file and add the following logic for encoding and decoding the URLs.

#### Modify `UrlShortenerController.php`

 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UrlShortenerController extends Controller
{
    // Encode URL - Shorten the given URL
    public function encode(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $url = $validated['url'];
        $shortCode = Str::random(6); // Generate a random 6-character code

        // Store the mapping in cache
        Cache::put($shortCode, $url, now()->addMinutes(60)); // Cache the URL for 60 minutes

        return response()->json([
            'shortened_url' => url("/decode/{$shortCode}")
        ]);
    }

    // Decode URL - Retrieve the original URL from the short code
    public function decode($shortCode)
    {
        $url = Cache::get($shortCode);

        if (!$url) {
            return response()->json([
                'error' => 'URL not found or expired.'
            ], 404);
        }

        return response()->json([
            'original_url' => $url
        ]);
    }
}
 

### Step 5: Modify the Routes

Now, modify the `routes/web.php` file to define the routes for encoding and decoding the URLs.

#### Modify `routes/web.php`

 
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

// Route to encode a URL (shorten it)
Route::get('/encode', [UrlShortenerController::class, 'encode']);

// Route to decode a URL (retrieve the original URL using the short code)
Route::get('/decode/{shortCode}', [UrlShortenerController::class, 'decode']);
 

### Step 6: Set Up the Development Server

Run Laravel’s built-in development server:
 
php artisan serve
 

This will start the server at `http://127.0.0.1:8000`.

### Step 7: Test the API Endpoints

You can now test the two primary API endpoints for encoding and decoding URLs.

#### Encode a URL (shorten it)

To shorten a URL, use the following `GET` request:

http://127.0.0.1:8000/encode?url=https://example.com 


This will return a JSON response like:

 
{
    "shortened_url": "http://127.0.0.1:8000/decode/abc123"
}
 

#### Decode a URL (retrieve the original URL)

To get the original URL, use the `GET` request with the short code:
 
http://127.0.0.1:8000/decode/abc123
 

This will return a JSON response like:

 
{
    "original_url": "https://example.com"
}
 

### Step 8: Customize (Optional)

- **Database Integration**: The service uses Laravel's cache for temporary URL storage by default. If you prefer to use a database, you can modify the cache configuration in `config/cache.php` to use a database connection or set up a separate table for URL mappings.

- **Caching System**: If you want to change the cache driver or expiration time, you can adjust the settings in the `.env` file and `config/cache.php`.

### Notes
This is a basic URL shortener service and can be further extended by adding more features such as rate-limiting, analytics, and user authentication for personalized URLs. 
