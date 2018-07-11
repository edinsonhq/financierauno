<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\finantienda;
use Carbon\Carbon;

class TarjetaCreditoController extends Controller
{   
    // tcEntregadas
    public function tcEntregadas($finantiendaId, $fechaCustom){

        try{
            //obteniendo data de finantienda
            $finantiendaDatos= $this->finantiendaShow($finantiendaId);

            if($fechaCustom == "null"){
                //obteniendo fecha actual
                $today = Carbon::now();//$today = new Carbon("2018-06-09");
                //buscando fecha con registros
                for($i=1;$i<15;$i++){
                    // obtener fecha atras
                    $diaAtrasGenerado = $today->subDay(1); 
                    //convirtiendo a formato deseado
                    $diaAtrasGeneradoFormat = $diaAtrasGenerado->format('Ymd');

                    // consultando registros
                   $resultado = $this->busquedaEntregadas($finantiendaId,$diaAtrasGeneradoFormat);

                   $cantidadRegistros = count($resultado);

                    if($cantidadRegistros>0){
                            // CALCULO DE PORCENTAJE
                            //if($cantidadRegistros>0){
                                $total=0;
                                foreach($resultado as $item){
                                    $total= $item->porcentaje_entregado + $total;
                                }
                                $porcentaje = $cantidadRegistros;
                           // }

                        return response()->json(['msg' => 'Consulta exitosa, tcEntregadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
                        break;
                    }else{
                        return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
                    }
                }                   
            }else{

                $resultado = $this->busquedaEntregadas($finantiendaId,$fechaCustom);
                $cantidadRegistros = count($resultado);
                // CALCULO DE PORCENTAJE
                if($cantidadRegistros>0){
                    $total=0;
                    $totalmeta=0;
                    foreach($resultado as $item){
                        $total= $item->nro_tc_entregado + $total;
                        $totalmeta=$item->ppto_entregado_diario_acumulado + $totalmeta;                        
                     }
                    //$porcentaje = $total/$cantidadRegistros;

                    return response()->json(['msg' => 'Consulta exitosa, tcEntregadas',
                                'rpta'=>$resultado, 
                                'porcentajeTotal' =>  $total/$totalmeta*100,
                                'totalmeta'=>round($totalmeta),
                                'finantiendaDatos' => $finantiendaDatos,
                                'success' => true], 201);
                }else{
                    $porcentaje=0;
                    return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
                }
                        
            }

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcEntregadas, ERROR!', 'success' => false], 201);
        } 

    }

    public function busquedaEntregadas($finantiendaId,$fechaFin){

         $primerDiaMesActual = $this->primerDiaMesActual();
         $fechaFin = '20180630';
         $pdmamu = $this->primerDiaMesActualMenosUno();

            $entregadas = DB::table('v_tarjetas_entregadas')            
            ->select(
                    'Ejecutivos as ejecutivo',
                     DB::raw("sum(Nro_TC_Entregado) AS nro_tc_entregado"),
                     DB::raw("sum(Ppto_Entregado) AS ppto_entregado_diario_acumulado"),
                     DB::raw("case when sum(Ppto_Entregado)<>0 then (sum(Nro_TC_Entregado)/sum(Ppto_Entregado))*100 else 0 end AS porcentaje_entregado")
             )
            ->where([
                ['Finantienda_key', '=', $finantiendaId],
                
            ])
            ->whereBetween('Fecha',['20180601',$fechaFin])
            ->groupBy('Ejecutivos')
            ->get();  

            // dd($entregadas);
            
                 // where CodigoCAI='090' AND Fecha between    '20180601' AND '20180704' and Ppto_Activado<>0
                 // group by Ejecutivos 




        return $entregadas;
    }

    //ACTIVADAS
    public function tcActivadas($finantiendaId, $fechaCustom){

        try{
            //obteniendo data de finantienda
            $finantiendaDatos= $this->finantiendaShow($finantiendaId);

            if($fechaCustom == "null"){
                //obteniendo fecha actual
                $today = Carbon::now();//$today = new Carbon("2018-06-09");
                //buscando fecha con registros
                for($i=1;$i<15;$i++){
                    // obtener fecha atras
                    $diaAtrasGenerado = $today->subDay(1); 
                    //convirtiendo a formato deseado
                    $diaAtrasGeneradoFormat = $diaAtrasGenerado->format('Ymd');

                    // consultando registros
                   $resultado = $this->busquedaActivadas($finantiendaId,$diaAtrasGeneradoFormat);

                   $cantidadRegistros = count($resultado);

                    if($cantidadRegistros>0){
                            // CALCULO DE PORCENTAJE
                            if($cantidadRegistros>0){
                                $total=0;
                                foreach($resultado as $item){
                                    $total= $item->porcentaje_activado + $total;
                                }
                                $porcentaje = $total/$cantidadRegistros;
                            }else{
                                $porcentaje=0;
                            }
                        return response()->json(['msg' => 'Consulta exitosa, tcActivadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
                        break;
                    }else{
                        return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
                    }
                }                   
            }else{

                $resultado = $this->busquedaActivadas($finantiendaId,$fechaCustom);
                $cantidadRegistros = count($resultado);
                // CALCULO DE PORCENTAJE
                if($cantidadRegistros>0){
                    $total=0;
                    $totalmeta=0;
                    foreach($resultado as $item){
                        $total= $item->nro_tc_Activada+ $total;
                        $totalmeta=$item->ppto_entregado_diario_acum+$totalmeta;
                    }
                    $porcentaje = $total/$cantidadRegistros;

                    return response()->json(['msg' => 'Consulta exitosa, tcActivadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => ($total/$totalmeta)*100,
                                    'totalmeta' => $totalmeta, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
                }else{
                    $porcentaje=0;
                    return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
                }
                        
            }

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcActivadas, ERROR!', 'success' => false], 201);
        } 

    }

    public function busquedaActivadas($finantiendaId,$fechaFin){

         $primerDiaMesActual = $this->primerDiaMesActual();
         $fechaFin = '20180630';
         $pdmamu = $this->primerDiaMesActualMenosUno();

        $activadas = DB::table('v_tarjetas_entregadas')            
        ->select(
                'Ejecutivos as ejecutivo',
                 DB::raw("sum(Nro_TC_Activada) AS nro_tc_Activada"),
                 DB::raw("sum(Ppto_Activado) AS ppto_entregado_diario_acum"),
                 //DB::raw("round(sum(Nro_TC_Activada)/sum(Ppto_Activado)*100,2) AS porcentaje_activado")
                 DB::raw("case when sum(Ppto_Activado)<>0 then round(sum(Nro_TC_Activada)/sum(Ppto_Activado)*100,2) else 0 end AS porcentaje_activado")         )
        ->where([
            ['Finantienda_key', '=', $finantiendaId],
                    ])
        ->whereBetween('Fecha',['20180601',$fechaFin])
        ->groupBy('Ejecutivos')
        ->get(); 

        return $activadas;





         
    }

    // tcIngresadas
    // public function tcIngresadas($finantiendaId, $fechaCustom){

    //     try{
    //         //obteniendo data de finantienda
    //         $finantiendaDatos= $this->finantiendaShow($finantiendaId);

    //         if($fechaCustom == "null"){
    //             //obteniendo fecha actual
    //             $today = Carbon::now();//$today = new Carbon("2018-06-09");
    //             //buscando fecha con registros
    //             for($i=1;$i<15;$i++){
    //                 // obtener fecha atras
    //                 $diaAtrasGenerado = $today->subDay(1); 
    //                 //convirtiendo a formato deseado
    //                 $diaAtrasGeneradoFormat = $diaAtrasGenerado->format('Ymd');

    //                 // consultando registros
    //                $resultado = $this->busquedaIngresadas($finantiendaId,$diaAtrasGeneradoFormat);

    //                $cantidadRegistros = count($resultado);

    //                 if($cantidadRegistros>0){
    //                         // CALCULO DE PORCENTAJE
    //                         if($cantidadRegistros>0){
    //                             $total=0;
    //                             foreach($resultado as $item){
    //                                 $total= $item->porcentaje_ingresado + $total;
    //                             }
    //                             $porcentaje = $total/$cantidadRegistros;
    //                         }else{
    //                             $porcentaje=0;
    //                         }
    //                     return response()->json(['msg' => 'Consulta exitosa, tcIngresadas',
    //                                 'rpta'=>$resultado, 
    //                                 'porcentajeTotal' => $porcentaje, 
    //                                 'finantiendaDatos' => $finantiendaDatos,
    //                                 'success' => true], 201);
    //                     break;
    //                 }else{
    //                     return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
    //                 }
    //             }                   
    //         }else{

    //             $resultado = $this->busquedaIngresadas($finantiendaId,$fechaCustom);
    //             $cantidadRegistros = count($resultado);
    //             // CALCULO DE PORCENTAJE
    //             if($cantidadRegistros>0){
    //                 $total=0;
    //                 foreach($resultado as $item){
    //                     $total= $item->porcentaje_ingresado + $total;
    //                 }
    //                 $porcentaje = $total/$cantidadRegistros;

    //                 return response()->json(['msg' => 'Consulta exitosa, tcIngresadas',
    //                                 'rpta'=>$resultado, 
    //                                 'porcentajeTotal' => $porcentaje, 
    //                                 'finantiendaDatos' => $finantiendaDatos,
    //                                 'success' => true], 201);
    //             }else{
    //                 $porcentaje=0;

    //                 return response()->json(['msg' => 'No hay Registros, ERROR!', 'success' => false], 201);
    //             }
                        
    //         }

    //     } catch(\Exception $e){
    //         return response()->json(['msg' => 'tcIngresadas, ERROR!', 'success' => false], 201);
    //     } 

    // }

    // public function busquedaIngresadas($finantiendaId,$fechaFin){

    //      $primerDiaMesActual = $this->primerDiaMesActual();
    //      $pdmamu = $this->primerDiaMesActualMenosUno();
    //      //$fechaFin = '20180621';
    //     $ingresadas = DB::table('v_ingresadas')            
    //         ->select(
    //                     'Ejecutivos as ejecutivo',
    //                  DB::raw("SUM (cast (Nro_Ingreso as  float) ) AS nro_ingreso"),
    //                  DB::raw("round(Ppto_Ingresado_Diario * DATEDIFF(DAY,'20180531','20180621'),0) AS ppto_ingresado_diario_acumulado"),
    //                  DB::raw("round((sum (cast (Nro_Ingreso as  float))/(Ppto_Ingresado_Diario * DATEDIFF(DAY,'20180531','20180621')))*100,2) AS porcentaje_ingresado")                  
    //          )
    //         ->where('Finantienda_key', '=', $finantiendaId)
    //         ->whereBetween('Fecha',['20180601',$fechaFin])
    //         ->groupBy('Ejecutivos','Ppto_Ingresado_Diario')
    //         ->get();  

    //     return $ingresadas;
    // }

public function tcActivadasSupervisor($finantiendaId,$fechaFin){

       try{


        $primerDiaMesActual = $this->primerDiaMesActual();
        //$fechaFin = '20180621';
        $pdmamu = $this->primerDiaMesActualMenosUno();

       $supervisor = DB::table('v_tarjetas_entregadas')            
       ->select(
               'Supervisor as supervisor',
                DB::raw("sum(Nro_TC_Activada_supervisor) AS nro_tc_activada_supervisor"),
                DB::raw("round(sum(Ppto_Activado),0) AS ppto_activada_diario_acumulado_supervisor"),
                DB::raw("round(sum(Nro_TC_Activada_supervisor)/sum(Ppto_Activado)*100,2) AS porcentaje_activada")
        )
       ->where([
           ['Finantienda_key', '=', $finantiendaId],
       ])
       ->whereBetween('Fecha',['20180601',$fechaFin])
       ->groupBy('supervisor')
       ->get();              
              
       return $supervisor;

       } catch(\Exception $e){
           return response()->json(['msg' => 'tarjetas_ingresadas, ERROR!', 'success' => false], 201);
       } 
        
   }



    public function tcIngresadas($finantiendaId){
        try{
      
            $finantiendaDatos= $this->finantiendaShow($finantiendaId);

            // obteniendo mes actual
            $datos_fecha_actual =  $this->datos_fecha_actual();
            $dia_actual =  $this->diaActual();



            $dias = (string)$datos_fecha_actual['nro_dias'];
            $mes = (string)$datos_fecha_actual['mes'];
            $anio = (string)$datos_fecha_actual['anio'];


            $fecha_actual = $dia_actual."-".$mes."-".$anio;

            // valores a enviar al procedimiento
            $valores = [$dias,$mes,$anio,$finantiendaId];
            // dd($valores);
            // llamando al procedimiento
             $ejecutivos_calendario = DB::select("EXEC [dbo].[sp_tarjetas_ingresadas] ?,?,?,?", $valores);

            return response()->json(['msg' => 'Consulta exitosa',
                                    'rpta' => $ejecutivos_calendario, 
                                    'mes' => $mes, 
                                    'fechaActual' => $fecha_actual,
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);

        } catch(\Exception $e){
            return response()->json(['msg' => 'tarjetas_ingresadas, ERROR!', 'success' => false], 201);
        } 
    }


public function datos_fecha_actual(){
    //obteniendo fecha actual para coger la cantidad de dias que contiene el mes
    // $fechaActual = Carbon::now();
    $fechaActual = Carbon::parse('2018-06-01');

    $diasMesActual = $fechaActual->daysInMonth;
    $mesActual = str_pad($fechaActual->month ,2,"0",STR_PAD_LEFT);
    $anioActual = $fechaActual->year;

 
    // $primerDiaMesPasado = new Carbon('first day of last month');
    // $primerDiaMesPasadoClean = $primerDiaMesPasado->format("Ymd");

    $datos_fecha_actual = [
        "nro_dias" => $diasMesActual,
        "mes" => $mesActual,
        "anio" => $anioActual,
    ];
   
    // $datos_fecha_actual['dia_mes_Actual'];

    return $datos_fecha_actual;
}



    public function finantiendas(){

        //$finantiendas=Finantienda::select('Finantienda_key','Finantienda')->orderBy('Finantienda','asc')->get();
        //$finantiendas=Finantienda::select(DB::raw("right('00'+codigoCai,3) as Finantienda_id"),'Finantienda')->get();
        $finantiendas=Finantienda::select('Finantienda_key','Finantienda','CodigoCAI')->get();

        try{        
                return response()->json(['msg' => 'Finantiendas Listado, EXITO!','rpta'=>$finantiendas, 'success' => true], 201);
        } catch(\Exception $e){
                return response()->json(['msg' => 'Finantiendas Listado, ERROR!', 'success' => false], 201);
        }
    }


    public function finantiendaShow($id){

        $finantienda = Finantienda::select('Finantienda_key','Finantienda','CodigoCAI')->where('Finantienda_key','=',$id)->first();

        $finantiendaDatos = [
            "finantiendaNombre" => $finantienda->Finantienda,
            // "finantiendaId" => $finantienda->Finantienda_key,
            "finantiendaId" => str_pad($finantienda->Finantienda_key ,3,"0",STR_PAD_LEFT),
        ];

        return $finantiendaDatos;
    }

    public function primerDiaMesPasado(){
  
        $primerDiaMesPasado = new Carbon('first day of last month');
        $primerDiaMesPasadoClean = $primerDiaMesPasado->format("Ymd");
        return $primerDiaMesPasadoClean;
    }

    public function ultimoDiaMesPasado(){
        /*
        $ultimoDiaMesPasado = new Carbon('last day of last month');
        //extraendo sólo caracteres que nos interesan
        $ultimoDiaMesPasadoFormat = substr($ultimoDiaMesPasado,0,11);
        //reemplazando los guiones por espacios nulos
        $ultimoDiaMesPasadoClean=str_replace('-', '', $ultimoDiaMesPasadoFormat);*/
        $ultimoDiaMesPasado = new Carbon('last day of last month');
        $ultimoDiaMesPasadoClean = $ultimoDiaMesPasado->format("Ymd");
        return $ultimoDiaMesPasadoClean;
    }

    public function diaAtras(){

        $diaAnteriorLaborable = "";
        //obteniendo fecha de ayer
        $today = Carbon::now();
        //$diaAtras = new Carbon('last day');
        $diaAtras = $today->subDay(1); 
        //convirtiendo a formato deseado
        $diaAtrasFormat = $diaAtras->format('Ymd');
        // obteniendo nombre del dia en Texto
        $diaAtras = $diaAtras->formatLocalized('%A');

        if($diaAtras=='Sunday'){
            $diaAnteriorLaborable = $this->ultimoViernesPasado();
        }else{
            $diaAnteriorLaborable= $diaAtrasFormat;
        }

       return $diaAnteriorLaborable;

    }

    public function diaActual(){

        // setlocale(LC_TIME, 'English');
        // Carbon::setUtf8(true);
        // //obteniendo fecha de ayer
        $today = Carbon::now();
        // $dia_actual=$today->day;
        $dia_actual = str_pad($today->day,2,"0",STR_PAD_LEFT);;

        return $dia_actual;
    }

    public function primerDiaMesActual(){

        $today = new Carbon('first day of this month');
        $primerDiaMesActual = $today->format('Ymd');        
        return $primerDiaMesActual;
    }

    public function primerDiaMesActualMenosUno(){

        $today = new Carbon('first day of this month');
        $pdma = $today->subDay(1); 
        $pdmaFormat = $pdma->format('Ymd');        
        return $pdmaFormat;
    }

    public function ultimoViernesPasado(){
        $ultimoViernesPasado = new Carbon('last friday');
        $ultimoViernesPasadoFormat = $ultimoViernesPasado->format('Ymd');        
        return $ultimoViernesPasadoFormat;
    }

}
