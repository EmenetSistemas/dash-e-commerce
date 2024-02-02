<?php

namespace App\Repositories\Dashboard;

use App\Models\TblSesionesAdmin;
use App\Models\TblUsuariosTienda;

class UsuarioRepository
{
    public function obtenerInformacionUsuarioPorToken( $token ){
        $usuario = TblSesionesAdmin::select('tblUsuariosAdmin.*')
                                   ->join('tblUsuariosAdmin', 'tblUsuariosAdmin.pkTblUsuarioAdmin', 'tblSesionesAdmin.fkTblUsuarioAdmin')
							       ->where('tblSesionesAdmin.token', '=', $token);

        return $usuario->get();
    }

    public function obtenerDetalleCliente ($idUsuario) {
        $query = TblUsuariosTienda::leftJoin('tblDirecciones', function ($join) {
                                      $join->on('tblDirecciones.fkTblUsuario', 'tblUsuariosTienda.pkTblUsuarioTienda')
                                           ->where('tblDirecciones.statusActual', '=', 1);
                                  })
                                  ->where('tblUsuariosTienda.pkTblUsuarioTienda', $idUsuario);

        return $query->get();
    }
}