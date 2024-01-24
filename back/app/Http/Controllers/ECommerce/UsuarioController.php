<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Services\ECommerce\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    protected $usuarioService;

    public function __construct(
        UsuarioService $UsuarioService
    )
    {
        $this->usuarioService = $UsuarioService;
    }

    public function registro ( Request $request ) {
        try {
            return $this->usuarioService->registro( $request->all() );
        } catch ( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error interno'
                ],
                500
            );
        }
    }

    public function modificacion ( Request $request ) {
        try {
            return $this->usuarioService->modificacion( $request->all() );
        } catch ( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error interno'
                ],
                500
            );
        }
    }

    public function login( Request $request ) {
        try {
            return $this->usuarioService->login( $request->all() );
        } catch ( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error interno'
                ],
                500
            );
        }
    }

    public function obtenerDatosSesion( Request $request ) {
        try {
            return $this->usuarioService->obtenerDatosSesion( $request->all() );
        } catch ( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error interno'
                ],
                500
            );
        }
    }
}