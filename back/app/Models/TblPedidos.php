<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPedidos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblPedido';
    protected $table = 'tblPedidos';
    protected $fillable = 
    [
        'pkTblPedido',
        'fkTblUsuarioTienda',
        'fechaPedido',
        'fechaEntregaEstimada',
        'fechaEnvio',
        'fechaEntrega',
        'fechaAlta',
        'fkStatus'
    ];
}