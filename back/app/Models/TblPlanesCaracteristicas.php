<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPlanesCaracteristicas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkPlanCaracteristica';
    protected $table = 'tblPlanesCaracteristicas';
    protected $fillable = 
    [
        'pkPlanCaracteristica',
        'fkTblPlan',
        'fkCatCaracteristica'
    ];
}