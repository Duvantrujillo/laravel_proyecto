<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GruposPersonalController;
use App\Http\Controllers\RegisterPersonalController;
use App\Http\Controllers\EntradaSalidaPersonalController;
use App\Http\Controllers\observationcontroller;
use App\Http\Controllers\GeoPondcontroller;
use App\Http\Controllers\MortalityController;
use App\Http\Controllers\PondUnitCodeController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\SowingController;
use App\Http\Controllers\DietMonitoringController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Auth\LoginController;



// Ruta principal
Route::get('/', function () {
    return view('auth.login');
})->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
// Autenticación
Auth::routes(['middleware' => 'role.redirect']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboards
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('auth.admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:usuario'])->group(function () {
    Route::get('/user', function () {
        return view('auth.user.dashboard');
    })->name('user.dashboard');
});

Route::get('/dashboard', function () {
    return view('auth.user.Dashboard');
});

// Vistas directas
Route::get('/grupopersonal', function () {
    return view('auth.user.r-personal.r-grupo.form');
})->name('grupo-form');

Route::get('/registerperosnal', function () {
    return view('auth.user.r-personal.r-personal');
})->name('register-personal');

Route::get('/entrada-salida', function () {
    return view('auth.user.r-personal.r-entrada-salida.r-entrada-salida');
})->name('entrada-salida');

