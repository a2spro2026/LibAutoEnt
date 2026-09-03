<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictVendeur
{
    /** @var array<string, string> */
    private const PATH_PERMISSION = [
        'dashboard' => 'dashboard.view',
        'stock/categorie-produit' => 'stock.view',
        'stock/etat-produit' => 'stock.view',
        'ventes/reglement-vente' => 'ventes.view',
        'ventes/balance-vente' => 'ventes.view',
        'ventes/bon-vente' => 'ventes.view',
        'configuration/utilisateurs' => 'config.view',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $path = trim($request->path(), '/');

        if ($path === '' || str_starts_with($path, 'api/')) {
            return $next($request);
        }

        $permissions = session('libautoent_permissions');
        if (is_array($permissions) && isset(self::PATH_PERMISSION[$path])) {
            $need = self::PATH_PERMISSION[$path];
            if (empty($permissions[$need])) {
                return redirect()->route('dashboard');
            }

            return $next($request);
        }

        // Fallback historique : vendeur limité
        if (strtolower((string) session('libautoent_statut', '')) === 'vendeur') {
            $allowed = [
                'dashboard',
                'stock/categorie-produit',
                'stock/etat-produit',
            ];
            if (! in_array($path, $allowed, true)) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
