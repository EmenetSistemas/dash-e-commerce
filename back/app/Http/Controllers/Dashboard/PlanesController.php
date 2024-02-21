<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\PlanesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanesController extends Controller
{
    protected $planesService;

    public function __construct(
        PlanesService $PlanesService
    )
    {
        $this->planesService = $PlanesService;
    }

    public function consultarPlanesInternet() {
        try {
            $planes = DB::table('tblPlanes')->get();

            foreach ($planes as $plan) {
                $plan->caracteristicas = DB::table('tblPlanesCaracteristicas')
                    ->join('catCaracteristicas', 'catCaracteristicas.pkCatCaracteristica', 'tblPlanesCaracteristicas.fkCatCaracteristica')
                    ->where('tblPlanesCaracteristicas.fkTblPlan', $plan->pkTblPlan)
                    ->get();
            }

            return response()->json(
                [
                    'data' => [
                        'planesInternet' => $planes
                    ],
                    'mensaje' => 'Se consultaron los planes con éxito.'
                ],
                200
            );
        } catch (\Throwable $error) {
            Log::alert($error);
            return response()->json(
                [
                    'error' => $error,
                    'mensaje' => 'Ocurrió un error al consultar.'
                ],
                500
            );
        }
    }

    public function modificarPlan (Request $request) {
        try{
            return $this->planesService->modificarPlan($request->all());
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

    public function obtenerPlanesInternet () {
        try{
            return $this->planesService->obtenerPlanesInternet();
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

    public function obtenerCaracteristicasPlanes () {
        try{
            return $this->planesService->obtenerCaracteristicasPlanes();
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

    public function obtenerDetallePlan ($idPlan) {
        try{
            return $this->planesService->obtenerDetallePlan($idPlan);
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

    public function registrarCaracteristicaPlan (Request $request) {
        try{
            return $this->planesService->registrarCaracteristicaPlan($request->all());
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

    public function actualizarCaracteristicaPlan (Request $request) {
        try{
            return $this->planesService->actualizarCaracteristicaPlan($request->all());
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

    public function eliminarCaracteristicaPlan ($pkCaracteristica) {
        try{
            return $this->planesService->eliminarCaracteristicaPlan($pkCaracteristica);
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