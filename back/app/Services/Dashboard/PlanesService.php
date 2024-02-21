<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\PlanesRepository;
use Illuminate\Support\Facades\Log;

class PlanesService
{
    protected $planesRepository;

    public function __construct(
        PlanesRepository $PlanesRepository
    )
    {
        $this->planesRepository = $PlanesRepository;
    }

    public function registrarPlan ($plan) {
        $validaPlan = $this->planesRepository->validarPlanExistente($plan);

        if ($validaPlan > 0) {
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer ya existe un plan similar',
                    'status' => 203
                ],
                200
            );
        }

        $this->planesRepository->registrarPlan($plan);

        return response()->json(
            [
                'mensaje' => 'Se registró el plan con éxito'
            ],
            200
        );
    }

    public function modificarPlan ($plan) {
        $validaPlan = $this->planesRepository->validarPlanExistente($plan);

        if ($validaPlan > 0) {
            return response()->json(
                [
                    'mensaje' => 'Upss! Al parecer ya existe un plan similar',
                    'status' => 203
                ],
                200
            );
        }

        $this->planesRepository->modificarPlan($plan);

        return response()->json(
            [
                'mensaje' => 'Se actualizó el plan con éxito'
            ],
            200
        );
    }

    public function obtenerPlanesInternet () {
        $planes = $this->planesRepository->obtenerPlanesInternet();

        return response()->json(
            [
                'data' => [
                    'planes' => $planes
                ],
                'mensaje' => 'Se obtuvieron los planes con éxito'
            ],
            200
        );
    }

    public function obtenerCaracteristicasPlanes () {
        $caracteristicas = $this->planesRepository->obtenerCaracteristicasPlanes();

        return response()->json(
            [
                'data' => [
                    'caracteristicas' => $caracteristicas
                ],
                'mensaje' => 'Se obtuvieron las características con éxito'
            ],
            200
        );
    }

    public function obtenerDetallePlan ($idPlan) {
        $detallePlan = $this->planesRepository->obtenerDetallePlan($idPlan);
        $caracteristicasPlan = $this->planesRepository->obtenerExtrasPlan($idPlan);

        return response()->json(
            [
                'data' => [
                    'plan' => $detallePlan,
                    'caracteristicas' => $caracteristicasPlan
                ],
                'mensaje' => 'Se obtuvieron los planes con éxito'
            ],
            200
        );
    }

    public function registrarCaracteristicaPlan ($caracteristica) {
        $this->planesRepository->registrarCaracteristicaPlan($caracteristica);

        return response()->json(
            [
                'mensaje' => 'Se registró la característica con éxito'
            ],
            200
        );
    }

    public function actualizarCaracteristicaPlan ($caracteristica) {
        $this->planesRepository->actualizarCaracteristicaPlan($caracteristica);

        return response()->json(
            [
                'mensaje' => 'Se actualizó la característica con éxito'
            ],
            200
        );
    }

    public function eliminarCaracteristicaPlan ($pkCaracteristica) {
        $this->planesRepository->eliminarCaracteristicaPlan($pkCaracteristica);
        
        return response()->json(
            [
                'mensaje' => 'Se eliminó la característica con éxiito'
            ],
            200
        );
    }
}