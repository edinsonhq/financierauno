<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\finantienda;
use Carbon\Carbon;

class CrediventaController extends Controller
{
    public function crediventaParticipacion(){

        try{

            $crediventaParticipacion = DB::table('CVTPSA.Fact_VentaOH AS A')
            ->join('Dim_Tiempo AS B', 'A.Tiempo_Key', '=', 'B.Tiempo_Key')
            ->join('Dim_Local AS C ', 'A.Local_Key', '=', 'C.Local_Key')
            ->join('Dim_Division AS D', 'A.Division_Key', '=', 'D.Division_Key')
            ->join('Dim_Area AS E', 'A.Area_Key', '=', 'E.Area_Key')
            ->join('Dim_Departamento AS F', 'A.Departamento_key', '=', 'F.Departamento_key')
            ->join('CVTPSA.Dim_Jefe_Comercial AS G', 'A.Jefe_Comercial_key', '=', 'G.Jefe_Comercial_key')
            ->join('CVTPSA.Dim_Gerente_Ventas AS H', 'A.Gerente_Venta_key', '=', 'H.Gerente_Venta_key')
            ->join('CVTPSA.Dim_Gerente_Tienda AS I', 'A.Gerente_Tienda_key', '=', 'I.Gerente_Tienda_key')
            ->select('B.Tiempo_Key',
                     'B.Fecha',
                     'C.Local_Key',
                     'C.Local',
                     'H.Gerente_Venta_key',
                     'H.Gerente_Venta',
                     'G.Jefe_Comercial_key',
                     'G.Jefe_Comercial',
                    DB::raw('SUM(A.Unidades) Unidades'),
                    DB::raw('SUM(A.Venta) Venta'),
                    DB::raw("SUM(A.VentaOh) VentaOh"),
                    DB::raw("SUM(TOh) TOh")
                    )
            ->where([
                ['C.Local_Key', '=', 7],
                ['h.Gerente_Venta_key', '=', 42],
            ])
            ->whereBetween('B.Fecha',["20180605","20180605"])
            ->groupBy('B.Tiempo_Key', 'B.Fecha','C.Local_Key',
                        'C.Local','G.Jefe_Comercial_key','G.Jefe_Comercial',
                        'H.Gerente_Venta_key','H.Gerente_Venta','I.Gerente_Tienda_key',
                        'I.Gerente_Tienda')
            ->get();  
       
                
            return response()->json(['msg' => 'Consulta exitosa',
                                    'rpta' =>  $crediventaParticipacion,
                                    'success' => true], 201);
                                    
        }catch(\Exception $e){

            return response()->json(['msg' => 'error al iniciar la consulta', 
                                    'success' => false], 201);
        }

        
    }

}
