<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::post('/detail.php', function () {
    return view('detail');
});

Route::post('/process_pago', 'ProcessPagoController@processPago');
Route::post('/pago_exitoso', 'ProcessPagoController@pago_exitoso')->name('pago_exitoso');
Route::post('/pago_fallo', 'ProcessPagoController@pago_fallo')->name('pago_fallo');
Route::post('/pago_pendiente', 'ProcessPagoController@pago_pendiente')->name('pago_pendiente');
