<?php
// Archivo: app/Http/Controllers/TwitterController.php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TwitterController extends Controller
{
    public function redirectToTwitter()
    {
        $connection = new TwitterOAuth(env('TWITTER_API_KEY'), env('TWITTER_API_SECRET_KEY'));

        $request_token = $connection->oauth('oauth/request_token', [
            'oauth_callback' => env('TWITTER_CALLBACK_URL')
        ]);

        // Guardar el token temporal en la sesión
        Session::put('oauth_token', $request_token['oauth_token']);
        Session::put('oauth_token_secret', $request_token['oauth_token_secret']);

        // Redirigir al usuario a Twitter para autenticar
        $url = $connection->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]);
        return redirect($url);
    }

    public function handleTwitterCallback(Request $request)
    {
        $request_token = [];
        $request_token['oauth_token'] = Session::get('oauth_token');
        $request_token['oauth_token_secret'] = Session::get('oauth_token_secret');

        // Verificar que la respuesta de Twitter tenga los tokens correctos
        if ($request->oauth_token !== $request_token['oauth_token']) {
            return response()->json(['error' => 'Error de autenticación'], 401);
        }

        // Obtener el token de acceso
        $connection = new TwitterOAuth(
            env('TWITTER_API_KEY'),
            env('TWITTER_API_SECRET_KEY'),
            $request_token['oauth_token'],
            $request_token['oauth_token_secret']
        );

        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request->oauth_verifier]);

        // Guardar el token de acceso en la sesión
        Session::put('access_token', $access_token);

        return response()->json(['message' => 'Autenticación exitosa']);
    }

    public function postTweet(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Session::has('access_token')) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $access_token = Session::get('access_token');

        // Usar el token de acceso para hacer solicitudes autenticadas
        $connection = new TwitterOAuth(
            env('TWITTER_API_KEY'),
            env('TWITTER_API_SECRET_KEY'),
            $access_token['oauth_token'],
            $access_token['oauth_token_secret']
        );

        // Publicar un tweet en formato application/x-www-form-urlencoded
        $status = $connection->post("statuses/update", [
            "status" => $request->input('tweet')
        ], true); // Enviar datos como application/x-www-form-urlencoded

        if ($connection->getLastHttpCode() == 200) {
            return response()->json(['message' => 'Tweet publicado con éxito']);
        } else {
            return response()->json(['error' => 'Error al publicar el tweet'], 500);
        }
    }
}
