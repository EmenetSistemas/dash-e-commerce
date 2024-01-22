<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rutas dashboard e-commerce
Route::post('/auth/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/auth', 'App\Http\Controllers\Auth\LoginController@auth');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::post('/dashboard/usuarios/obtenerInformacionUsuarioPorToken', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerInformacionUsuarioPorToken');

// porductos dash
Route::get('/dashboard/productos/obtenerProductosPendientes', 'App\Http\Controllers\Dashboard\ProductoController@obtenerProductosPendientes');
Route::get('/dashboard/productos/obtenerdetalleProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerdetalleProducto');
Route::get('/dashboard/productos/obtenerCategoriasApartados', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCategoriasApartados');
Route::post('/dashboard/productos/modificarProducto', 'App\Http\Controllers\Dashboard\ProductoController@modificarProducto');
    //caracteristicas
    Route::post('/dashboard/productos/registrarCaracteristicaProducto', 'App\Http\Controllers\Dashboard\ProductoController@registrarCaracteristicaProducto');
    Route::get('/dashboard/productos/obtenerCaracteristicasProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCaracteristicasProducto');
    Route::post('/dashboard/productos/actualizarCaracteristicaProducto', 'App\Http\Controllers\Dashboard\ProductoController@actualizarCaracteristicaProducto');
    Route::get('/dashboard/productos/eliminarCaracteristicaProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@eliminarCaracteristicaProducto');

// rutas e-commerce
Route::post('/usuarios/obtenerDatosSesion', 'App\Http\Controllers\ECommerce\UsuarioController@obtenerDatosSesion');
Route::post('/usuarios/login', 'App\Http\Controllers\ECommerce\UsuarioController@login');
Route::post('/usuarios/registro', 'App\Http\Controllers\ECommerce\UsuarioController@registro');