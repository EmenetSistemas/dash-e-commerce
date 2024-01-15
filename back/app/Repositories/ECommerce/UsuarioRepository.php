<?php

namespace App\Repositories\ECommerce;

use App\Models\TblUsuariosTienda;
use Illuminate\Support\Str;

class UsuarioRepository
{
    public function validarExistenciaUsuario ( $correo, $password ) {
        $temporal = TblUsuariosTienda::select(
                                   'pkTblUsuarioTienda',
                                   'password',
                                   'activo',
                                   'status',
                                   'token'
                               )
                               ->where('correo', $correo)
                               ->first();

        return $temporal && password_verify($password, $temporal->password) ? $temporal[0] : null;
    }

    public function obtenerToken () {
        return bcrypt(Str::random(50));
    }

    public function levantarSesion ($pkUsuario, $token) {
        TblUsuariosTienda::where('pkTblUsuarioTienda', $pkUsuario)
                         ->update([
                            'status' => 1,
                            'token' => $token
                         ]);
    }
}