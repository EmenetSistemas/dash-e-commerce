<?php

namespace App\Repositories\ECommerce;

use App\Models\TblUsuariosTienda;
use Illuminate\Support\Str;

class UsuarioRepository
{
    public function validarExistenciaUsuario ($correo, $password) {
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

    public function validarCorreoExistente ($correo, $idUsuario = 0) {
        $validarCorreo = TblUsuariosTienda::where([
            ['correo', $correo],
            ['pkTblUsuarioTienda', '!=', $idUsuario]
        ]);

        return $validarCorreo->count();
    }

    public function registrarUsuario ($datosUsuario) {
        $registro = new TblUsuariosTienda();
        $registro->nombre = $datosUsuario['nombre'];
        $registro->aPaterno = $datosUsuario['aPaterno'];
        $registro->aMaterno = $datosUsuario['aMaterno'];
        $registro->telefono = $datosUsuario['telefono'];
        $registro->correo = $datosUsuario['correo'];
        $registro->password = $datosUsuario['password'];
        $registro->save();
    }

    public function obtenerInformacionPorToken ($token) {
        $query = TblUsuariosTienda::where('token', $token);

        return $query->get()[0] ?? [];
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