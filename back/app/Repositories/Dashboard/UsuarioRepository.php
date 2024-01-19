<?php

namespace App\Repositories\Dashboard;

use App\Models\TblSesionesAdmin;

class UsuarioRepository
{
    public function obtenerInformacionUsuarioPorToken( $token ){
        $usuario = TblSesionesAdmin::select('tblUsuariosAdmin.*')
                                   ->join('tblUsuariosAdmin', 'tblUsuariosAdmin.pkTblUsuarioAdmin', 'tblSesionesAdmin.fkTblUsuarioAdmin')
							       ->where('tblSesionesAdmin.token', '=', $token);

        return $usuario->get();
    }
}