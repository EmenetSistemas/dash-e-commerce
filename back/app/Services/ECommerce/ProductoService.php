<?php

namespace App\Services\ECommerce;

use App\Repositories\Dashboard\ProductoRepository as DashboardProductoRepository;
use App\Repositories\ECommerce\UsuarioRepository;
use App\Repositories\ECommerce\ProductoRepository;
use App\Services\Dashboard\ProductoService as DashboardProductoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoService
{
    protected $productoRepository;
    protected $dashboardProductoRepository;
    protected $dashboardProductoService;
    protected $usuarioRepository;

    public function __construct(
        ProductoRepository $ProductoRepository,
        DashboardProductoRepository $DashboardProductoRepository,
        DashboardProductoService $DashboardProductoService,
        UsuarioRepository $UsuarioRepository
    )
    {
        $this->productoRepository = $ProductoRepository;
        $this->dashboardProductoRepository = $DashboardProductoRepository;
        $this->dashboardProductoService = $DashboardProductoService;
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
        $this->dashboardProductoService->actualizarProductos();
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
        $this->dashboardProductoService->actualizarProductos();
        $detalleProductos = [];
        foreach ($productos as $producto) {
            $temp = $this->dashboardProductoRepository->obtenerdetalleProducto($producto['idItem'] ?? $producto['id'])[0];
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
    
    public function eliminarItemCarrito ($pkItemCarrito) {
        $this->productoRepository->eliminarItemCarrito($pkItemCarrito);

        return response()->json(
            [
                'mensaje' => 'Se eliminó el producto del carrito'
            ],
            200
        );
    }

    public function vaciarCarrito ($token) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($token);
        $this->productoRepository->vaciarCarrito($datosSesion[0]->pkTblUsuarioTienda);

        return response()->json(
            [
                'mensaje' => 'Se eliminarón todos los productos del carrito de compras'
            ],
            200
        );
    }

    public function agregarPedido ($pedido) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($pedido['token']);

        DB::beginTransaction();
            $pkPedido = $this->productoRepository->agregarPedido($pedido, $datosSesion[0]->pkTblUsuarioTienda);
            foreach ($pedido['productos'] as $producto) {
                $this->productoRepository->agregarDetallePedido($producto, $pkPedido);
            }
        DB::commit();

        return response()->json(
            [
                'mensaje' => 'Pedido realizado con éxito'
            ],
            200
        );
    }

    public function obtenerNoPedidos ($token) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($token);
        $noPedidos = $this->productoRepository->obtenerNoPedidos($datosSesion[0]->pkTblUsuarioTienda);

        return response()->json(
            [
                'data' => [
                    'noPedidos' => $noPedidos
                ],
                'mensaje' => 'Se eliminarón todos los productos del carrito de compras'
            ],
            200
        );
    }

    public function obtenerPedidos ($token) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($token);
        $pedidos = $this->productoRepository->obtenerPedidos($datosSesion[0]->pkTblUsuarioTienda);

        foreach ($pedidos as $pedido) {
            $pedido->productos = $this->productoRepository->obtenerProductosPedido($pedido->idPedido);
        }

        return response()->json(
            [
                'data' => [
                    'pedidos' => $pedidos
                ],
                'mensaje' => 'Se eliminarón todos los productos del carrito de compras'
            ],
            200
        );
    }

    public function cancelarPedido ($idPedido) {
        $this->productoRepository->cancelarPedido($idPedido);

        return response()->json(
            [
                'mensaje' => 'Se canceló el pedido con éxito'
            ],
            200
        );
    }

    public function cancelarProductoPedido ($idPedido, $idProducto) {
        $this->productoRepository->cancelarProductoPedido($idPedido, $idProducto);

        return response()->json(
            [
                'mensaje' => 'Se eliminó el producto del pedido con éxito'
            ],
            200
        );
    }
}