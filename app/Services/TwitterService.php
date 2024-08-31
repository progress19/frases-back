<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Frase;

class TwitterService
{
    protected $twitter;

    public function __construct()
    {
        $this->twitter = new TwitterOAuth(
            env('TWITTER_CONSUMER_KEY'),
            env('TWITTER_CONSUMER_SECRET'),
            env('TWITTER_ACCESS_TOKEN'),
            env('TWITTER_ACCESS_TOKEN_SECRET')
        );
    }

    public function postRandomFrase()
    {
        // Obtener una frase aleatoria de la base de datos
        $frase = Frase::inRandomOrder()->first();
    
        if (!$frase) {
            throw new \Exception("No se encontraron frases en la base de datos.");
        }
    
        // Intenta postear la frase en X (Twitter)
        $response = $this->twitter->post("statuses/update", ["status" => $frase->texto]);
    
        // Obtener el código de estado HTTP y el cuerpo de la respuesta
        $httpCode = $this->twitter->getLastHttpCode();
        $responseBody = $this->twitter->getLastBody();
    
        // Verificar el estado de la respuesta
        if ($httpCode != 200) {
            // Si hay un error, lanza una excepción con detalles
            throw new \Exception("Error al postear en X. Código HTTP: $httpCode. Detalles: " . json_encode($responseBody));
        }
    
        // Si todo sale bien, devuelve la respuesta
        return $response;
    }
    
    
    
}