// Grupos y Fichas
Route::get('/grupos/create', [GruposPersonalController::class, 'create'])->name('grupo.create');
Route::post('/grupos', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.store');
Route::post('/fichas', [GruposPersonalController::class, 'storeFicha'])->name('ficha.store');
Route::post('/grupo/store-grupo', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.storeGrupo');
Route::post('/grupo/store-ficha', [GruposPersonalController::class, 'storeFicha'])->name('grupo.storeFicha');
Route::post('/check-grupo-nombre', [GruposPersonalController::class, 'checkNombre'])->name('check.grupo.nombre');
Route::post('/check-numero-ficha', [GruposPersonalController::class, 'checkNumeroFicha'])->name('check.numero.ficha');
Route::get('/get-fichas', [GruposPersonalController::class, 'getFichas'])->name('get.fichas');

// Registro de Personal
Route::get('registre/create', [RegisterPersonalController::class, 'create'])->name('register.create');
Route::post('registre/store', [RegisterPersonalController::class, 'store'])->name('register.store');
Route::get('/get-fichas', [RegisterPersonalController::class, 'getFichas'])->name('getFichas');
Route::post('/check-numero-documento', [RegisterPersonalController::class, 'checkNumeroDocumento'])->name('check.numero.documento');
Route::get('/grupos-fichas', [RegisterPersonalController::class, 'indexGruposFichas'])->name('grupos-fichas.index');
Route::get('/personal-filtrado', [RegisterPersonalController::class, 'filtrarPersonal'])->name('personal.filtrado');

// Entrada y Salida de Personal
Route::get('entradasalida/create', [EntradaSalidaPersonalController::class, 'create'])->name('entrada_salida.create');
Route::post('entradasalida/store', [EntradaSalidaPersonalController::class, 'store'])->name('entrada_salida.store');
Route::get('/entradas-salidas-filtradas', [EntradaSalidaPersonalController::class, 'filtrarPorGrupo'])->name('entradas_salidas.filtradas');

Route::get('/entrada-salida/create', [EntradaSalidaPersonalController::class, 'create'])->name('entrada_salida.create');
Route::post('/entrada-salida/store', [EntradaSalidaPersonalController::class, 'store'])->name('entrada_salida.store');
Route::get('/get-fichas-por-grupo', [EntradaSalidaPersonalController::class, 'getFichasPorGrupo']);
Route::get('/get-usuarios-por-ficha', [EntradaSalidaPersonalController::class, 'getUsuariosPorFicha']);
Route::get('/salida', [EntradaSalidaPersonalController::class, 'index'])->name('entrada_salida.index');
Route::post('/entrada-salida/actualizar-fecha-salida', [EntradaSalidaPersonalController::class, 'actualizarFechaSalida'])->name('entrada_salida.actualizar_fecha_salida');

// Observaciones
Route::get('/observacion/create', [observationController::class, 'create'])->name('observacion.create');
Route::post('/observacion/store', [observationcontroller::class, 'store'])->name('observacion.store');
Route::get('/observacion/filtro', [observationController::class, 'index'])->name('observacion.index');
Route::delete('/observacion/{id}', [observationController::class, 'destroy'])->name('observacion.destroy');
Route::get('/observacion/{id}/edit', [observationcontroller::class, 'edit'])->name('observacion.editar');
Route::put('/observacion/{id}/edit', [observationcontroller::class, 'update'])->name('observacion.update');

// Geolocalización de Estanques
Route::get('/geo-estanque/form', [GeoPondcontroller::class, 'create'])->name('geo.create');
Route::post('/geo-estanque', [GeoPondcontroller::class, 'store'])->name('geo.store');

// Pons (unidad de código por estanque)
Route::post('/pons/store', [PondUnitCodeController::class, 'store'])->name('pond.store');
Route::get('/geo-estanque/filter', [PondUnitCodeController::class, 'index'])->name('geo.index');




// tasa de mortalidad
// Mortalidad
Route::get('/mortality/create', [MortalityController::class, 'create'])->name('mortality.create');
Route::post('/mortality/store', [MortalityController::class, 'store'])->name('mortality.store');
Route::get('/get-ponds-by-pond-id', [MortalityController::class, 'getPondsByPondId'])->name('mortality.getPondsByPondId');
// Ajax para traer los identificadores según el tipo de unidad



Route::get('/mortality/get-ponds', [MortalityController::class, 'index'])->name('mortality.index');



Route::get('/obtener-fichas/{grupoId}', [EntradaSalidaPersonalController::class, 'obtenerFichas']);

Route::get('/mortality/get-sowing-data', [MortalityController::class, 'getSowingData'])->name('mortality.getSowingData');







Route::get('/species', [SpeciesController::class, 'index'])->name('species.index');
Route::get('/species/create', [SpeciesController::class, 'create'])->name('species.create');
Route::post('/species/store', [SpeciesController::class, 'store'])->name('species.store');
Route::delete('/species/{species}', [SpeciesController::class, 'destroy'])->name('species.destroy');


Route::post('species/type', [SpeciesController::class, 'storeType'])->name('species.storeType');
Route::put('/species/{species}', [SpeciesController::class, 'update'])->name('species.update');
Route::put('species/type/{type}', [SpeciesController::class, 'updateType'])->name('species.updateType');


Route::put('/types/update/{type}', [SpeciesController::class, 'updateType'])->name('types.update');
Route::get('/types/create', [TypeController::class, 'create'])->name('types.create');
Route::post('/types/store', [TypeController::class, 'store'])->name('types.store');
Route::delete('/types/{type}', [TypeController::class, 'destroy'])->name('types.destroy');














Route::get('/siembras/create', [SowingController::class, 'create'])->name('siembras.create');
Route::post('/siembras', [SowingController::class, 'store'])->name('siembras.store');

// Rutas para AJAX
Route::get('/get-tipos/{especieId}', [SowingController::class, 'getTypes']);
Route::get('/get-identificadores/{pondId}', [SowingController::class, 'getIdentifiers']);












Route::get('/diet_monitoring', [DietMonitoringController::class, 'create'])->name('diet_monitoring.create'); //ruta para la vista del formulario
Route::get('seguimiento-dieta/{sowing_id}', [DietMonitoringController::class, 'index'])->name('diet_monitoring.index');

Route::post('/seguimiento-dieta', [DietMonitoringController::class, 'store'])->name('diet_monitoring.store');


Route::get('/sowing/{sowing}/diet-monitoring', [App\Http\Controllers\DietMonitoringController::class, 'showBySowing'])
    ->name('sowing.diet_monitoring');


Route::post('/sowing/{id}/finish', [SowingController::class, 'finish'])->name('sowing.finish');













Route::resource('loans', LoanController::class);
Route::resource('returns', ReturnController::class);




Route::post('/sowing/{id}/finish', [DietMonitoringController::class, 'finish'])->name('sowing.finish');
Route::get('/seguimientos/terminados', [DietMonitoringController::class, 'terminated'])->name('diet_monitoring.terminated');







Route::get('visitors-index', function () {
    return redirect()->route('visitors.index');
});

Route::resource('visitors', VisitorController::class)->only([
    'index',
    'create',
    'store'
]);

// Nueva vista para actualizar hora de salida

Route::get('/visitors/checkout', [VisitorController::class, 'checkoutForm'])->name('visitors.checkout.form');
Route::post('/visitors/checkout', [VisitorController::class, 'updateCheckout'])->name('visitors.checkout.update');


// Filtro por fecha
Route::get('/visitors/filter', [VisitorController::class, 'filter'])->name('visitors.filter');


// Formulario público
Route::get('/registro-visitante', [VisitorController::class, 'publicCreate'])->name('visitors.public.create');
Route::post('/registro-visitante', [VisitorController::class, 'publicStore'])->name('visitors.public.store');


Route::post('/config/registro-publico/toggle', [VisitorController::class, 'toggleFormState'])->name('visitors.toggle');




use App\Http\Controllers\WaterQualityController;


Route::get('/water-quality/create/{sowing}', [WaterQualityController::class, 'create'])->name('water_quality.create');
Route::post('/water-quality/store/{sowing}', [WaterQualityController::class, 'store'])->name('water_quality.store');

Route::get('/water-quality/history/{sowing}', [WaterQualityController::class, 'history'])


    ->name('water_quality.history');

use App\Http\Controllers\UserController;

Route::resource('users', UserController::class);
