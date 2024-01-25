<?php

namespace App\Services\ECommerce;

use App\Repositories\ECommerce\UsuarioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsuarioService
{
    protected $usuarioRepository;

    public function __construct(
        UsuarioRepository $UsuarioRepository
    )
    {
        $this->usuarioRepository = $UsuarioRepository;
    }

    public function registro ( $datosUsuario ) {
        $validaCorreo = $this->usuarioRepository->validarCorreoExistente($datosUsuario['correo']);

        if ($validaCorreo > 0) {
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer ya existe una cuenta vinculada a este correo. Por favor validar la información',
                    'status' => 409
                ],
                200
            );
        }

        DB::beginTransaction();
            $data = $this->usuarioRepository->registrarUsuario($datosUsuario);
            $this->usuarioRepository->registrarDireccion($datosUsuario, $data['pk']);
            $this->usuarioRepository->registrarMetodoPago($datosUsuario, $data['pk']);
        DB::commit();

        return response()->json(
            [
                'data' => [
                    'token' => $data['token']
                ],
                'mensaje' => 'Se registraron sus datos con éxito. Bienvenido a Emenet Shop.',
                'status' => 200
            ],
            200
        );
    }

    public function modificacion ( $datosUsuario ) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($datosUsuario['token']);
        $validaCorreo = $this->usuarioRepository->validarCorreoExistente($datosUsuario['correo'], $datosSesion[0]->pkTblUsuarioTienda);

        if ($validaCorreo > 0) {
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer ya existe una cuenta vinculada a este correo. Por favor validar la información',
                    'status' => 409
                ],
                200
            );
        }

        DB::beginTransaction();
            $this->usuarioRepository->actaulizarUsuario($datosUsuario, $datosSesion[0]->pkTblUsuarioTienda);
            $this->usuarioRepository->actualizarDireccion($datosUsuario, $datosSesion[0]->pkTblUsuarioTienda);
            $this->usuarioRepository->actualizarMetodoPago($datosUsuario, $datosSesion[0]->pkTblUsuarioTienda);
        DB::commit();

        return response()->json(
            [
                'mensaje' => 'Se actualizó la información con éxito.',
                'status' => 200
            ],
            200
        );
    }

    public function login ( $credenciales ) {
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

    public function obtenerDatosSesion ( $data ) {
        $datosSesion = $this->usuarioRepository->obtenerDatosSesion($data['token']);

        if (count($datosSesion) == 0 ? true : false) {
            return response()->json(
                [
                    'data' => [],
                    'mensaje' => 'No se encontró infromación',
                    'status' => 204
                ],
                200
            );
        }

        $data = [
            'nombre' => $datosSesion[0]->nombre,
            'aPaterno' => $datosSesion[0]->aPaterno,
            'aMaterno' => $datosSesion[0]->aMaterno,
            'telefono' => $datosSesion[0]->telefono,
            'correo' => $datosSesion[0]->correo,
            'direccion' => [
                'pkTblDireccion' => $datosSesion[0]->pkTblDireccion,
                'calle' => $datosSesion[0]->calle,
                'noExterior' => $datosSesion[0]->noExterior,
                'localidad' => $datosSesion[0]->localidad,
                'municipio' => $datosSesion[0]->municipio,
                'estado' => $datosSesion[0]->estado,
                'cp' => $datosSesion[0]->cp,
                'referencias' => $datosSesion[0]->referencias,
            ],
            'metodoPago' => [
                'emisor' => $datosSesion[0]->emisor,
                'tipo' => $datosSesion[0]->tipo,
                'noTarjeta' => $datosSesion[0]->noTarjeta
            ]
        ];

        return response()->json(
            [
                'data' => $data,
                'mensaje' => 'Se consultó su información con éxito',
                'status' => 200
            ],
            200
        );
    }

    public function obtenerInformacionUsuarioPorToken( $token ){
        return $this->usuarioRepository->obtenerInformacionUsuarioPorToken( $token['token'] );
    }

    public function obtenerCantidadUsuariosTienda(){
        return $this->usuarioRepository->obtenerCantidadUsuariosTienda();
    }
}