<?php

namespace App\Http\Middleware\Zapi;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockMessageGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //se os dados vierem de um grupo não fazer nada;
        $dados = $request->all();
        if ($dados['isGroup']) {
            return response()->json(["message" => "Grupos não podem se comunicar com a api e-greja"], 422);
        }

        return $next($request);
    }
}
