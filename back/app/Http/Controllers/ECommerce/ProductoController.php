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

    public function obtenerNoItemsCarritoCompras ($token) {
        try{
            return $this->productoService->obtenerNoItemsCarritoCompras($token);
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

    public function obtenerItemsCarritoCompras ($token) {
        try{
            return $this->productoService->obtenerItemsCarritoCompras($token);
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

    public function vaciarCarrito ($token) {
        try{
            return $this->productoService->vaciarCarrito($token);
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

    public function obtenerNoPedidos ($token) {
        try{
            return $this->productoService->obtenerNoPedidos($token);
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

    public function obtenerPedidos ($token) {
        try{
            return $this->productoService->obtenerPedidos($token);
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
}