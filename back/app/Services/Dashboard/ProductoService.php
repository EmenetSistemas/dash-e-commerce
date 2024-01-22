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

    public function obtenerdetalleProducto ($pkProducto) {
        $this->actualizarProductos();
        $detalleProducto = $this->productoRepository->obtenerProductosPendientes($pkProducto);

        return response()->json(
            [
                'data' => [
                    'detalleProducto' => $detalleProducto
                ],
                'mensaje' => 'Se obtuvo el detalle del producto con éxito'
            ],
            200
        );
    }

    public function obtenerCategoriasApartados () {
        $categoriasApartados = $this->productoRepository->obtenerCategorias();

        foreach ($categoriasApartados as $categoria) {
            $categoria->apartados = $this->productoRepository->obtenerApartadosCategoria($categoria->id);
        }

        return response()->json(
            [
                'data' => [
                    'categoriasApartados' => $categoriasApartados
                ],
                'mensaje' => 'Se obtuvieron las categorías con sus apartados'
            ],
            200
        );
    }

    public function registrarCaracteristicaProducto ($caracteristica) {
        $this->productoRepository->registrarCaracteristicaProducto($caracteristica);

        return response()->json(
            [
                'mensaje' => 'Se agregó la característica con éxito'
            ],
            200
        );
    }
    
    public function obtenerCaracteristicasProducto ($pkProdcuto) {
        $caracteristicasProducto = $this->productoRepository->obtenerCaracteristicasProducto($pkProdcuto);
        
        return response()->json(
            [
                'data' => [
                    'caracteristicasProducto' => $caracteristicasProducto
                ],
                'mensaje' => 'Se obtuvieron las características del producto'
            ],
            200
        );
    }
    
    public function actualizarCaracteristicaProducto ($caracteristica) {
        $this->productoRepository->actualizarCaracteristicaProducto($caracteristica);

        return response()->json(
            [
                'mensaje' => 'Se actualizó la característica con éxito'
            ],
            200
        );
    }

    public function eliminarCaracteristicaProducto ($pkProdcuto) {
        $this->productoRepository->eliminarCaracteristicaProducto($pkProdcuto);
        
        return response()->json(
            [
                'mensaje' => 'Se eliminó la característica con éxiito'
            ],
            200
        );
    }
}