<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function tweet()
    {
        try {
            $user = Socialite::driver('twitter')->user();

            // Publicar el tweet
            $response = $user->post('statuses/update', ['status' => '¡Hola desde Laravel Lumen!']);

            return response()->json(['message' => 'Tweet publicado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}