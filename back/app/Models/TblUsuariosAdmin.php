<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUsuariosAdmin extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblUsuarioAdmin';
    protected $table = 'tblUsuariosAdmin';
    protected $fillable = 
    [
        'pkTblUsuarioAdmin',
        'nombre',
        'aPaterno',
        'aMaterno',
        'correo',
        'password',
        'token',
        'fechaAlta',
        'activo'
    ];
}