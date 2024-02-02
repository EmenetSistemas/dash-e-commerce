<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\UsuarioRepository;

class UsuarioService
{
    protected $usuariosRepository;

    public function __construct(
        UsuarioRepository $UsuariosRepository
    )
    {
        $this->usuariosRepository = $UsuariosRepository;
    }

    public function obtenerInformacionUsuarioPorToken( $token ){
        return $this->usuariosRepository->obtenerInformacionUsuarioPorToken( $token['token'] );
    }

    public function obtenerDetalleCliente ($idCliente) {
        $datosCliente = $this->usuariosRepository->obtenerDetalleCliente($idCliente);

        $data = [
            'nombre' => $datosCliente[0]->nombre,
            'aPaterno' => $datosCliente[0]->aPaterno,
            'aMaterno' => $datosCliente[0]->aMaterno,
            'telefono' => $datosCliente[0]->telefono,
            'correo' => $datosCliente[0]->correo,
            'direccion' => [
                'pkTblDireccion' => $datosCliente[0]->pkTblDireccion,
                'calle' => $datosCliente[0]->calle,
                'noExterior' => $datosCliente[0]->noExterior,
                'localidad' => $datosCliente[0]->localidad,
                'municipio' => $datosCliente[0]->municipio,
                'estado' => $datosCliente[0]->estado,
                'cp' => $datosCliente[0]->cp,
                'referencias' => $datosCliente[0]->referencias,
            ],
            'metodoPago' => [
                'emisor' => $datosCliente[0]->emisor,
                'tipo' => $datosCliente[0]->tipo,
                'noTarjeta' => $datosCliente[0]->noTarjeta
            ]
        ];

        return response()->json(
            [
                'data' => [
                    'datosCliente' => $data
                ],
                'mensaje' => 'Se consultó la información del usuario con éxito'
            ],
            200
        );
    }
}