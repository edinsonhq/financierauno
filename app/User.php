<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    
    protected $table = "WEB.Dim_Usuario";
    protected $primaryKey = 'Usuario_key';

    protected $fillable = [
        'DNI', 'Contraseña', 'Estado','Nombre'
    ];


    /*
     * The attributes that should be hidden for arrays.
  
     
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    */
}
