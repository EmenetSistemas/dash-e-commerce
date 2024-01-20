<?php

namespace App\Repositories\ECommerce;

use App\Models\TblDirecciones;
use App\Models\TblMetodosPago;
use App\Models\TblSesionesAdmin;
use App\Models\TblUsuariosTienda;
use Illuminate\Support\Facades\Log;
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

        return $temporal && password_verify($password, $temporal->password) ? $temporal : null;
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
        $registro->nombre   = $datosUsuario['nombre'];
        $registro->aPaterno = $datosUsuario['aPaterno'];
        $registro->aMaterno = $datosUsuario['aMaterno'];
        $registro->telefono = $datosUsuario['telefono'];
        $registro->correo   = $datosUsuario['correo'];
        $registro->password = bcrypt($datosUsuario['password']);
        $registro->token    = $this->obtenerToken();
        $registro->status   = 1;
        $registro->activo   = 1;
        $registro->save();

        return [
            'pk' => $registro->pkTblUsuarioTienda,
            'token' => $registro->token
        ];
    }

    public function registrarDireccion ($datosUsuario, $pkRegistro) {
        $registro = new TblDirecciones();
        $registro->fkTblUsuario = $pkRegistro;
        $registro->calle        = $datosUsuario['calle'];
        $registro->noExterior   = $datosUsuario['noExterior'];
        $registro->localidad    = $datosUsuario['localidad'];
        $registro->municipio    = $datosUsuario['municipio'];
        $registro->estado       = $datosUsuario['estado'];
        $registro->cp           = $datosUsuario['cp'];
        $registro->save();
    }

    public function registrarMetodoPago ($datosUsuario, $pkRegistro) {
        $registro = new TblMetodosPago();
        $registro->fkTblUsuario = $pkRegistro;
        $registro->emisor       = $datosUsuario['emisor'];
        $registro->tipo         = $datosUsuario['tipo'];
        $registro->noTarjeta    = $datosUsuario['noTarjeta'];
        $registro->statusActual = 1;
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

    public function obtenerDatosSesion ( $token ) {
        $query = TblUsuariosTienda::join('tblDirecciones', 'tblDirecciones.fkTblUsuario', 'tblUsuariosTienda.pkTblUsuarioTienda')
                                  ->join('tblMetodosPago', function ($join) {
                                        $join->on('tblMetodosPago.fkTblUsuario', 'tblUsuariosTienda.pkTblUsuarioTienda')
                                             ->where('tblMetodosPago.statusActual', '=', 1);
                                  })
                                  ->where('tblUsuariosTienda.token', $token);

        return $query->get() ?? [];
    }

    public function obtenerInformacionUsuarioPorToken( $token ){
        $usuario = TblSesionesAdmin::select('tblUsuariosAdmin.*')
                                   ->join('tblUsuariosAdmin', 'tblUsuariosAdmin.pkTblUsuarioAdmin', 'tblSesionesAdmin.fkTblUsuarioAdmin')
							       ->where('tblSesionesAdmin.token', '=', $token);

        return $usuario->get();
    }
}