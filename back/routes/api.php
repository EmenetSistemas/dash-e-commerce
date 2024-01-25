<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rutas dashboard e-commerce
Route::post('/auth/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/auth', 'App\Http\Controllers\Auth\LoginController@auth');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::post('/dashboard/usuarios/obtenerInformacionUsuarioPorToken', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerInformacionUsuarioPorToken');
Route::get('/dashboard/usuarios/obtenerCantidadUsuariosTienda', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerCantidadUsuariosTienda');

// porductos dash
Route::get('/dashboard/productos/obtenerProductos/{variante}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerProductos');
Route::get('/dashboard/productos/obtenerdetalleProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerdetalleProducto');
Route::get('/dashboard/productos/obtenerCategoriasApartados', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCategoriasApartados');
Route::post('/dashboard/productos/actualizarImagen', 'App\Http\Controllers\Dashboard\ProductoController@modificarProducto');
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
Route::post('/usuarios/modificacion', 'App\Http\Controllers\ECommerce\UsuarioController@modificacion');
    // productos e-commerce
    Route::get('/e-commerce/productos/obtenerProductosPorApartado/{pkApartado}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerProductosPorApartado');
    Route::get('/e-commerce/productos/obtenerDetalleProductoPorId/{pkApartado}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerDetalleProductoPorId');
    Route::post('/e-commerce/productos/obtenerDetalleProductosVenta', 'App\Http\Controllers\ECommerce\ProductoController@obtenerDetalleProductosVenta');

    // carrito compras
    Route::post('/e-commerce/carritoCompras/agregarItemCarrito', 'App\Http\Controllers\ECommerce\ProductoController@agregarItemCarrito');
    Route::get('/e-commerce/carritoCompras/obtenerNoItemsCarritoCompras/{token}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerNoItemsCarritoCompras');
    Route::get('/e-commerce/carritoCompras/obtenerItemsCarritoCompras/{token}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerItemsCarritoCompras');
    Route::get('/e-commerce/carritoCompras/eliminarItemCarrito/{token}', 'App\Http\Controllers\ECommerce\ProductoController@eliminarItemCarrito');
    Route::get('/e-commerce/carritoCompras/vaciarCarrito/{token}', 'App\Http\Controllers\ECommerce\ProductoController@vaciarCarrito');

    // pedidos
    Route::post('/e-commerce/pedidos/agregarPedido', 'App\Http\Controllers\ECommerce\ProductoController@agregarPedido');
    Route::get('/e-commerce/pedidos/obtenerNoPedidos/{token}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerNoPedidos');
    Route::get('/e-commerce/pedidos/obtenerPedidos/{token}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerPedidos');
    Route::get('/e-commerce/pedidos/cancelarPedido/{idPedido}', 'App\Http\Controllers\ECommerce\ProductoController@cancelarPedido');
    Route::get('/e-commerce/pedidos/cancelarProductoPedido/{idPedido}/{idProducto}', 'App\Http\Controllers\ECommerce\ProductoController@cancelarProductoPedido');