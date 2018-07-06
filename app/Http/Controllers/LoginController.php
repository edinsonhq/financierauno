<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {

            $nick      =$request->input('email');
            $password   =$request->input('password');

        
            $user = User::where([
                                ['DNI', '=', $nick],
                                ['Contraseña', '=', $password]
                                ])->first();

    
            if($user<>null){

                return response()->json(['msg' => 'Usuario logueado con exito' , 'rpta' => $user ,'success' => true ], 201);
            }else{
                
                return response()->json(['msg' => 'Contraseña incorrecta','success' => false], 201);
            }
   }
}
