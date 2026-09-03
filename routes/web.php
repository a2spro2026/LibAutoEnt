<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $statut = strtolower(trim((string) $request->input('statut', 'gerant')));
    if (! in_array($statut, ['gerant', 'assis', 'vendeur'], true)) {
        $statut = 'gerant';
    }

    $login = trim((string) $request->input('login', ''));
    $permissions = libautoent_find_user_permissions($login, $statut);

    session([
        'libautoent_statut' => $statut,
        'libautoent_login' => $login,
        'libautoent_permissions' => $permissions,
    ]);

    return redirect()->route('dashboard');
})->name('login.submit');

Route::post('/', function (Request $request) {
    return redirect()->route('login.submit');
});

Route::get('/logout', function () {
    session()->forget(['libautoent_statut', 'libautoent_login', 'libautoent_permissions']);

    return redirect()->route('login');
})->name('logout');

Route::middleware('restrict.vendeur')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/recuperation-ventes', function () {
        return view('recuperation-ventes');
    })->name('recuperation-ventes');

    Route::get('/achats/bon-achat', function () {
        return view('achats.bon-achat');
    })->name('bon-achat');

    Route::get('/achats/reglement-achat', function () {
        return view('achats.reglement-achat');
    })->name('reglement-achat');

    Route::get('/achats/balance-achat', function () {
        return view('achats.balance-achat');
    })->name('balance-achat');

    Route::get('/ventes/bon-vente', function () {
        return view('ventes.bon-vente');
    })->name('bon-vente');

    Route::get('/ventes/reglement-vente', function () {
        return view('ventes.reglement-vente');
    })->name('reglement-vente');

    Route::get('/ventes/balance-vente', function () {
        return view('ventes.balance-vente');
    })->name('balance-vente');

    Route::get('/stock/categorie-produit', function () {
        return view('stock.categorie-produit');
    })->name('categorie-produit');

    Route::get('/stock/etat-produit', function () {
        return view('stock.etat-produit');
    })->name('etat-produit');

    Route::get('/configuration/utilisateurs', function () {
        return view('configuration.utilisateurs');
    })->name('utilisateurs');
});

Route::get('/api/store/{key}', [App\Http\Controllers\DataStoreController::class, 'show']);
Route::put('/api/store/{key}', [App\Http\Controllers\DataStoreController::class, 'update']);
Route::post('/api/photo', [App\Http\Controllers\DataStoreController::class, 'uploadPhoto']);
