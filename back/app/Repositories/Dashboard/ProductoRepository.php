<?php

namespace App\Repositories\Dashboard;

use App\Models\CatApartados;
use App\Models\CatCategorias;
use App\Models\CatStatusPedidos;
use App\Models\TblCaracteristicasProducto;
use App\Models\TblPedidos;
use App\Models\TblProductos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductoRepository
{
    public function obtenerProductosServidor () {
        $url = 'http://45.174.108.252:8085/api/obtenerProductos';

        $token = [
            "token" => env('STRIPE_SECRET')
        ];

        $response = Http::post($url, $token);
        $data = $response->json();
        return $data;
    }

    public function actualizarProductoExistenteECommerce ( $producto ) {
        $query = TblProductos::where('identificador_mbp', $producto['id']);
        $query->update([
                  'descripcion'   => $producto['descripcion'],
                  'precio'        => $producto['precio'],
                  'stock'         => $producto['stock'],
                  'fechaAlta'     => Carbon::now()
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
                                  'tblProductos.stock as stock'
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
                           ->selectRaw("CONCAT('#PE-',tblPedidos.pkTblPedido) as id")
                           ->selectRaw("CONCAT(tblUsuariosTienda.nombre,' ',tblUsuariosTienda.aPaterno) as nombre")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaPedido, '%d-%m-%Y' ) as fechaPedido")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaEntregaEstimada, '%d-%m-%Y' ) as fechaEntregaEstimada")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaEnvio, '%d-%m-%Y' ) as fechaEnvio")
                           ->selectRaw("DATE_FORMAT(tblPedidos.fechaEntrega, '%d-%m-%Y' ) as fechaEntrega")
                           ->selectRaw("(SELECT COUNT(fkTblPedido) FROM tblDetallePedido WHERE fkTblPedido = tblPedidos.pkTblPedido) as productos")
                           ->selectRaw("(SELECT SUM(cantidad) FROM tblDetallePedido WHERE fkTblPedido = tblPedidos.pkTblPedido) as articulos")
                           ->leftJoin('tblUsuariosTienda', 'tblUsuariosTienda.pkTblUsuarioTienda', 'tblPedidos.fkTblUsuarioTienda')
                           ->leftJoin('catStatusPedido', 'catStatusPedido.pkCatStatus', 'tblPedidos.fkStatus')
                           ->whereIn('tblPedidos.fkStatus', $status);

        return $query->get();
    }

    public function obtenerDetallePedido ($idPedido) {
        $query = TblPedidos::where('pkTblPedido', $idPedido);

        return $query->get();
    }

    public function enviarPedido ($idPedido) {
        TblPedidos::where('pkTblPedido', $idPedido)
                  ->update([
                      'fkStatus' => 2,
                      'fechaEnvio' => Carbon::now()
                  ]);
    }

    public function entregarPedido ($idPedido) {
        TblPedidos::where('pkTblPedido', $idPedido)
                  ->update([
                      'fkStatus' => 3,
                      'fechaEntrega' => Carbon::now()
                  ]);
    }

    public function validarCambioFecha ($data) {
        $query = TblPedidos::where([
                               ['pkTblPedido', $data['idPedido']]
                           ])
                           ->whereRaw("DATE_FORMAT(fechaPedido, '%Y-%m-%d') > '" . Carbon::parse($data['fechaEntregaEstimada'])->format('Y-m-d') . "'");
        
        return $query->count() > 0;
    }

    public function entregaEstimada ($data) {
        TblPedidos::where('pkTblPedido', $data['idPedido'])
                  ->update([
                      'fechaEntregaEstimada' => Carbon::parse($data['fechaEntregaEstimada'])
                  ]);
    }

    public function obtenerCantidadPedidosStatus ($status) {
        $query = TblPedidos::where('fkStatus', $status);

        return $query->count();
    }

    public function obtenerProductosVendidos () {
        $query = TblProductos::selectRaw("COUNT(DISTINCT tblproductos.pkTblProducto) AS totalProductosDiferentes")
                             ->join('tbldetallepedido', 'tbldetallepedido.fkTblProducto', '=', 'tblproductos.pkTblProducto');

        return $query->get()[0]->totalProductosDiferentes ?? 0;
    }

    public function obtenerArticulosVendidos () {
        $query = TblProductos::selectRaw("SUM(tbldetallepedido.cantidad) as totalCantidad")
                             ->join('tbldetallepedido', 'tbldetallepedido.fkTblProducto', '=', 'tblproductos.pkTblProducto');

        return $query->get()[0]->totalCantidad ?? 0;
    }

    public function obtenerTotalGananciasVentas () {
        $query = TblProductos::selectRaw("sum((precio - (precio*IFNULL(descuento, 0))) * cantidad) as total")
                             ->join('tbldetallepedido', 'tbldetallepedido.fkTblProducto', '=', 'tblproductos.pkTblProducto');
                            
        return $query->get()[0]->total ?? 0;
    }

    public function obtenerProductosAgregadosRecientes () {
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
                             ->whereNotNull('tblProductos.nombre')
                             ->orderBy('tblProductos.fechaAlta', 'desc')
                             ->take(10);

        return $query->get();
    }

    public function registrarCategoriaProducto ($categoria) {
        $registro = new CatCategorias();
        $registro->nombre      = $categoria['nombre'];
        $registro->descripcion = $categoria['descripcion'] ?? null;
        $registro->save();
        return;
    }

    public function obtenerCategoriasProductos () {
        return CatCategorias::get();
    }

    public function actualizarCategoriaProducto ($caracteristica) {
        CatCategorias::where('pkCatCategoria', $caracteristica['id'])
                     ->update([
                         'nombre' => $caracteristica['nombre'],
                         'descripcion' => $caracteristica['descripcion'] ?? null
                     ]);
        return;
    }

    public function eliminarCategoriaProducto ($pkCategoria) {
        CatCategorias::where('pkCatCategoria', $pkCategoria)
                     ->delete();
        return;
    }
}