<?php

use App\Http\Controllers\clientcontroller;
use App\Http\Controllers\reservationcontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\voiturecontroller;


Route::get('/', function () {
    return view('home');
});


Route::get('/voiture', [voiturecontroller::class, 'index'])->name('voiture.index');
Route::post('/voiture', [voiturecontroller::class, 'store'])->name('voiture.store');
Route::put('/voiture/{id}', [voiturecontroller::class, 'update'])->name('voiture.update');
Route::delete('/voiture/{id}', [voiturecontroller::class, 'destroy'])->name('voiture.destroy');


Route::get('/client', [clientcontroller::class, 'index'])->name('client.index');
Route::post('/client', [clientcontroller::class, 'store'])->name('client.store');
Route::put('/client/{id}', [clientcontroller::class, 'update'])->name('client.update');
Route::delete('/client/{id}', [clientcontroller::class, 'destroy'])->name('client.destroy');


Route::get('/reservation/places-libres/{idvoit}', [reservationcontroller::class, 'placesLibres'])->name('reservation.placesLibres');
Route::get('/reservation/stats/{idvoit}', [reservationcontroller::class, 'statsPaiement'])->name('reservation.stats');
Route::get('/reservation/recette', [reservationcontroller::class, 'recetteTotal'])->name('reservation.recette');
Route::get('/reservation/recu/{id}', [reservationcontroller::class, 'recu'])->name('reservation.recu');
Route::get('/reservation', [reservationcontroller::class, 'index'])->name('reservation.index');
Route::post('/reservation', [reservationcontroller::class, 'store'])->name('reservation.store');
Route::put('/reservation/{id}', [reservationcontroller::class, 'update'])->name('reservation.update');
Route::delete('/reservation/{id}', [reservationcontroller::class, 'destroy'])->name('reservation.destroy');
