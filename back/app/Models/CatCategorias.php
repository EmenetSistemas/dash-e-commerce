<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatCategorias extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkCatCategoria';
    protected $table = 'catCategorias';
    protected $fillable = 
    [
        'pkCatCategoria',
        'nombre',
        'descripcion'
    ];
}