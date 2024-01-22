<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function obtenerProductos ($variante) {
        try{
            return $this->productoService->obtenerProductos($variante);
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

    public function obtenerdetalleProducto ($pkProducto) {
        try{
            return $this->productoService->obtenerdetalleProducto($pkProducto);
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

    public function obtenerCategoriasApartados () {
        try{
            return $this->productoService->obtenerCategoriasApartados();
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

    public function modificarProducto (Request $request) {
        try{
            return $this->productoService->modificarProducto( $request->all() );
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

    public function registrarCaracteristicaProducto (Request $request) {
        try{
            return $this->productoService->registrarCaracteristicaProducto( $request->all() );
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
    
    public function obtenerCaracteristicasProducto ($pkProducto) {
        try{
            return $this->productoService->obtenerCaracteristicasProducto($pkProducto);
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
    
    public function actualizarCaracteristicaProducto (Request $request) {
        try{
            return $this->productoService->actualizarCaracteristicaProducto( $request->all() );
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

    public function eliminarCaracteristicaProducto ($pkProducto) {
        try{
            return $this->productoService->eliminarCaracteristicaProducto($pkProducto);
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