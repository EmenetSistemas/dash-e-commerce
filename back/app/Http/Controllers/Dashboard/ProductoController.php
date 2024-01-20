<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProductoService;
use GuzzleHttp\Psr7\Request;
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

    public function obtenerProductosPendientes () {
        try{
            return $this->productoService->obtenerProductosPendientes();
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
}