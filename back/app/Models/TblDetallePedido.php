<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblDetallePedido extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblDetallePedido';
    protected $table = 'tblDetallePedido';
    protected $fillable = 
    [
        'pkTblDetallePedido',
        'fkTblPedido',
        'fkTblProducto',
        'cantidad'
    ];
}