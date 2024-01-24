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
}