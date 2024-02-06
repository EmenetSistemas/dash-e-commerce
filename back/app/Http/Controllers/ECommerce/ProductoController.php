<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Services\ECommerce\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    protected $productoService;

    public function __construct(
        ProductoService $ProductoService
    )
    {
        $this->productoService = $ProductoService;
    }

    public function obtenerProductosPorApartado ($pkApartado) {
        try{
            return $this->productoService->obtenerProductosPorApartado($pkApartado);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerDetalleProductoPorId ($pkProducto) {
        try{
            return $this->productoService->obtenerDetalleProductoPorId($pkProducto);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerDetalleProductosVenta (Request $request) {
        try{
            return $this->productoService->obtenerDetalleProductosVenta($request->all());
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function agregarItemCarrito (Request $request) {
        try{
            return $this->productoService->agregarItemCarrito($request->all());
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerNoItemsCarritoCompras (Request $request) {
        try{
            return $this->productoService->obtenerNoItemsCarritoCompras($request->all()['token']);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerItemsCarritoCompras (Request $request) {
        try{
            return $this->productoService->obtenerItemsCarritoCompras($request->all()['token']);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function eliminarItemCarrito ($pkItemCarrito) {
        try{
            return $this->productoService->eliminarItemCarrito($pkItemCarrito);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function vaciarCarrito (Request $request) {
        try{
            return $this->productoService->vaciarCarrito($request->all()['token']);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }
    
    public function agregarPedido (Request $request) {
        try{
            return $this->productoService->agregarPedido($request->all());
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerNoPedidos (Request $request) {
        try{
            return $this->productoService->obtenerNoPedidos($request->all()['token']);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerPedidos (Request $request) {
        try{
            return $this->productoService->obtenerPedidos($request->all()['token']);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function cancelarPedido ($idPedido) {
        try{
            return $this->productoService->cancelarPedido($idPedido);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function cancelarProductoPedido ($idPedido, $idProducto) {
        try{
            return $this->productoService->cancelarProductoPedido($idPedido, $idProducto);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerActualizacionesPedido ($idPedido) {
        try{
            return $this->productoService->obtenerActualizacionesPedido($idPedido);
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerNombresProductosTienda () {
        try{
            return $this->productoService->obtenerNombresProductosTienda();
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }
    
    public function obtenerProductosDestacados () {
        try{
            return $this->productoService->obtenerProductosDestacados();
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerProductosBusqueda (Request $request) {
        try{
            return $this->productoService->obtenerProductosBusqueda($request->all());
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }
}