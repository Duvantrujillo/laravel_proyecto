<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GruposPersonalController;
use App\Http\Controllers\RegisterPersonalController;
use App\Http\Controllers\EntradaSalidaPersonalController;
use App\Http\Controllers\Toolcontroller;
use App\Http\Controllers\GeoPondcontroller;
use App\Http\Controllers\FeedRecordController;
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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WaterQualityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignedSowingController;
use App\Http\Controllers\GraficController;
use App\Models\Tool;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (sin necesidad de estar logueado)
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('auth.login'))->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Autenticación básica
Auth::routes(['middleware' => 'role.redirect']);

// Formulario público de registro de visitantes
Route::get('/registro-visitante', [VisitorController::class, 'publicCreate'])->name('visitors.public.create');
Route::post('/registro-visitante', [VisitorController::class, 'publicStore'])->name('visitors.public.store');

// Vista bloqueado (login_lock)
Route::get('/bloqueado', fn() => view('auth.login_lock.login_lock'))->name('bloqueado');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren login y rol admin o pasante)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,pasante'])->group(function () {

    // Dashboards
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', fn() => view('auth.admin.dashboard'))->name('admin.dashboard');
    });
    Route::middleware('role:pasante')->group(function () {
        Route::get('/pasante', fn() => view('auth.intern.dashboard'))->name('pasante.dashboard');
    });
    Route::get('/dashboard', fn() => view('auth.admin.Dashboard'));

    // Vistas directas
    Route::get('/grupopersonal', fn() => view('auth.admin.r-personal.r-grupo.form'))->name('grupo-form');
    Route::get('/registerperosnal', fn() => view('auth.admin.r-personal.r-personal'))->name('register-personal');
    Route::get('/entrada-salida', fn() => view('auth.admin.r-personal.r-entrada-salida.r-entrada-salida'))->name('entrada-salida');

    // Grupos y Fichas
    Route::get('/grupos/create', [GruposPersonalController::class, 'create'])->name('grupo.create');
    Route::post('/grupos', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.store');
    Route::post('/fichas', [GruposPersonalController::class, 'storeFicha'])->name('ficha.store');
    Route::post('/grupo/store-grupo', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.storeGrupo');
    Route::post('/grupo/store-ficha', [GruposPersonalController::class, 'storeFicha'])->name('grupo.storeFicha');
    Route::post('/check-grupo-nombre', [GruposPersonalController::class, 'checkNombre'])->name('check.grupo.nombre');
    Route::post('/check-numero-ficha', [GruposPersonalController::class, 'checkNumeroFicha'])->name('check.numero.ficha');
    Route::get('/get-fichas', [GruposPersonalController::class, 'getFichas'])->name('get.fichas');
    Route::get('grupo/{id}/edit', [GruposPersonalController::class, 'editGrupo'])->name('grupo.edit');
    Route::put('grupo/{id}', [GruposPersonalController::class, 'updateGrupo'])->name('grupo.update');
    Route::get('ficha/{id}/edit', [GruposPersonalController::class, 'editFicha'])->name('ficha.edit');
    Route::put('ficha/{id}', [GruposPersonalController::class, 'updateFicha'])->name('ficha.update');
    Route::delete('/grupo/{id}', [GruposPersonalController::class, 'destroyGrupo'])->name('grupo.destroy');
    Route::delete('/ficha/{id}', [GruposPersonalController::class, 'destroyFicha'])->name('ficha.destroy');

    // Registro de Personal
    Route::get('registre/create', [RegisterPersonalController::class, 'create'])->name('register.create');
    Route::post('registre/store', [RegisterPersonalController::class, 'store'])->name('register.store');
    Route::get('/get-fichas', [RegisterPersonalController::class, 'getFichas'])->name('getFichas');
    Route::post('/check-numero-documento', [RegisterPersonalController::class, 'checkNumeroDocumento'])->name('check.numero.documento');
    Route::get('/grupos-fichas', [RegisterPersonalController::class, 'indexGruposFichas'])->name('grupos-fichas.index');
    Route::get('/personal-filtrado', [RegisterPersonalController::class, 'filtrarPersonal'])->name('personal.filtrado');
    Route::get('/register/{id}/edit', [RegisterPersonalController::class, 'edit'])->name('register.edit');
    Route::put('/register/{id}', [RegisterPersonalController::class, 'update'])->name('register.update');
    Route::delete('/register/{id}', [RegisterPersonalController::class, 'destroy'])->name('register.destroy');

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
    Route::get('/obtener-fichas/{grupoId}', [EntradaSalidaPersonalController::class, 'obtenerFichas']);

    // Observaciones
    Route::get('/Tool/create', [ToolController::class, 'create'])->name('Tool.create');
    Route::post('/Tool/store', [ToolController::class, 'store'])->name('Tool.store');
    Route::get('/Tool/filtro', [ToolController::class, 'index'])->name('Tool.index');
    Route::delete('/Tool/{id}', [ToolController::class, 'destroy'])->name('Tool.destroy');
    Route::get('/Tool/{id}/edit', [ToolController::class, 'edit'])->name('Tool.editar');
    Route::put('/Tool/{id}/edit', [ToolController::class, 'update'])->name('Tool.update');

    // Geolocalización de Estanques
    Route::get('/geo-estanque/form', [GeoPondcontroller::class, 'create'])->name('geo.create');
    Route::post('/geo-estanque', [GeoPondcontroller::class, 'store'])->name('geo.store');
    Route::get('/geo/edit/{id}', [PondUnitCodeController::class, 'edit'])->name('geo.edit');
    Route::put('/geo/update/{id}', [PondUnitCodeController::class, 'update'])->name('geo.update');
    Route::delete('/geo/destroy/{id}', [PondUnitCodeController::class, 'destroy'])->name('geo.destroy');
    Route::post('/pons/store', [PondUnitCodeController::class, 'store'])->name('pond.store');
    Route::get('/geo-estanque/filter', [PondUnitCodeController::class, 'index'])->name('geo.index');
    Route::get('/geo/estanque/{pond_id}/edit', [PondUnitCodeController::class, 'editEstanque'])->name('geo.edit-name');
    Route::put('/geo/estanque/{pond_id}', [PondUnitCodeController::class, 'updateEstanque'])->name('geo.update-name');
    Route::delete('/geo/estanque/{pond_id}', [PondUnitCodeController::class, 'deleteEstanque'])->name('geo.deleteEstanque');

    // Mortalidad
    Route::get('/mortality/create', [MortalityController::class, 'create'])->name('mortality.create');
    Route::post('/mortality/store', [MortalityController::class, 'store'])->name('mortality.store');
    Route::get('/get-ponds-by-pond-id', [MortalityController::class, 'getPondsByPondId'])->name('mortality.getPondsByPondId');
    Route::get('/mortality/get-ponds', [MortalityController::class, 'index'])->name('mortality.index');
    Route::get('/mortality/get-sowing-data', [MortalityController::class, 'getSowingData'])->name('mortality.getSowingData');
    Route::get('/mortality/history/{sowing}', [MortalityController::class, 'history'])->name('mortality.history');
    Route::get('/mortality/pdf/estanque/{pond_unit_code_id}', [MortalityController::class, 'pdfEstanque'])->name('mortality.pdf.estanque');
    Route::get('/mortality/pdf/quincena/{pond_unit_code_id}/{quincena}', [MortalityController::class, 'pdfQuincena'])->name('mortality.pdf.quincena');
    Route::get('mortality/{id}/edit', [MortalityController::class, 'edit'])->name('mortality.edit');
    Route::delete('mortality/{id}', [MortalityController::class, 'destroy'])->name('mortality.destroy');
    Route::put('mortality/{id}', [MortalityController::class, 'update'])->name('mortality.update');



    // Especies y Tipos
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

    // Siembras y seguimiento
    Route::get('/siembras/create', [SowingController::class, 'create'])->name('siembras.create');
    Route::post('/siembras', [SowingController::class, 'store'])->name('siembras.store');
    Route::get('/get-tipos/{especieId}', [SowingController::class, 'getTypes']);
    Route::get('/get-identificadores/{pondId}', [SowingController::class, 'getIdentifiers']);
    Route::get('/sowings', [DietMonitoringController::class, 'terminated'])->name('sowings.index');
    Route::get('/identifiers/by-pond/{pondId}', fn($pondId) => \App\Models\pond_unit_code::where('pond_id', $pondId)->get())->name('identifiers.by-pond');
    Route::get('/diet_monitoring', [DietMonitoringController::class, 'create'])->name('diet_monitoring.create');
    Route::get('seguimiento-dieta/{sowing_id}', [DietMonitoringController::class, 'index'])->name('diet_monitoring.index');
    Route::post('/seguimiento-dieta', [DietMonitoringController::class, 'store'])->name('diet_monitoring.store');
    Route::get('/sowing/{sowing}/diet-monitoring', [DietMonitoringController::class, 'showBySowing'])->name('sowing.diet_monitoring');
    Route::post('/sowing/{id}/finish', [DietMonitoringController::class, 'finish'])->name('sowing.finish');
    Route::get('/seguimientos/terminados', [DietMonitoringController::class, 'terminated'])->name('diet_monitoring.terminated');
    Route::get('/sowing/{id}/export-pdf', [SowingController::class, 'exportPDF'])->name('sowing.export.pdf');


    // Préstamos y devoluciones
    Route::resource('loans', LoanController::class);
    Route::resource('returns', ReturnController::class);

    // Visitantes
    Route::resource('visitors', VisitorController::class)->only(['index', 'create', 'store']);
    Route::get('/visitors/checkout', [VisitorController::class, 'checkoutForm'])->name('visitors.checkout.form');
    Route::post('/visitors/checkout', [VisitorController::class, 'updateCheckout'])->name('visitors.checkout.update');
    Route::get('/visitors/filter', [VisitorController::class, 'filter'])->name('visitors.filter');
    Route::post('/config/registro-publico/toggle', [VisitorController::class, 'toggleFormState'])->name('visitors.toggle');

    // Calidad del agua
    Route::get('/water-quality/create/{sowing}', [WaterQualityController::class, 'create'])->name('water_quality.create');
    Route::post('/water-quality/store/{sowing}', [WaterQualityController::class, 'store'])->name('water_quality.store');
    Route::get('/water-quality/history/{sowing}', [WaterQualityController::class, 'history'])->name('water_quality.history');
    Route::get('/water-quality/{id}/edit', [WaterQualityController::class, 'edit'])->name('water_quality.edit');
    Route::put('/water-quality/{id}', [WaterQualityController::class, 'update'])->name('water_quality.update');
    Route::delete('/water-quality/{id}', [WaterQualityController::class, 'destroy'])->name('water_quality.destroy');

    // Usuarios
    Route::resource('users', UserController::class);

    // Feed records
    Route::get('/feed-records/create/{sowingId}', [FeedRecordController::class, 'create'])->name('feed_records.create');
    Route::get('feed-records/{id}/edit', [FeedRecordController::class, 'edit'])->name('feed_records.edit');
    Route::post('/feed-records', [FeedRecordController::class, 'store'])->name('feed_records.store');
    Route::get('/feed-records/history/{sowingId}', [FeedRecordController::class, 'showFeedHistory'])->name('feed_records.history');
    Route::resource('feedRecords', FeedRecordController::class);
    Route::put('/feed-records/{id}', [FeedRecordController::class, 'update'])->name('feed_records.update');




    Route::get('/grafic', [GraficController::class, 'index'])->name('sowing.dashboard');
    Route::get('/grafic/compare', [GraficController::class, 'compare'])->name('sowing.compare');

    Route::get('/asignaciones', [AssignedSowingController::class, 'index'])->name('assigned_sowings.index');
    Route::get('/asignaciones/crear', [AssignedSowingController::class, 'create'])->name('assigned_sowings.create');
    Route::post('/asignaciones', [AssignedSowingController::class, 'store'])->name('assigned_sowings.store');
    Route::delete('/asignaciones/{id}', [AssignedSowingController::class, 'destroy'])->name('assigned_sowings.destroy');


});

/*
|--------------------------------------------------------------------------
| Fin de file
|--------------------------------------------------------------------------
*/
