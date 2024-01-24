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
                                 'tblProductos.nombre as nombre',
                                 'tblProductos.precio as precio',
                                 'tblProductos.descuento as descuento',
                                 'tblProductos.imagen as imagen'
                             )
                             ->leftJoin('catApartados', 'catApartados.pkCatApartado', 'tblProductos.fkCatApartado')
                             ->where('catApartados.pkCatApartado', $pkApartado);

        return $query->get();
    }
}