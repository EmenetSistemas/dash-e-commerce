<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblDirecciones extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tblDirecciones';
    protected $fillable = 
    [
        'fkTblUsuario',
	    'calle',
	    'noExterior',
	    'localidad',
	    'municipio',
	    'estado',
	    'cp',
        'referencias'
    ];
}