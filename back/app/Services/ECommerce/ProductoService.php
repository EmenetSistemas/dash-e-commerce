<?php

namespace App\Services\ECommerce;

use App\Repositories\ECommerce\ProductoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoService
{
    protected $productoRepository;

    public function __construct(
        ProductoRepository $ProductoRepository
    )
    {
        $this->productoRepository = $ProductoRepository;
    }

    public function obtenerProductosPorApartado ($pkApartado) {
        $productos = $this->productoRepository->obtenerProductosPorApartado($pkApartado);

        foreach ($productos as $producto) {
            $producto->caracteristicas = $this->productoRepository->obtenerCaracteristicasProducto($producto->id);
        }

        return response()->json(
            [
                'data' => [
                    'productos' => $productos
                ],
                'mensaje' => 'Se obtuvieron los porductos con éxito'
            ],
            200
        );
    }
}