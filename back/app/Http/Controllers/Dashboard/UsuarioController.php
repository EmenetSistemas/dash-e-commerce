<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UsuarioService;
use App\Services\ECommerce\UsuarioService as ECommerceUsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    protected $eCommerceUsuarioService;
    protected $usuarioService;

    public function __construct(
        ECommerceUsuarioService $ECommerceUsuarioService,
        UsuarioService $UsuarioService
    )
    {
        $this->eCommerceUsuarioService = $ECommerceUsuarioService;
        $this->usuarioService = $UsuarioService;
    }

    public function obtenerInformacionUsuarioPorToken( Request $request ){
        try{
            return $this->eCommerceUsuarioService->obtenerInformacionUsuarioPorToken( $request->all() );
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
            return $this->eCommerceUsuarioService->obtenerCantidadUsuariosTienda();
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

    public function obtenerClientesPorStatus(Request $request){
        try{
            return $this->eCommerceUsuarioService->obtenerClientesPorStatus($request->all());
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

    public function obtenerDetalleCliente ($idCliente) {
        try{
            return $this->usuarioService->obtenerDetalleCliente($idCliente);
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