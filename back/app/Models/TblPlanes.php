<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPlanes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblPlan';
    protected $table = 'tblPlanes';
    protected $fillable = 
    [
        'pkTblPlan',
        'plan',
        'mensualidad',
        'anualidad',
        'tipoPlan',
        'dispositivosSimultaneos',
        'estudioTrabajo',
        'reproduccionVideo',
        'juegoLinea',
        'transmisiones'
    ];
}