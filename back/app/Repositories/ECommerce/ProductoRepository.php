<?php

namespace App\Repositories\ECommerce;

use App\Models\TblCaracteristicasProducto;
use App\Models\TblProductos;
use Illuminate\Support\Facades\Log;

class ProductoRepository
{
    public function obtenerProductosPorApartado ($pkApartado) {
        $query = TblProductos::select(
                                 'tblProductos.pkTblProducto as id',
                                 'tblProductos.identificador_mbp as identificador_mbp',
                                 'tblProductos.nombre as nombre',
                                 'tblProductos.imagen as imagen',
                                 'tblProductos.precio as precio',
                                 'tblProductos.descuento as descuento',
                                 'catCategorias.nombre as categoria',
                                 'catCategorias.pkCatCategoria as idCategoria',
                                 'catApartados.nombre as apartado',
                                 'catApartados.pkCatApartado as idApartado',
                                 'tblProductos.calificacion as calificacion',
                                 'tblProductos.calificaciones as calificaciones',
                                 'tblProductos.descripcion as descripcion',
                                 'tblProductos.stock as stock',
                             )
                             ->leftJoin('catApartados', 'catApartados.pkCatApartado', 'tblProductos.fkCatApartado')
                             ->leftJoin('catCategorias', 'catCategorias.pkCatCategoria', 'catApartados.fkCatCategoria')
                             ->where('catApartados.pkCatApartado', $pkApartado);

        return $query->get();
    }

    public function obtenerCaracteristicasProducto ($pkProducto) {
        $query = TblCaracteristicasProducto::select(
                                               'titulo',
                                               'descripcion'
                                           )
                                           ->where('fkTblProducto', $pkProducto);

        return $query->get();
    }
}