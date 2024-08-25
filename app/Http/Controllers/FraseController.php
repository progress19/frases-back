<?php

namespace App\Http\Controllers;

use App\Models\Frase;
use Illuminate\Http\JsonResponse;

class FraseController extends Controller
{
    public function aleatoria(): JsonResponse
    {

        $frase = Frase::inRandomOrder()->first();
        
        return response()->json([
            'frase' => $frase ? $frase->frase : 'No hay frases disponibles en este momento.',
        ]);
        
    }
}
