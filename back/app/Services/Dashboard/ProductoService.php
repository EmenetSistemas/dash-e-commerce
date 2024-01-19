<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ProductoRepository;
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

    private function actualizarProductos () {
        $productosServer = $this->productoRepository->obtenerProductosServidor();
        
        DB::beginTransaction();
            foreach ($productosServer['data'] as $producto) {
                $update = $this->productoRepository->actualizarProductoExistenteECommerce($producto);
                if ($update == 0) {
                    $this->productoRepository->registrarProductoECommerce($producto);
                }
            }
        DB::commit();
    }

    public function obtenerProductosPendientes () {
        $this->actualizarProductos();
        $productosPendientes = $this->productoRepository->obtenerProductosPendientes();

        return response()->json(
            [
                'data' => [
                    'productos' => $productosPendientes
                ],
                'mensaje' => 'Se obtuvieron los porductos pendientes con éxito'
            ],
            200
        );
    }
}
