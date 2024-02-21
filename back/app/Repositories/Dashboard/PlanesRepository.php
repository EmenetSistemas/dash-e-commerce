<?php

namespace App\Repositories\Dashboard;

use App\Models\CatCaracteristicas;
use App\Models\TblPlanes;
use App\Models\TblPlanesCaracteristicas;
use Illuminate\Support\Facades\DB;

class PlanesRepository
{
    public function validarPlanExistente ($plan, $id = 0) {
        $query = TblPlanes::where([
                              ['pkTblPlan', '!=', $plan['pkTblPlan'] ?? $id],
                              ['tipoPlan', $plan['tipoPlan']],
                              ['plan', $plan['plan']]
                          ]);

        return $query->count();
    }

    public function registrarPlan ($plan) {
        $registro = new TblPlanes;
        $registro->plan                    = $plan['plan'];
        $registro->mensualidad             = $plan['mensualidad'] ?? null;
        $registro->anualidad               = $plan['anualidad'] ?? null;
        $registro->tipoPlan                = $plan['tipoPlan'];
        $registro->dispositivosSimultaneos = $plan['dispositivosSimultaneos'];
        $registro->estudioTrabajo          = $plan['estudioTrabajo'];
        $registro->reproduccionVideo       = $plan['reproduccionVideo'];
        $registro->juegoLinea              = $plan['juegoLinea'];
        $registro->transmisiones           = $plan['transmisiones'];
        $registro->save();

        $registroCar = new TblPlanesCaracteristicas;
        $registroCar->fkTblPlan           = $registro->pkTblPlan;
        $registroCar->fkCatCaracteristica = 1;
        $registroCar->save();

        return $registro->pkTblPlan;
    }

    public function modificarPlan ($plan) {
        TblPlanes::where('pkTblPlan', $plan['pkTblPlan'])
                 ->update([
                     'plan' => $plan['plan'],
                     'mensualidad' => $plan['mensualidad'] ?? null,
                     'anualidad' => $plan['anualidad'] ?? null,
                     'tipoPlan' => $plan['tipoPlan'],
                     'dispositivosSimultaneos' => $plan['dispositivosSimultaneos'],
                     'estudioTrabajo' => $plan['estudioTrabajo'],
                     'reproduccionVideo' => $plan['reproduccionVideo'],
                     'juegoLinea' => $plan['juegoLinea'],
                     'transmisiones' => $plan['transmisiones']
                 ]);
    }

    public function obtenerPlanesInternet () {
        $query = TblPlanes::select(
                              'pkTblPlan',
                              'plan',
                              DB::raw("CASE 
                                          WHEN tipoPlan = 1 THEN 'Plan'
                                          ELSE 'Paquete'
                                      END AS tipoPlan"),
                              DB::raw("COALESCE(mensualidad, anualidad) AS precio"),
                              DB::raw("CASE
                                      WHEN mensualidad IS NOT NULL THEN 'Mensual'
                                      ELSE 'Anual'
                                  END AS periodo")
                          );

        return $query->get();
    }

    public function obtenerCaracteristicasPlanes () {
        $query = CatCaracteristicas::where('pkCatCaracteristica', '!=', 1);

        return $query->get();
    }

    public function obtenerDetallePlan ($idPlan) {
        $query = TblPlanes::where('PkTblPlan', $idPlan);

        return $query->get()[0];
    }

    public function obtenerExtrasPlan ($idPlan) {
        $query = TblPlanesCaracteristicas::select(
                                             'tblPlanesCaracteristicas.pkPlanCaracteristica as pkPlanCaracteristica',
                                             'catCaracteristicas.*'
                                         )
                                         ->join('catCaracteristicas', function ($join) {
                                            $join->on('catCaracteristicas.pkCatCaracteristica', 'tblPlanesCaracteristicas.fkCatCaracteristica')
                                                 ->where('catCaracteristicas.pkCatCaracteristica', '!=', 1);
                                         })
                                         ->where('tblPlanesCaracteristicas.fkTblPlan', $idPlan);

        return $query->get();
    }

    public function registrarCaracteristicaPlan ($caracteristica) {
        $registro = new TblPlanesCaracteristicas;
        $registro->fkTblPlan           = $caracteristica['fkPlan'];
        $registro->fkCatCaracteristica = $caracteristica['idCaracteristica'];
        $registro->save();
    }

    public function actualizarCaracteristicaPlan ($caracteristica) {
        TblPlanesCaracteristicas::where('pkPlanCaracteristica', $caracteristica['fkTblPlanCaracteristica'])
                                ->update([
                                    'fkCatCaracteristica' => $caracteristica['idCaracteristica']
                                ]);
    }

    public function eliminarCaracteristicaPlan ($pkCaracteristica) {
        TblPlanesCaracteristicas::where('pkPlanCaracteristica', $pkCaracteristica)
                                ->delete();
    }
}