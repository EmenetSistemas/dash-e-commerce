<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUsuariosTienda extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblUsuarioTienda';
    protected $table = 'tblUsuariosTienda';
    protected $fillable = 
    [
        'pkTblUsuarioTienda',
	    'nombre',
	    'aPaterno',
	    'aMaterno',
	    'telefono',
	    'correo',
	    'password',
        'token',
        'status',
	    'activo'
    ];
}