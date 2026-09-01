<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictVendeur
{
    /** @var list<string> */
    private const ALLOWED = [
        'dashboard',
        'stock/categorie-produit',
        'stock/etat-produit',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (strtolower((string) session('libautoent_statut', '')) !== 'vendeur') {
            return $next($request);
        }

        $path = trim($request->path(), '/');

        if ($path === '' || str_starts_with($path, 'api/')) {
            return $next($request);
        }

        if (in_array($path, self::ALLOWED, true)) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
