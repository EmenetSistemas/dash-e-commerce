<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblProductos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblProducto';
    protected $table = 'tblProductos';
    protected $fillable = 
    [
        'pkTblProducto',
        'identificador_mbp',
        'nombre',
        'imagen',
        'precio',
        'descuento',
        'fkCatApartado',
        'calificacion',
        'calificaciones',
        'descripcion',
        'stock',
        'fechaAlta'
    ];
}