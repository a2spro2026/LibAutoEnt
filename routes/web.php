<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::post('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.submit');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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

Route::get('/api/store/{key}', [App\Http\Controllers\DataStoreController::class, 'show']);
Route::put('/api/store/{key}', [App\Http\Controllers\DataStoreController::class, 'update']);
