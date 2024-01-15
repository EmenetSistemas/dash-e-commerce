<?php

namespace App\Services\ECommerce;

use App\Repositories\ECommerce\UsuarioRepository;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    protected $usuarioRepository;

    public function __construct(
        UsuarioRepository $UsuarioRepository
    )
    {
        $this->usuarioRepository = $UsuarioRepository;
    }

    public function login( $credenciales ){
        $usuario = $this->usuarioRepository->validarExistenciaUsuario( $credenciales['correo'], $credenciales['password'] );
        if(is_null($usuario)){
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer las credenciales no son correctas para poder ingresar',
                    'status' => 204
                ],
                200
            );
        }

        if($usuario->activo == 0){
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer tu cuenta esta actualmente supendida',
                    'status' => 409
                ],
                200
            );
        }

        if($usuario->status == 1){
            $token = $usuario->token;
        } else {
            $token = $this->usuarioRepository->obtenerToken();
            DB::beginTransaction();
                $this->usuarioRepository->levantarSesion($usuario->pkTblUsuarioTienda, $usuario->token);
            DB::commit();
        }

        return response()->json(
            [
                'data' => [
                    'token' => $token
                ],
                'mensaje' => 'Bienvenido a Emenet Shop',
                'status' => 200
            ],
            200
        );
    }
}
