<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finantienda extends Model
{
    protected $table        = "RIESGOS.Dim_Finantienda";
    protected $primaryKey   = "Finantienda_key";// por defecto laravel busca id como campo  primary de la tabla 

    protected $fillable = [
        'Finantienda_key','Finantienda'
    ];
}
