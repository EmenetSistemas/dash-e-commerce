<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblSesionesAdmin extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblSesion';
    protected $table = 'tblSesionesAdmin';
    protected $fillable = 
    [
        'pkTblSesion',
        'fkTblUsuarioAdmin',
        'token'
    ];
}