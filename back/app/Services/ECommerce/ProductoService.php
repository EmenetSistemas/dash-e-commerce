<?php

namespace App\Services\ECommerce;

use App\Repositories\Dashboard\ProductoRepository as DashboardProductoRepository;
use App\Repositories\ECommerce\ProductoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoService
{
    protected $productoRepository;
    protected $dashboardProductoRepository;

    public function __construct(
        ProductoRepository $ProductoRepository,
        DashboardProductoRepository $DashboardProductoRepository
    )
    {
        $this->productoRepository = $ProductoRepository;
        $this->dashboardProductoRepository = $DashboardProductoRepository;
    }

    public function obtenerProductosPorApartado ($pkApartado) {
        $productos = $this->productoRepository->obtenerProductosPorApartado($pkApartado);

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

    public function obtenerDetalleProductoPorId ($pkProducto) {
        $detalleProducto = $this->dashboardProductoRepository->obtenerdetalleProducto($pkProducto);
        $detalleProducto[0]->caracteristicas = $this->dashboardProductoRepository->obtenerCaracteristicasProducto($pkProducto);

        return response()->json(
            [
                'data' => [
                    'detalleProducto' => $detalleProducto
                ],
                'mensaje' => 'Se obtuvieron los porductos con éxito'
            ],
            200
        );
    }
}