<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/contabilidad/login', function () {
    return view('contabilidad.login');
})->name('contabilidad.login');

Route::get('/contabilidad/home', function () {
    return view('contabilidad.home');
})->name('contabilidad.home');

Route::get('/contabilidad/cuentas', function () {
    return view('contabilidad.cuentas');
})->name('contabilidad.cuentas');

Route::get('/contabilidad/asignar_cuentas', function () {
    return view('contabilidad.asignar_cuentas');
})->name('contabilidad.asignar_cuentas');

Route::get('/contabilidad/cuentas_hija', function () {
    return view('contabilidad.cuentas_hija');
})->name('contabilidad.cuentas_hija');

Route::get('/recursosHumanos/home', function () {
    return view('recursosHumanos.home');
})->name('recursosHumanos.home');