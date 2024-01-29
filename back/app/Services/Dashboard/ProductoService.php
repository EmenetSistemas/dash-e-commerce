<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ProductoRepository;
use App\Repositories\ECommerce\ProductoRepository as ECommerceProductoRepository;
use App\Repositories\ECommerce\UsuarioRepository;
use App\Services\ECommerce\ProductoService as ECommerceProductoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoService
{
    protected $productoRepository;
    protected $usuarioRepository;
    protected $eCommerceProductoRepository;

    public function __construct(
        ProductoRepository $ProductoRepository,
        UsuarioRepository $UsuarioRepository,
        ECommerceProductoRepository $ECommerceProductoRepository
    )
    {
        $this->productoRepository = $ProductoRepository;
        $this->usuarioRepository = $UsuarioRepository;
        $this->eCommerceProductoRepository = $ECommerceProductoRepository;
    }

    public function actualizarProductos () {
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

    public function obtenerProductos ($variante) {
        $this->actualizarProductos();
        $productosPendientes = $this->productoRepository->obtenerProductos($variante);

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
        $detalleProducto = $this->productoRepository->obtenerdetalleProducto($pkProducto);

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

    public function modificarProducto ($producto) {
        $this->productoRepository->modificarProducto($producto);

        return response()->json(
            [
                'mensaje' => 'Se actualizó el producto con éxito'
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

    public function obtenerStatusPedidosSelect () {
        $sociosGenerales = $this->productoRepository->obtenerStatusPedidosGenerales();
        $opcionesSelect = [];

        foreach( $sociosGenerales as $item ){
            $temp = [
                'value' => $item->pkCatStatus,
                'label' => $item->nombreStatus,
                'checked' => false
            ];

            array_push($opcionesSelect, $temp);
        }
        
        return response()->json(
            [
                'mensaje' => 'Se consultaron los Status Pedidos con éxito',
                'data' => $opcionesSelect
            ]
        );
    }

    public function obtenerPedidosPorStatus($status){
        $pedidosStatus = $this->productoRepository->obtenerPedidosPorStatus($status['status']);

        return response()->json(
            [
                'mensaje' => 'Se consultaron los Pedidos por status seleccionados con éxito',
                'data' => $pedidosStatus
            ]
        );
    }

    public function obtenerDetallePedido ($idPedido) {
        $productos = $this->eCommerceProductoRepository->obtenerProductosPedido($idPedido);
        $pedido = $this->productoRepository->obtenerDetallePedido($idPedido);
        $usuario = $this->usuarioRepository->obtenerInformacionUsuarioPorId($pedido[0]->fkTblUsuarioTienda);

        $pedido[0]->fechaPedido          = $pedido[0]->fechaPedido != null ? Carbon::parse($pedido[0]->fechaPedido)->format('Y-m-d') : null;
        $pedido[0]->fechaEntregaEstimada = $pedido[0]->fechaEntregaEstimada != null ? Carbon::parse($pedido[0]->fechaEntregaEstimada)->format('Y-m-d') : null;
        $pedido[0]->fechaEntrega         = $pedido[0]->fechaEntrega != null ? Carbon::parse($pedido[0]->fechaEntrega)->format('Y-m-d') : null;

        return response()->json(
            [
                'data' => [
                    'datosUsuario' => $usuario,
                    'productosPedido' => $productos,
                    'detallePedido' => $pedido
                ],
                'mensaje' => 'Se consultó la información del usuario con éxito'
            ],
            200
        );
    }

    public function enviarPedido ($idPedido) {
        $this->productoRepository->enviarPedido($idPedido);

        return response()->json(
            [
                'mensaje' => 'Se cambio el status del pedido a enviado con éxito',
            ],
            200
        );
    }

    public function entregarPedido ($idPedido) {
        $this->productoRepository->entregarPedido($idPedido);

        return response()->json(
            [
                'mensaje' => 'Se cambio el status del pedido a entregado con éxito',
            ],
            200
        );
    }

    public function actualizarFechaEstimadaEntrega ($data) {
        $fechaInvalida = $this->productoRepository->validarCambioFecha($data);

        if ($fechaInvalida) {
            return response()->json(
                [
                    'mensaje' => 'La fecha estimada de entrega debe ser mayor a la fecha en que se realizó el pedido, favor de validar la información.',
                    'error' => 203
                ],
                200
            );
        }

        $this->productoRepository->entregaEstimada($data);

        return response()->json(
            [
                'mensaje' => 'Se actualizó la fecha estimada de entrega con éxito',
            ],
            200
        );
    }
}