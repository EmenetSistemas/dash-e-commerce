<?php

namespace App\Repositories\ECommerce;

use App\Models\TblCaracteristicasProducto;
use App\Models\TblCarritoCompras;
use App\Models\TblProductos;
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
                                      ['pkTblCarritoCompras', $pkItem]
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
}