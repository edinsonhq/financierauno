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
                            if($cantidadRegistros>0){
                                $total=0;
                                foreach($resultado as $item){
                                    $total= $item->porcentaje_entregado + $total;
                                }
                                $porcentaje = $total/$cantidadRegistros;
                            }
                        return response()->json(['msg' => 'Consulta exitosa, tcEntregadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
                        break;
                    }else{

                    }
                }                   
            }else{

                $resultado = $this->busquedaEntregadas($finantiendaId,$fechaCustom);
                $cantidadRegistros = count($resultado);
                // CALCULO DE PORCENTAJE
                if($cantidadRegistros>0){
                    $total=0;
                    foreach($resultado as $item){
                        $total= $item->porcentaje_entregado + $total;
                    }
                    $porcentaje = $total/$cantidadRegistros;
                }else{
                    $porcentaje=0;
                }
                        return response()->json(['msg' => 'Consulta exitosa, tcEntregadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
            }

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcEntregadas, ERROR!', 'success' => false], 201);
        } 

    }

    public function busquedaEntregadas($finantiendaId,$fechaFin){

         $primerDiaMesActual = $this->primerDiaMesActual();
         //$fechaFin = '20180621';
         $pdmamu = $this->primerDiaMesActualMenosUno();

        $entregadas = DB::table('v_entregadas')            
        ->select(
                    'Ejecutivos as ejecutivo',
                     DB::raw("SUM(cast (Nro_TC_Entregado as  float) ) AS nro_tc_entregado"),
                     DB::raw("Ppto_Entregado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."') AS ppto_entregado_diario_acumulado"),
                     DB::raw("ROUND((SUM(cast (Nro_TC_Entregado as  float))/ (Ppto_Entregado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."')))*100,2) AS porcentaje_entregado")
             )
            ->where('Finantienda_key', '=', $finantiendaId)
            // ->where([
            //        ['Finantienda_key', '=', $finantiendaId],
            //        ['cod_asesores', '=', '00000022872'],
            //     ])
            ->whereBetween('Fecha',[$primerDiaMesActual,$fechaFin])
            ->groupBy('Ejecutivos','Ppto_Entregado_Mensual','Ppto_Part_Activada','Ppto_Entregado_Diario')
            ->get();  

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

                    }
                }                   
            }else{

                $resultado = $this->busquedaActivadas($finantiendaId,$fechaCustom);
                $cantidadRegistros = count($resultado);
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
            }

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcActivadas, ERROR!', 'success' => false], 201);
        } 

    }

    public function busquedaActivadas($finantiendaId,$fechaFin){

         $primerDiaMesActual = $this->primerDiaMesActual();
         //$fechaFin = '20180621';
         $pdmamu = $this->primerDiaMesActualMenosUno();

        $entregadas = DB::table('v_entregadas')            
            ->select(
                    'Ejecutivos as ejecutivo',
                     DB::raw("sum (cast (Nro_TC_Activada as float) ) as nro_tc_Activada"),
                     DB::raw("Ppto_Entregado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."') as ppto_entregado_diario_acum"),
                     DB::raw("round((sum (cast (Nro_TC_Activada as  float))/ (Ppto_Entregado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."')))*100,2) as porcentaje_activado")
             )
            ->where('Finantienda_key', '=', $finantiendaId)
            // ->where([
            //        ['Finantienda_key', '=', $finantiendaId],
            //        ['cod_asesores', '=', '00000022872'],
            //     ])
            ->whereBetween('Fecha',[$primerDiaMesActual,$fechaFin])
            ->groupBy('Ejecutivos','Ppto_Entregado_Mensual','Ppto_Part_Activada','Ppto_Entregado_Diario')

            ->get();  

        return $entregadas;
    }

    // tcIngresadas
    public function tcIngresadas($finantiendaId, $fechaCustom){

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
                   $resultado = $this->busquedaIngresadas($finantiendaId,$diaAtrasGeneradoFormat);

                   $cantidadRegistros = count($resultado);

                    if($cantidadRegistros>0){
                            // CALCULO DE PORCENTAJE
                            if($cantidadRegistros>0){
                                $total=0;
                                foreach($resultado as $item){
                                    $total= $item->porcentaje_ingresado + $total;
                                }
                                $porcentaje = $total/$cantidadRegistros;
                            }else{
                                $porcentaje=0;
                            }
                        return response()->json(['msg' => 'Consulta exitosa, tcIngresadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
                        break;
                    }else{

                    }
                }                   
            }else{

                $resultado = $this->busquedaIngresadas($finantiendaId,$fechaCustom);
                $cantidadRegistros = count($resultado);
                // CALCULO DE PORCENTAJE
                if($cantidadRegistros>0){
                    $total=0;
                    foreach($resultado as $item){
                        $total= $item->porcentaje_ingresado + $total;
                    }
                    $porcentaje = $total/$cantidadRegistros;
                }else{
                    $porcentaje=0;
                }
                        return response()->json(['msg' => 'Consulta exitosa, tcIngresadas',
                                    'rpta'=>$resultado, 
                                    'porcentajeTotal' => $porcentaje, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);
            }

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcIngresadas, ERROR!', 'success' => false], 201);
        } 

    }

    public function busquedaIngresadas($finantiendaId,$fechaFin){

         $primerDiaMesActual = $this->primerDiaMesActual();
         $pdmamu = $this->primerDiaMesActualMenosUno();
         //$fechaFin = '20180621';
        $ingresadas = DB::table('v_ingresadas')            
            ->select(
                        'Ejecutivos as ejecutivo',
                     DB::raw("SUM (cast (Nro_Ingreso as  float) ) AS nro_ingreso"),
                     DB::raw("round(Ppto_Ingresado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."'),0) AS ppto_ingresado_diario_acumulado"),
                     DB::raw("round((sum (cast (Nro_Ingreso as  float))/(Ppto_Ingresado_Diario * DATEDIFF(DAY,'".$pdmamu."','".$fechaFin."')))*100,2) AS porcentaje_ingresado")                  
             )
            ->where('Finantienda_key', '=', $finantiendaId)
            ->whereBetween('Fecha',[$primerDiaMesActual,$fechaFin])
            ->groupBy('Ejecutivos','Ppto_Ingresado_Diario')
            ->get();  

        return $ingresadas;
    }



    public function tarjetas_ingresadas($finantiendaId){
        try{
      
           $finantiendaDatos= $this->finantiendaShow($finantiendaId);

            // obteniendo mes actual
            $datos_fecha_actual =  $this->datos_fecha_actual();

            $dias = $datos_fecha_actual['nro_dias'];
            // $mes = $datos_fecha_actual['mes'];
            $mes = "06";
            $anio = $datos_fecha_actual['anio'];

            // valores a enviar al procedimiento
            $valores = [$dias,$mes,$anio,$finantiendaId];
            // llamando al procedimiento
            $ejecutivos_calendario = DB::select("EXEC [dbo].[sp_tarjetas_ingresadas] ?,?,?,?", $valores);

            return response()->json(['msg' => 'Consulta exitosa',
                                    'rpta' => $ejecutivos_calendario, 
                                    'mes' => $mes, 
                                    'finantiendaDatos' => $finantiendaDatos,
                                    'success' => true], 201);

        } catch(\Exception $e){
            return response()->json(['msg' => 'tcIngresadas, ERROR!', 'success' => false], 201);
        } 
    }






















public function datos_fecha_actual(){

    $fechaActual = Carbon::now();
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
            "finantiendaId" => $finantienda->Finantienda_key,
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

    public function diaActual($fecha){

         setlocale(LC_TIME, 'English');
        // Carbon::setUtf8(true);
        // //obteniendo fecha de ayer
        // $today = Carbon::now();
        // $diaActual=$fecha->format('d-m-Y');
        // $diaActual=$today->formatLocalized('%A %d %B %Y');
        return $diaActual;
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
