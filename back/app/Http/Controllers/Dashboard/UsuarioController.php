<?php

namespace App\Http\Controllers\Dashboard;

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

    public function obtenerInformacionUsuarioPorToken( Request $request ){
        try{
            return $this->usuarioService->obtenerInformacionUsuarioPorToken( $request->all() );
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }

    public function obtenerCantidadUsuariosTienda(){
        try{
            return $this->usuarioService->obtenerCantidadUsuariosTienda();
        } catch( \Throwable $error ) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar' 
                ], 
                500
            );
        }
    }
}