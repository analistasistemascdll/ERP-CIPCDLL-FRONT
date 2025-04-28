<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/contabilidad/login', function () {
    return view('contabilidad.login');
})->name('contabilidad.login');

Route::get('/contabilidad/home', function () {
    return view('contabilidad.home');
})->name('contabilidad.home');


Route::get('/contabilidad/libro_contable', function () {
    return view('contabilidad.libro_contable');
})->name('contabilidad.libro_contable');

Route::get('/recursosHumanos/home', function () {
    return view('recursosHumanos.home');
})->name('recursosHumanos.home');