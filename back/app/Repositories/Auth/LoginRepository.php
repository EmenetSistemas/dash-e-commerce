<?php

namespace App\Repositories\Auth;

use App\Models\TblSesionesAdmin;
use App\Models\TblUsuariosAdmin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginRepository
{
        public function validarExistenciaUsuario ( $correo, $password ) {
            $temporal = TblUsuariosAdmin::select(
                                   'pkTblUsuarioAdmin',
                                   'password'
                               )
                               ->where('correo', $correo)
                               ->first();

        return $temporal && password_verify($password, $temporal->password) ? $temporal->pkTblUsuarioAdmin : null;
    }

    public function validarUsuarioActivo( $pkUsuario ){
        $usuario = TblUsuariosAdmin::where([
                                 ['pkTblUsuarioAdmin', $pkUsuario],
                                 ['activo', 1]
                              ]);

        return $usuario->count() > 0;
    }

    public function depurarSesionPorPK ( $pkUsuario ) {
        TblSesionesAdmin::where('fkTblUsuarioAdmin', $pkUsuario)
                   ->delete();
    }

    public function crearSesionYAsignarToken ( $pkUsuario ){
        $registro = new TblSesionesAdmin();
        $registro->fkTblUsuarioAdmin = $pkUsuario;
        $registro->Token             = bcrypt(Str::random(50));
        $registro->save();
        
        return $registro->Token;
    }

    public function auth( $token ){
        $sesiones = TblSesionesAdmin::where('token', $token)->count();
        return $sesiones > 0 ? 'true' : 'false';
    }

    public function logout( $token ){
        $sesion = TblSesionesAdmin::where('token', $token);
        
        $sesion->delete();
    }
}