<?php

namespace App\Repositories\Dashboard;

use App\Models\TblProductos;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductoRepository
{
    public function obtenerProductosServidor () {
        $url = 'http://45.174.108.252:8085/api/obtenerProductos';

        $response = Http::get($url);
        $data = $response->json();
        return $data;
    }

    public function actualizarProductoExistenteECommerce ( $producto ) {
        $query = TblProductos::where('identificador_mbp', $producto['id']);
        $query->update([
                  'descripcion'   => $producto['descripcion'],
                  'precio'        => $producto['precio'],
                  'stock'         => $producto['stock']
              ]);

        return $query->select('pkTblProducto')->count();
    }

    public function registrarProductoECommerce ( $producto ) {
        $registro = new TblProductos();
        $registro->identificador_mbp = $producto['id'];
        $registro->descripcion       = $producto['descripcion'];
        $registro->precio            = $producto['precio'];
        $registro->stock             = $producto['stock'];
        $registro->save();
    }

    public function obtenerProductosPendientes () {
        $query = TblProductos::select(
                                  'tblProductos.pkTblProducto as id',
                                  'tblProductos.identificador_mbp as identificador_mbp',
                                  'tblProductos.nombre as nombre',
                                  'tblProductos.imagen as imagen',
                                  'tblProductos.precio as precio',
                                  'tblProductos.descuento as descuento',
                                  'CatCategorias.nombre as categoria',
                                  'CatCategorias.pkCatCategoria as idCategoria',
                                  'CatApartados.nombre as apartado',
                                  'CatApartados.pkCatApartado as idApartado',
                                  'tblProductos.calificacion as calificacion',
                                  'tblProductos.calificaciones as calificaciones',
                                  'tblProductos.descripcion as descripcion',
                                  'tblProductos.stock as stock',
                             )
                             ->leftJoin('CatApartados', 'CatApartados.pkCatApartado', 'tblProductos.fkCatApartado')
                             ->leftJoin('CatCategorias', 'CatCategorias.pkCatCategoria', 'CatApartados.fkCatCategoria');

        return $query->get();
    }
}