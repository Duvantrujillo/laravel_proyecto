<?php
use App\Http\Controllers\GruposPersonalController;
use App\Http\Controllers\RegisterPersonalController;
use App\Http\Controllers\EntradaSalidaPersonalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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





Route::get('grupos/create', [GruposPersonalController::class, 'create'])->name('grupos.create');
Route::post('grupos', [GruposPersonalController::class, 'store'])->name('grupos.store');


Route::get('registre/create',[RegisterPersonalController::class,'create'])->name('register.create');
Route::post('registre/store',[RegisterPersonalController::class,'store'])->name('register.store');





Route::get('entradasalida/create',[EntradaSalidaPersonalController::class,'create'])->name('entrada_salida.create');
Route::post('entradasalida/store',[EntradaSalidaPersonalController::class,'store'])->name('entrada_salida.store');










Route::get('/get-usuarios-por-grupo', [EntradaSalidaPersonalController::class, 'getUsuariosPorGrupo']);
