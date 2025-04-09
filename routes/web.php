<?php
use App\Http\Controllers\GruposPersonalController;
use App\Http\Controllers\observationcontroller;
use App\Http\Controllers\RegisterPersonalController;
use App\Http\Controllers\EntradaSalidaPersonalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeoPondcontroller;
use App\Http\Controllers\PondUnitCodeController;

// Ruta principal
Route::get('/', function () {
    return view('auth.login');
});

// Rutas de autenticación protegidas por el middleware centralizado
Auth::routes(['middleware' => 'role.redirect']);

// Rutas protegidas por roles
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

Route::post('/logout',[AuthController::class,'logout'])->name('logout');


Route::get('/dashboard', function(){
return view('auth.user.Dashboard');
}
);





Route::get('/grupopersonal', function() {
    return view('auth.user.r-personal.r-grupo.form');
} )->name('grupo-form');


Route::get('/registerperosnal', function(){
    return view('auth.user.r-personal.r-personal');
} )->name('register-personal');


Route::get('/entrada-salida', function(){
    return view('auth.user.r-personal.r-entrada-salida.r-entrada-salida');
} )->name('entrada-salida');






// Mostrar el formulario de creación
Route::get('/grupos/create', [GruposPersonalController::class, 'create'])->name('grupo.create');

// Guardar un nuevo grupo
Route::post('/grupos', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.store');

// Guardar una nueva ficha
Route::post('/fichas', [GruposPersonalController::class, 'storeFicha'])->name('ficha.store');


Route::post('/grupo/store-grupo', [GruposPersonalController::class, 'storeGrupo'])->name('grupo.storeGrupo');
Route::post('/grupo/store-ficha', [GruposPersonalController::class, 'storeFicha'])->name('grupo.storeFicha');
Route::post('/check-grupo-nombre', [GruposPersonalController::class, 'checkNombre'])->name('check.grupo.nombre');
Route::post('/check-numero-ficha', [GruposPersonalController::class, 'checkNumeroFicha'])->name('check.numero.ficha');
Route::get('/get-fichas', [GruposPersonalController::class, 'getFichas'])->name('get.fichas');







Route::get('registre/create',[RegisterPersonalController::class,'create'])->name('register.create');
Route::post('registre/store',[RegisterPersonalController::class,'store'])->name('register.store');
Route::get('/get-fichas', [RegisterPersonalController::class, 'getFichas'])->name('getFichas');
Route::post('/check-numero-documento', [RegisterPersonalController::class, 'checkNumeroDocumento'])->name('check.numero.documento');
Route::get('/grupos-fichas', [RegisterPersonalController::class, 'indexGruposFichas'])->name('grupos-fichas.index');
Route::get('/personal-filtrado', [RegisterPersonalController::class, 'filtrarPersonal'])->name('personal.filtrado');




Route::get('entradasalida/create',[EntradaSalidaPersonalController::class,'create'])->name('entrada_salida.create');
Route::post('entradasalida/store',[EntradaSalidaPersonalController::class,'store'])->name('entrada_salida.store');
Route::get('/entradas-salidas-filtradas', [EntradaSalidaPersonalController::class, 'filtrarPorGrupo'])->name('entradas_salidas.filtradas');












Route::get('/entrada-salida/create', [EntradaSalidaPersonalController::class, 'create'])->name('entrada_salida.create');
Route::post('/entrada-salida/store', [EntradaSalidaPersonalController::class, 'store'])->name('entrada_salida.store');
Route::get('/get-fichas-por-grupo', [EntradaSalidaPersonalController::class, 'getFichasPorGrupo']);
Route::get('/get-usuarios-por-ficha', [EntradaSalidaPersonalController::class, 'getUsuariosPorFicha']);
Route::get('/salida', [EntradaSalidaPersonalController::class, 'index'])->name('entrada_salida.index');
Route::post('/entrada-salida/actualizar-fecha-salida', [EntradaSalidaPersonalController::class, 'actualizarFechaSalida'])->name('entrada_salida.actualizar_fecha_salida');



## herramientas


Route::get('/observacion/create',[observationController::class,'create'])->name('observacion.create');
Route::post('/observacion/store',[observationcontroller::class,'store'])->name('observacion.store');
Route::get('/observacion/filtro',[observationController::class,'index'])->name('observacion.index');
Route::delete('/observacion/{id}',[observationController::class,'destroy'])->name('observacion.destroy');
Route::get('/observacion/{id}/edit',[observationcontroller::class,'edit'])->name('observacion.editar');
Route::put('/observacion/{id}/edit',[observationcontroller::class,'update'])->name('observacion.update');




Route::get('/geo-estanque/form',[GeoPondcontroller::class,'create'])-> name('geo.create');
Route::post('/geo-estanque',[GeoPondcontroller::class,'store'])-> name('geo.store');


Route::post('/pons/store',[PondUnitCodeController::class,'store'])->name('pond.store');



Route::get('/geo-estanque/filter',[PondUnitCodeController::class,'index'])->name('geo.index');