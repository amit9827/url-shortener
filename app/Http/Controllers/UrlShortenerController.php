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
