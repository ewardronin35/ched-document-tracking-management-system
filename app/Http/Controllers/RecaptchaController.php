<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    public function verify(Request $request)
    {
        $secretKey = env('RECAPTCHA_SECRET_KEY'); // Get your secret key from .env
        $token = $request->input('token');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            if ($result['success'] && $result['score'] >= 0.5) { // Adjust the score threshold as needed
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }
}