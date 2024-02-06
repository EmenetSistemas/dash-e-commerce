<?php

namespace App\Repositories\ECommerce;

use App\Models\TblCarritoCompras;
use App\Models\TblDetallePedido;
use App\Models\TblPedidos;
use App\Models\TblProductos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoRepository
{
    public function obtenerProductosPorApartado ($pkApartado) {
        $query = TblProductos::select(
                                 'tblProductos.pkTblProducto as id',
                                 'tblProductos.nombre as nombre',
                                 'tblProductos.precio as precio',
                                 'tblProductos.descuento as descuento',
                                 'tblProductos.imagen as imagen'
                             )
                             ->leftJoin('catApartados', 'catApartados.pkCatApartado', 'tblProductos.fkCatApartado')
                             ->where('catApartados.pkCatApartado', $pkApartado);

        return $query->get();
    }

    public function validarItemEnCarrito ($pkItem, $pkUsuario) {
        $query = TblCarritoCompras::where([
                                      ['fkTblUsuarioTienda', $pkUsuario],
                                      ['idItem', $pkItem]
                                  ]);

        return $query->get()[0] ?? null;
    }

    public function obtenerCantidadDisponibleItem ($pkItem) {
        $query = TblProductos::select('stock as cantidad')
                             ->where('pkTblProducto', $pkItem);

        return $query->get()[0];
    }

    public function actualizarCantidadEnCarritoPorItem ($pkItemCarrito, $cantidad) {
        TblCarritoCompras::where('pkTblCarritoCompras', $pkItemCarrito)
                         ->update([
                             'cantidad' => $cantidad
                         ]);
    }

    public function agregarItemCarrito ($item, $pkUsuario) {
        $registro = new TblCarritoCompras();
        $registro->fkTblUsuarioTienda = $pkUsuario;
        $registro->idItem             = $item['idItem'];
        $registro->cantidad           = $item['cantidad'];
        $registro->save();
    }

    public function obtenerNoItemsCarritoCompras ($pkUsuario) {
        $query = TblCarritoCompras::select('pkTblCarritoCompras')
                                  ->where('fkTblUsuarioTienda', $pkUsuario);

        return $query->count();
    }

    public function obtenerItemsCarritoCompras ($pkUsuario) {
        $query = TblCarritoCompras::select(
                                      'tblCarritoCompras.*',
                                      'tblProductos.imagen',
                                      'tblProductos.precio',
                                      'tblProductos.descuento'
                                  )
                                  ->leftJoin('tblProductos', 'tblProductos.pkTblProducto', 'tblCarritoCompras.idItem')
                                  ->where('tblCarritoCompras.fkTblUsuarioTienda', $pkUsuario);

        return $query->get();
    }

    public function eliminarItemCarrito ($pkItemCarrito) {
        TblCarritoCompras::where('pkTblCarritoCompras', $pkItemCarrito)
                         ->delete();
    }
    
    public function vaciarCarrito ($pkTblUsuarioTienda) {
        TblCarritoCompras::where('fkTblUsuarioTienda', $pkTblUsuarioTienda)
                         ->delete();
    }

    public function agregarPedido ($pedido, $pkUsuario) {
        $registro = new TblPedidos();
        $registro->fkTblUsuarioTienda   = $pkUsuario;
        $registro->fkTblDireccion       = $pedido['pkDireccion'];
        $registro->fechaPedido          = Carbon::now();
        $registro->fechaEntregaEstimada = Carbon::parse($pedido['fechaEntregaEstimada']);
        $registro->fechaAlta            = Carbon::now();
        $registro->fkStatus             = 1;
        $registro->save();

        return $registro->pkTblPedido;
    }

    public function agregarDetallePedido ($producto, $pkPedido) {
        $registro = new TblDetallePedido();
        $registro->fkTblPedido        = $pkPedido;
        $registro->fkTblProducto      = $producto['id'];
        $registro->cantidad           = $producto['cantidad'];
        $registro->save();
    }

    public function obtenerNoPedidos ($pkUsuario) {
        $query = TblPedidos::select('pkTblPedido')
                           ->where('fkTblUsuarioTienda', $pkUsuario);

        return $query->count();
    }

    public function obtenerPedidos ($pkUsuario) {
        $query = TblPedidos::select(
                               'tblPedidos.pkTblPedido as idPedido',
                               'tblPedidos.fkStatus as fkStatus'
                           )
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaPedido, '%d-%m-%Y') as fechaPedido")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaEntregaEstimada, '%d-%m-%Y') as fechaEntregaEstimada")
                           ->selectRaw("CONCAT(tblDirecciones.calle,', ',tblDirecciones.localidad,', ',tblDirecciones.municipio,', ',tblDirecciones.estado,', ',tblDirecciones.cp) as direccionEntrega")
                           ->leftJoin('tblDirecciones', 'tblDirecciones.pkTblDireccion', 'tblPedidos.fkTblDireccion')
                           ->where('fkTblUsuarioTienda', $pkUsuario);

        return $query->get();
    }

    public function obtenerProductosPedido ($pkPedido) {
        $query = TblDetallePedido::select(
                                     'tblDetallePedido.cantidad',
                                     'tblProductos.pkTblProducto as idItem',
                                     'tblProductos.identificador_mbp as identificador_mbp',
                                     'tblProductos.nombre as nombre',
                                     'tblProductos.descripcion as descripcion',
                                     'tblProductos.precio as precio',
                                     'tblProductos.descuento as descuento',
                                     'tblProductos.imagen as imagen'
                                 )
                                 ->leftJoin('tblProductos', 'tblProductos.pkTblProducto', 'tblDetallePedido.fkTblProducto')
                                 ->leftJoin('catApartados', 'catApartados.pkCatApartado', 'tblProductos.fkCatApartado')
                                 ->where('tblDetallePedido.fkTblPedido', $pkPedido);

        return $query->get();
    }

    public function cancelarPedido ($pkPedido) {
        TblPedidos::where('pkTblPedido', $pkPedido)
                  ->delete();
        TblDetallePedido::where('fkTblPedido', $pkPedido)
                        ->delete();
    }

    public function cancelarProductoPedido ($pkPedido, $idProducto) {
        TblDetallePedido::where([
                      ['fkTblPedido', $pkPedido],
                      ['fkTblProducto', $idProducto],
                  ])
                  ->delete();
    }

    public function obtenerFechasPedido ($idPedido) {
        $query = TblPedidos::select(
                               'tblPedidos.fechaPedido',
                               'tblPedidos.fechaEntregaEstimada',
                               'tblPedidos.fechaEnvio',
                               'tblPedidos.fechaEntrega',
                               'tblPedidos.fkStatus'
                           )
                           ->leftJoin('catStatusPedido', 'catStatusPedido.pkCatStatus', 'tblPedidos.fkStatus')
                           ->where('tblPedidos.pkTblPedido', $idPedido);

        return (array)json_decode(json_encode($query->get()[0] ?? []));
    }

    public function obtenerNombresProductosTienda () {
        $query = TblProductos::select('nombre')
                             ->whereNotNull('nombre');

        return $query->get();
    }

    public function obtenerProductosDestacados () {
        $query = TblDetallePedido::select(
                                     'tbldetallepedido.fkTblProducto as id',
                                     'tblproductos.nombre as nombre',
                                     'tblproductos.precio as precio',
                                     'tblproductos.descuento as descuento',
                                     'tblproductos.imagen as imagen'
                                 )
                                 ->selectRaw('SUM(tbldetallepedido.cantidad) as totalVentas')
                                 ->leftJoin('tblproductos', 'tblproductos.pkTblProducto', 'tbldetallepedido.fkTblProducto')
                                 ->groupBy(
                                     'tbldetallepedido.fkTblProducto',
                                     'tblproductos.nombre',
                                     'tblproductos.precio',
                                     'tblproductos.descuento',
                                     'tblproductos.imagen'
                                 )
                                 ->orderByDesc('totalVentas')
                                 ->limit(10);

        return $query->get();
    }

    public function obtenerProductosBusquedaInput ($producto) {
        $query = TblProductos::select(
                                 'pkTblProducto as id',
                                 'nombre as nombre',
                                 'precio as precio',
                                 'descuento as descuento',
                                 'imagen as imagen'
                             )
                             ->where('nombre', $producto)
                             ->orWhere('nombre', 'like', '%'.$producto.'%');

        return (array)json_decode(json_encode($query->get()));
    }

    public function obtenerProductosBusquedaFonetica ($producto, $palabras) {
        $query = TblProductos::select(
                                 'pkTblProducto as id',
                                 'nombre as nombre',
                                 'precio as precio',
                                 'descuento as descuento',
                                 'imagen as imagen'
                             );
                             
        foreach ($palabras as $palabra) {
            $query->orWhere('nombre', 'like', '%'.$palabra.'%');
        }

        return (array)json_decode(json_encode($query->get()));
    }
}