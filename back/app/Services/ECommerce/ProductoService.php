<?php

namespace App\Services\ECommerce;

use App\Repositories\Dashboard\ProductoRepository as DashboardProductoRepository;
use App\Repositories\ECommerce\UsuarioRepository;
use App\Repositories\ECommerce\ProductoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoService
{
    protected $productoRepository;
    protected $dashboardProductoRepository;
    protected $usuarioRepository;

    public function __construct(
        ProductoRepository $ProductoRepository,
        DashboardProductoRepository $DashboardProductoRepository,
        UsuarioRepository $UsuarioRepository
    )
    {
        $this->productoRepository = $ProductoRepository;
        $this->dashboardProductoRepository = $DashboardProductoRepository;
        $this->usuarioRepository = $UsuarioRepository;
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

    public function obtenerDetalleProductosVenta ($productos) {
        $detalleProductos = [];
        foreach ($productos as $producto) {
            $temp = $this->dashboardProductoRepository->obtenerdetalleProducto($producto['idItem'])[0];
            $temp->cantidad = $producto['cantidad'];
            array_push($detalleProductos, $temp);
        }

        return response()->json(
            [
                'data' => [
                    'detalleProductos' => $detalleProductos
                ],
                'mensaje' => 'Se obtuvieron los porductos con éxito'
            ],
            200
        );
    }

    public function agregarItemCarrito ($item) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($item['token']);
        $validaItem = $this->productoRepository->validarItemEnCarrito($item['idItem'], $datosSesion[0]->pkTblUsuarioTienda);
        
        if ($validaItem != null) {
            $cantidadItem = $this->productoRepository->obtenerCantidadDisponibleItem($item['idItem']);

            $cantidadTotal = ($item['cantidad'] + $validaItem->cantidad);
            if (
                $cantidadTotal > $cantidadItem->cantidad
            ) {
                return response()->json(
                    [
                        'titulo'  => $cantidadItem->cantidad.' productos disponibles',
                        'mensaje' => 'Actualmente cuentas con '.$validaItem->cantidad.' '.($validaItem->cantidad == 1 ? 'producto' : 'productos').' en tu carrito, e intentas agregar '.$item['cantidad'].' más, lo cual no es posible.',
                        'error'   => 402
                    ],
                    200
                );
            }

            $this->productoRepository->actualizarCantidadEnCarritoPorItem($validaItem->pkTblCarritoCompras, $cantidadTotal);
            return response()->json(
                [
                    'mensaje' => 'Se agregó al carrito',
                ],
                200
            );
        }

        $this->productoRepository->agregarItemCarrito($item, $datosSesion[0]->pkTblUsuarioTienda);
        return response()->json(
            [
                'mensaje' => 'Se agregó al carrito',
            ],
            200
        );
    }

    public function obtenerNoItemsCarritoCompras ($token) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($token);
        $noItemsCarrito = $this->productoRepository->obtenerNoItemsCarritoCompras($datosSesion[0]->pkTblUsuarioTienda);

        return response()->json(
            [
                'data' => [
                    'noItemsCarrito' => $noItemsCarrito
                ],
                'mensaje' => 'Se obtuvieron los porductos con éxito'
            ],
            200
        );
    }

    public function obtenerItemsCarritoCompras ($token) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($token);
        $carrito = $this->productoRepository->obtenerItemsCarritoCompras($datosSesion[0]->pkTblUsuarioTienda);

        return response()->json(
            [
                'data' => [
                    'carritoCompras' => $carrito
                ],
                'mensaje' => 'Se obtuvieron los porductos con éxito'
            ],
            200
        );
    }
}