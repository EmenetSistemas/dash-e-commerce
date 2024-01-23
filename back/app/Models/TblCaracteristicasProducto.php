<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblCaracteristicasProducto extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblCaracteristicaProducto';
    protected $table = 'tblCaracteristicasProducto';
    protected $fillable = 
    [
        'pkTblCaracteristicaProducto',
        'fkTblProducto',
        'titulo',
        'descripcion'
    ];
}