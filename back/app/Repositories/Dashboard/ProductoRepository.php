<?php

namespace App\Repositories\Dashboard;

use App\Models\CatApartados;
use App\Models\CatCategorias;
use App\Models\CatStatusPedidos;
use App\Models\TblCaracteristicasProducto;
use App\Models\TblPedidos;
use App\Models\TblProductos;
use Illuminate\Support\Facades\DB;
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

    public function obtenerProductos ($variante = 'pendientes') {
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
                             ->leftJoin('catCategorias', 'catCategorias.pkCatCategoria', 'catApartados.fkCatCategoria');

        if ($variante == 'pendientes' || $variante == 'cantidadPendientes') {
            $query->whereNull('tblProductos.nombre');
        } else {
            $query->whereNotNull('tblProductos.nombre');
        }

        if ($variante == 'cantidadPendientes' || $variante == 'cantidadTienda') {
            return $query->count();
        } else {
            return $query->get();
        }
    }

    public function obtenerdetalleProducto ($pkProducto) {
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
                             ->where('tblProductos.pkTblProducto', $pkProducto);
    
        return $query->get();
    }

    public function obtenerCategorias () {
        $query = CatCategorias::select(
                                  'pkCatCategoria as id',
                                  'nombre',
                                  'descripcion'
                              );

        return $query->get();
    }

    public function obtenerApartadosCategoria($pkCategoria) {
        $query = CatApartados::select(
                'pkCatApartado as id',
                'fkCatCategoria',
                'nombre',
                DB::raw('false as checked')
            )
            ->where('fkCatCategoria', $pkCategoria);
    
        return $query->get();
    }

    public function modificarProducto ($producto) {
        $update = [
            'nombre' => $producto['nombreProducto'],
            'descuento' => is_null($producto['descuento']) ? $producto['descuento'] : $producto['descuento'] / 100,
            'fkCatApartado' => $producto['apartadoProducto']
        ];

        if (isset($producto['imagen'])) {
            $update['imagen'] = $producto['imagen'];
        }
        TblProductos::where('pkTblProducto', $producto['pkProducto'])
                    ->update($update);
    }

    public function registrarCaracteristicaProducto ($caracteristica) {
        $registro = new TblCaracteristicasProducto();
        $registro->fkTblProducto = $caracteristica['fkTblProducto'];
        $registro->titulo        = $caracteristica['titulo'];
        $registro->descripcion   = $caracteristica['descripcion'];
        $registro->save();
        return;
    }
    
    public function obtenerCaracteristicasProducto ($pkProdcuto) {
        $query = TblCaracteristicasProducto::select(
                                               'pkTblCaracteristicaProducto as id',
                                               'titulo',
                                               'descripcion'
                                           )
                                           ->where('fkTblProducto', $pkProdcuto)
                                           ->orderBy('titulo', 'asc');
        
        return $query->get();
    }

    public function actualizarCaracteristicaProducto ($caracteristica) {
        TblCaracteristicasProducto::where('pkTblCaracteristicaProducto', $caracteristica['id'])
                                  ->update([
                                      'titulo' => $caracteristica['titulo'],
                                      'descripcion' => $caracteristica['descripcion']
                                  ]);
        return;
    }

    public function eliminarCaracteristicaProducto ($pkProdcuto) {
        TblCaracteristicasProducto::where('pkTblCaracteristicaProducto', $pkProdcuto)
                                  ->delete();
        return;
    }

    public function obtenerStatusPedidosGenerales () {
        return CatStatusPedidos::get();
    }

    public function obtenerPedidosPorStatus ($status) {
        $query = TblPedidos::select(
                               'tblPedidos.pkTblPedido as pkTblPedido',
                               'tblPedidos.fkTblUsuarioTienda as fkTblUsuarioTienda',
                               'catStatusPedido.nombreStatus as nombreStatus'
                           )
                           ->selectRaw("CONCAT(tblUsuariosTienda.nombre,' ',tblUsuariosTienda.aPaterno) as nombre")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaPedido, '%d-%m-%Y' ) as fechaPedido")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaEntrega, '%d-%m-%Y' ) as fechaEntrega")
                           ->selectRaw("(SELECT COUNT(fkTblPedido) FROM tblDetallePedido WHERE fkTblPedido = tblPedidos.pkTblPedido) as productos")
                           ->selectRaw("(SELECT SUM(cantidad) FROM tblDetallePedido WHERE fkTblPedido = tblPedidos.pkTblPedido) as articulos")
                           ->leftJoin('tblUsuariosTienda', 'tblUsuariosTienda.pkTblUsuarioTienda', 'tblPedidos.fkTblUsuarioTienda')
                           ->leftJoin('catStatusPedido', 'catStatusPedido.pkCatStatus', 'tblPedidos.fkStatus')
                           ->whereIn('tblPedidos.fkStatus', $status);

        return $query->get();
    }
}