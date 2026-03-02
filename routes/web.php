<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

//Rutas para el admin
Route::get('/dashboard', function (){ return redirect()->route('admin.index');})->name('dashboard')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');



//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth');
Route::post('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth');

//Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware('auth');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');

//Rutas para clientes
Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index')->middleware('auth');
Route::get('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create')->middleware('auth');
Route::post('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store')->middleware('auth');
Route::post('/admin/cliente/{id}/restaurar', [App\Http\Controllers\ClienteController::class, 'restaurar'])->name('admin.clientes.restaurar')->middleware('auth');
Route::get('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'show'])->name('admin.clientes.show')->middleware('auth');
Route::get('/admin/cliente/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware('auth');
Route::put('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update')->middleware('auth');
Route::delete('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware('auth');

//Rutas para categorias
Route::get('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('admin.categorias.index')->middleware('auth');
Route::post('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class, 'store'])->name('admin.categorias.store')->middleware('auth');
Route::put('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('admin.categorias.update')->middleware('auth');
Route::delete('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('admin.categorias.destroy')->middleware('auth');

//Rutas para prestamos
Route::get('/admin/prestamos', [App\Http\Controllers\PrestamoController::class, 'index'])->name('admin.prestamos.index')->middleware('auth');
Route::get('/admin/prestamos/create', [App\Http\Controllers\PrestamoController::class, 'create'])->name('admin.prestamos.create')->middleware('auth');
Route::get('/admin/prestamo/{id}/contrato', [App\Http\Controllers\PrestamoController::class, 'contrato'])->name('admin.prestamos.contrato')->middleware('auth');
Route::post('/admin/prestamos/create', [App\Http\Controllers\PrestamoController::class, 'store'])->name('admin.prestamos.store')->middleware('auth');
Route::get('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class, 'show'])->name('admin.prestamos.show')->middleware('auth');
Route::get('/admin/prestamo/{id}/edit', [App\Http\Controllers\PrestamoController::class,'edit'])->name('admin.prestamos.edit')->middleware('auth');
Route::put('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class,'update'])->name('admin.prestamos.update')->middleware('auth');
Route::delete('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class,'destroy'])->name('admin.prestamos.destroy')->middleware('auth');

//rutas para pagos
Route::post('/admin/pago/create', [App\Http\Controllers\PagoController::class, 'store'])->name('admin.pagos.store')->middleware('auth');
Route::get('/admin/pago/{id}/comprobante', [App\Http\Controllers\PagoController::class, 'comprobante'])->name('admin.pagos.comprobante')->middleware('auth');
Route::post('/admin/pago/{id}/borrar', [App\Http\Controllers\PagoController::class, 'destroy'])->name('admin.pagos.destroy')->middleware('auth');

//rutas para notificaciones
Route::get('/admin/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])->name('admin.notificaciones.index')->middleware('auth');
Route::post('/admin/notificacion/{cliente}/email', [App\Http\Controllers\NotificacionController::class, 'notificarEmail'])->name('admin.notificaciones.notificarEmail')->middleware('auth');
Route::get('/admin/notificacion/{cliente}/whatsapp', [App\Http\Controllers\NotificacionController::class, 'notificarWhatsapp'])->name('admin.notificaciones.notificarWhatsapp')->middleware('auth');



