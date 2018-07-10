<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//FINANTIENDAS
Route::get('finantiendas/',array(
	'uses'	=>'TarjetaCreditoController@finantiendas',
));


//TARJETAS CREDITO ACTIVADAS
Route::get('tcActivadasDiario/{finantiendaId}/{fechaCustom}',array(
	'uses'	=>'TarjetaCreditoController@tcActivadasDiario',
));
Route::get('tcActivadasAcumuladas/{finantiendaId}/{fechaCustomInicio}/{fechaCustomFin}',array(
	'uses'	=>'TarjetaCreditoController@tcActivadasAcumuladas',
));




Route::get('tcEntregadas/{finantiendaId}/{fechaCustomInicio}',array(
	'uses'	=>'TarjetaCreditoController@tcEntregadas',
));

// Route::get('tcIngresadas/{finantiendaId}/{fechaCustomInicio}',array(
// 	'uses'	=>'TarjetaCreditoController@tcIngresadas',
// ));

Route::get('tcActivadas/{finantiendaId}/{fechaCustomInicio}',array(
	'uses'	=>'TarjetaCreditoController@tcActivadas',
));




Route::get('tarjetas_ingresadas/{finantiendaId}',array(
	'uses'	=>'TarjetaCreditoController@tcIngresadas',
));

//ACTIVADAS SUPERVISOR
Route::get('tcActivadasSupervisor/{finantiendaId}/{fechaCustom}',array(
	'uses'	=>'TarjetaCreditoController@tcActivadasSupervisor',
));




Route::get('datos_fecha_actual',array(
	'uses'	=>'TarjetaCreditoController@datos_fecha_actual',
));

























// //LOGIN
// Route::get('login',array(
// 	'uses'	=>'LoginController@login',
// ));



// Route::post('login','LoginController@login');
// Route::post('logout','LoginController@logout');



























//TARJETA CREDITO



// CREDIVENTA
Route::get('crediventaParticipacion/',array(
	'uses'	=>'CrediventaController@crediventaParticipacion',
));













// PRUEBA DE FUNCIONES

Route::get('/ultimoDiaMesPasado',array(
	'uses'	=>'WebServicesController@ultimoDiaMesPasado',
));

Route::get('/primerDiaMesPasado',array(
	'uses'	=>'WebServicesController@primerDiaMesPasado',
));

Route::get('/diaActual',array(
	'uses'	=>'WebServicesController@diaActual',
));

Route::get('/diaAtras',array(
	'uses'	=>'WebServicesController@diaAtras',
));

Route::get('/primerDiaMesActual',array(
	'uses'	=>'WebServicesController@primerDiaMesActual',
));

Route::get('/ultimoViernesPasado',array(
	'uses'	=>'WebServicesController@ultimoViernesPasado',
));
// FIN PRUEBA DE FUNCIONES





