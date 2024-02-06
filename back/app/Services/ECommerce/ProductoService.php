<?php

namespace App\Services\ECommerce;

use App\Repositories\Dashboard\ProductoRepository as DashboardProductoRepository;
use App\Repositories\ECommerce\UsuarioRepository;
use App\Repositories\ECommerce\ProductoRepository;
use App\Services\Dashboard\ProductoService as DashboardProductoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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

        $fechaEntregaEstimada = Carbon::now()->addDays(2)->isWeekend() ? Carbon::now()->addDays(2)->nextWeekday()->format('d-m-Y') : Carbon::now()->addDays(2)->format('d-m-Y');

        return response()->json(
            [
                'data' => [
                    'detalleProductos' => $detalleProductos,
                    'fechaEntregaEstimada' => $fechaEntregaEstimada
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
        $this->dashboardProductoService->actualizarProductos();
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($pedido['token']);

        DB::beginTransaction();
            $pkPedido = $this->productoRepository->agregarPedido($pedido, $datosSesion[0]->pkTblUsuarioTienda);

            $respuestaPago = $this->ventaStripe($pedido['total_pagar'], $pedido['token_id'], $pkPedido);

            if (!$respuestaPago) {
                return response()->json(
                    [
                        'mensaje' => 'El pago fue rechazado, favor de validar la información de la tarjeta o intentar con una diferente',
                        'error' => 402
                    ],
                    200
                );
            }

            foreach ($pedido['productos'] as $producto) {
                $dp = $this->dashboardProductoRepository->obtenerdetalleProducto($producto['id']);
                
                if ($dp[0]->stock >= $producto['cantidad']) {
                    $this->productoRepository->agregarDetallePedido($producto, $pkPedido);
                } else {
                    return response()->json(
                        [
                            'mensaje' => 'Al parecer el stock cambio para el producto "'.$dp[0]->nombre.'", ahora solo contamos con '.$dp[0]->stock.' disponibles',
                            'error' => 204
                        ],
                        200
                    );
                }
            }
        DB::commit();

        return response()->json(
            [
                'mensaje' => 'Pedido realizado con éxito'
            ],
            200
        );
    }

    private function ventaStripe($amount, $token_id, $pkPedido) {
        try {
            $amount = $amount * 100;
            Stripe::setApiKey(env('STRIPE_SECRET'));
    
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'mxn',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => ['token' => $token_id],
                ],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'https://your-website.com/success',
                'description' => 'Pago pedido #PE-'.$pkPedido
            ]);
    
            if ($paymentIntent->status === 'succeeded') {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
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

    public function obtenerActualizacionesPedido ($idPedido) {
        $fechas = $this->productoRepository->obtenerFechasPedido($idPedido);

        $hoy = Carbon::now();
        foreach ($fechas as $index => $fecha) {
            if ($fecha != null && $index != 'fkStatus') {
                $fechaReg = Carbon::parse($fecha);
                $fechaComp = Carbon::parse(Carbon::parse($fecha)->format('d-m-Y'));
                
                if ($index != 'fechaEntregaEstimada') {
                    $diferenciaDias = $fechaComp->diffInDays($hoy);
                    $fechas[$index] = $this->fechasAnteriores($diferenciaDias, $fechaReg);
                } else {
                    if ($fechaReg < $hoy) {
                        $fechas[$index] = null;
                        $fechas['fechaRetrazo'] = $hoy->isWeekend() ? $this->fechasPosteriores($fechaReg->nextWeekday(), $hoy) : $this->fechasPosteriores($hoy, $hoy);
                    } else {
                        $fechas[$index] = $this->fechasPosteriores($fechaReg, $hoy);
                    }
                }
            }
        }

        return response()->json(
            [
                'data' => [
                    'fechas' => $fechas
                ],
                'mensaje' => 'Se consultó el detalle del envio del pedido',
            ],
            200
        );
    }

    private function fechasAnteriores ($diferenciaDias, $fecha) {
        $fecha = Carbon::parse($fecha);
        if ($diferenciaDias === 0) {
            return 'Hoy ' . $fecha->format('h:i A');
        } elseif ($diferenciaDias === 1) {
            return 'Ayer ' . $fecha->format('h:i A');
        } elseif ($diferenciaDias === 2) {
            return 'Antier ' . $fecha->format('h:i A');
        } else {
            return $fecha->format('d-m-Y');
        }
    }

    private function fechasPosteriores ($fecha, $hoy) {
        $fecha = Carbon::parse(Carbon::parse($fecha)->format('d-m-Y'));
        $hoy = Carbon::parse(Carbon::parse($hoy)->format('d-m-Y'));

        $diferenciaDias = $fecha->diffInDays($hoy);

        if ($diferenciaDias === 0) {
            return 'Hoy entre 9:00 AM y 6:00 PM';
        } else if ($fecha > $hoy) {
            if ($diferenciaDias === 1) {
                return 'Mañana entre 9:00 AM y 6:00 PM';
            } elseif ($diferenciaDias === 2) {
                return 'Pasado mañana 9:00 AM y 6:00 PM';
            } else {
                return $fecha->format('d-m-Y');
            }
        }
    }

    public function obtenerNombresProductosTienda () {
        $nombres = $this->productoRepository->obtenerNombresProductosTienda();
        $datos = json_decode($nombres, true);
    
        $nombresProductos = [];
        foreach ($datos as $elemento) {
            if (isset($elemento['nombre'])) {
                $nombresProductos[] = $elemento['nombre'];
            }
        }

        return response()->json(
            [
                'data' => [
                    'nombresProductos' => $nombresProductos
                ],
                'mensaje' => 'Se consultaron los nombres de productos en tienda con éxito',
            ],
            200
        );
    }

    public function obtenerProductosDestacados () {
        $productosDestacados = $this->productoRepository->obtenerProductosDestacados();

        return response()->json(
            [
                'data' => [
                    'productosDestacados' => $productosDestacados
                ],
                'mensaje' => 'Se consultaron los productos destacados con éxito',
            ],
            200
        );
    }

    public function obtenerProductosBusqueda ($data) {
        $palabras = $this->obtenerPalabrasTitulo($data['producto']);
        $productoExacto = $this->productoRepository->obtenerProductosBusquedaInput($data['producto']);
        $busquedaFonetica = $this->productoRepository->obtenerProductosBusquedaFonetica($data['producto'], $palabras);

        $productos = array_merge($productoExacto, $busquedaFonetica);
        $productosUnicos = array_unique($productos, SORT_REGULAR);

        return response()->json(
            [
                'data' => [
                    'productos' => array_merge([], $productosUnicos)
                ],
                'mensaje' => 'Se consultaron los nombres de productos en tienda con éxito',
            ],
            200
        );
    }
    
    function obtenerPalabrasTitulo($titulo) {
        $palabras = str_word_count($titulo, 1);
    
        $palabrasUnicas = [];
        foreach ($palabras as $palabra) {
            if (strlen($palabra) > 2) {
                if ($this->esPlural($palabra)) {
                    $formaSingular = $this->obtenerFormaSingular($palabra);
                    $palabrasUnicas[] = $formaSingular ?: strtolower($palabra);
                } else {
                    $palabrasUnicas[] = strtolower($palabra);
                }
            }
        }
    
        return $palabrasUnicas;
    }
    
    function esPlural($palabra) {
        $terminacionesPlural = ['s', 'es'];
    
        foreach ($terminacionesPlural as $terminacion) {
            if (substr($palabra, -strlen($terminacion)) === $terminacion) {
                return true;
            }
        }
    
        return false;
    }
    
    function obtenerFormaSingular($palabraPlural) {
        return rtrim($palabraPlural, 'es');
    }
}