<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\TwitterController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/frase-aleatoria', 'FraseController@aleatoria');

/*
$router->get('/login/twitter', [TwitterController::class, 'redirectToTwitter']);
$router->get('/callback', [TwitterController::class, 'handleTwitterCallback']);
$router->post('/tweet', [TwitterController::class, 'postTweet'])->name('twitter.tweet');
*/

// Archivo: routes/web.php

$router->get('/login/twitter', ['uses' => 'TwitterController@redirectToTwitter']);
$router->get('/callback', ['uses' => 'TwitterController@handleTwitterCallback']);
$router->post('/tweet', ['uses' => 'TwitterController@postTweet']);
