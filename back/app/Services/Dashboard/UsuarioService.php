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
}