<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rutas dashboard e-commerce
Route::post('/auth/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/auth', 'App\Http\Controllers\Auth\LoginController@auth');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::post('/dashboard/usuarios/obtenerInformacionUsuarioPorToken', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerInformacionUsuarioPorToken');
Route::get('/dashboard/usuarios/obtenerCantidadUsuariosTienda', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerCantidadUsuariosTienda');
Route::post('/dashboard/usuarios/obtenerClientesPorStatus', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerClientesPorStatus');
Route::get('/dashboard/usuarios/obtenerDetalleCliente/{idCliente}', 'App\Http\Controllers\Dashboard\UsuarioController@obtenerDetalleCliente');

// porductos dash
Route::get('/dashboard/productos/obtenerProductos/{variante}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerProductos');
Route::get('/dashboard/productos/obtenerdetalleProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerdetalleProducto');
Route::get('/dashboard/productos/obtenerCategoriasApartados', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCategoriasApartados');
Route::post('/dashboard/productos/actualizarImagen', 'App\Http\Controllers\Dashboard\ProductoController@modificarProducto');
Route::post('/dashboard/productos/modificarProducto', 'App\Http\Controllers\Dashboard\ProductoController@modificarProducto');
Route::post('/dashboard/productos/registrarCategoriaProducto', 'App\Http\Controllers\Dashboard\ProductoController@registrarCategoriaProducto');
Route::get('/dashboard/productos/obtenerCategoriasProductos', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCategoriasProductos');
Route::post('/dashboard/productos/actualizarCategoriaProducto', 'App\Http\Controllers\Dashboard\ProductoController@actualizarCategoriaProducto');
Route::post('/dashboard/productos/registrarApartadpProducto', 'App\Http\Controllers\Dashboard\ProductoController@registrarApartadpProducto');
Route::get('/dashboard/productos/obtenerApartadosProductos', 'App\Http\Controllers\Dashboard\ProductoController@obtenerApartadosProductos');
Route::get('/e-commerce/productos/obtenerProductosDestacados', 'App\Http\Controllers\ECommerce\ProductoController@obtenerProductosDestacados');
Route::post('/dashboard/productos/actualizarApartadoProducto', 'App\Http\Controllers\Dashboard\ProductoController@actualizarApartadoProducto');

    //caracteristicas
    Route::post('/dashboard/productos/registrarCaracteristicaProducto', 'App\Http\Controllers\Dashboard\ProductoController@registrarCaracteristicaProducto');
    Route::get('/dashboard/productos/obtenerCaracteristicasProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCaracteristicasProducto');
    Route::post('/dashboard/productos/actualizarCaracteristicaProducto', 'App\Http\Controllers\Dashboard\ProductoController@actualizarCaracteristicaProducto');
    Route::get('/dashboard/productos/eliminarCaracteristicaProducto/{pkProducto}', 'App\Http\Controllers\Dashboard\ProductoController@eliminarCaracteristicaProducto');
    Route::get('/dashboard/productos/obtenerProductosAgregadosRecientes', 'App\Http\Controllers\Dashboard\ProductoController@obtenerProductosAgregadosRecientes');

//pedidos dash
Route::get('/dashboard/pedidos/obtenerStatusPedidosSelect', 'App\Http\Controllers\Dashboard\ProductoController@obtenerStatusPedidosSelect');
Route::post('/dashboard/pedidos/obtenerPedidosPorStatus', 'App\Http\Controllers\Dashboard\ProductoController@obtenerPedidosPorStatus');
Route::get('/dashboard/pedidos/obtenerDetallePedido/{idPedido}', 'App\Http\Controllers\Dashboard\ProductoController@obtenerDetallePedido');
Route::get('/dashboard/pedidos/enviarPedido/{idPedido}', 'App\Http\Controllers\Dashboard\ProductoController@enviarPedido');
Route::get('/dashboard/pedidos/entregarPedido/{idPedido}', 'App\Http\Controllers\Dashboard\ProductoController@entregarPedido');
Route::post('/dashboard/pedidos/actualizarFechaEstimadaEntrega', 'App\Http\Controllers\Dashboard\ProductoController@actualizarFechaEstimadaEntrega');
Route::get('/dashboard/pedidos/obtenerCantidadPedidosPendientes', 'App\Http\Controllers\Dashboard\ProductoController@obtenerCantidadPedidosPendientes');
Route::get('/dashboard/pedidos/obtenerTotalesDashboard', 'App\Http\Controllers\Dashboard\ProductoController@obtenerTotalesDashboard');

// rutas e-commerce
Route::post('/usuarios/obtenerDatosSesion', 'App\Http\Controllers\ECommerce\UsuarioController@obtenerDatosSesion');
Route::post('/usuarios/login', 'App\Http\Controllers\ECommerce\UsuarioController@login');
Route::post('/usuarios/registro', 'App\Http\Controllers\ECommerce\UsuarioController@registro');
Route::post('/usuarios/modificacion', 'App\Http\Controllers\ECommerce\UsuarioController@modificacion');

// productos e-commerce
Route::get('/e-commerce/productos/obtenerProductosPorApartado/{pkApartado}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerProductosPorApartado');
Route::get('/e-commerce/productos/obtenerDetalleProductoPorId/{pkApartado}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerDetalleProductoPorId');
Route::post('/e-commerce/productos/obtenerDetalleProductosVenta', 'App\Http\Controllers\ECommerce\ProductoController@obtenerDetalleProductosVenta');
Route::get('/e-commerce/productos/obtenerNombresProductosTienda', 'App\Http\Controllers\ECommerce\ProductoController@obtenerNombresProductosTienda');
Route::post('/e-commerce/productos/obtenerProductosBusqueda', 'App\Http\Controllers\ECommerce\ProductoController@obtenerProductosBusqueda');
Route::post('/e-commerce/productos/obtenerProductosPorApartados', 'App\Http\Controllers\ECommerce\ProductoController@obtenerProductosPorApartados');

// carrito compras
Route::post('/e-commerce/carritoCompras/agregarItemCarrito', 'App\Http\Controllers\ECommerce\ProductoController@agregarItemCarrito');
Route::post('/e-commerce/carritoCompras/obtenerNoItemsCarritoCompras', 'App\Http\Controllers\ECommerce\ProductoController@obtenerNoItemsCarritoCompras');
Route::post('/e-commerce/carritoCompras/obtenerItemsCarritoCompras', 'App\Http\Controllers\ECommerce\ProductoController@obtenerItemsCarritoCompras');
Route::get('/e-commerce/carritoCompras/eliminarItemCarrito/{pkItem}', 'App\Http\Controllers\ECommerce\ProductoController@eliminarItemCarrito');
Route::post('/e-commerce/carritoCompras/vaciarCarrito', 'App\Http\Controllers\ECommerce\ProductoController@vaciarCarrito');

// pedidos
Route::post('/e-commerce/pedidos/agregarPedido', 'App\Http\Controllers\ECommerce\ProductoController@agregarPedido');
Route::post('/e-commerce/pedidos/obtenerNoPedidos', 'App\Http\Controllers\ECommerce\ProductoController@obtenerNoPedidos');
Route::post('/e-commerce/pedidos/obtenerPedidos', 'App\Http\Controllers\ECommerce\ProductoController@obtenerPedidos');
Route::get('/e-commerce/pedidos/cancelarPedido/{idPedido}', 'App\Http\Controllers\ECommerce\ProductoController@cancelarPedido');
Route::get('/e-commerce/pedidos/cancelarProductoPedido/{idPedido}/{idProducto}', 'App\Http\Controllers\ECommerce\ProductoController@cancelarProductoPedido');
Route::get('/e-commerce/pedidos/obtenerActualizacionesPedido/{idPedido}', 'App\Http\Controllers\ECommerce\ProductoController@obtenerActualizacionesPedido');

//planes internet
Route::get('/internet/planes', 'App\Http\Controllers\Dashboard\PlanesController@consultarPlanesInternet');
Route::post('/dashboard/planes-internet/registrarPlan', 'App\Http\Controllers\Dashboard\PlanesController@registrarPlan');
Route::post('/dashboard/planes-internet/modificarPlan', 'App\Http\Controllers\Dashboard\PlanesController@modificarPlan');
Route::get('/dashboard/planes-internet/obtenerPlanesInternet', 'App\Http\Controllers\Dashboard\PlanesController@obtenerPlanesInternet');
Route::post('/dashboard/planes-internet/registrarCaracteristica', 'App\Http\Controllers\Dashboard\PlanesController@registrarCaracteristica');
Route::post('/dashboard/planes-internet/actualizarCaracteristica', 'App\Http\Controllers\Dashboard\PlanesController@actualizarCaracteristica');
Route::get('/dashboard/planes-internet/obtenerCaracteristicasPlanes', 'App\Http\Controllers\Dashboard\PlanesController@obtenerCaracteristicasPlanes');
Route::get('/dashboard/planes-internet/obtenerDetallePlan/{idPlan}', 'App\Http\Controllers\Dashboard\PlanesController@obtenerDetallePlan');
Route::post('/dashboard/planes-internet/registrarCaracteristicaPlan', 'App\Http\Controllers\Dashboard\PlanesController@registrarCaracteristicaPlan');
Route::post('/dashboard/planes-internet/actualizarCaracteristicaPlan', 'App\Http\Controllers\Dashboard\PlanesController@actualizarCaracteristicaPlan');
Route::get('/dashboard/planes-internet/eliminarCaracteristicaPlan/{idCaracteristica}', 'App\Http\Controllers\Dashboard\PlanesController@eliminarCaracteristicaPlan');