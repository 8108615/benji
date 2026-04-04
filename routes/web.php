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
Route::get('/dashboard', function ()
    { return redirect()->route('admin.index');
})->name('dashboard')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');


//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth', 'can:Ver formulario de ajustes');
Route::post('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth', 'can:Editar ajustes');

//Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth','can:Ver listado de roles');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth', 'can:Ver formulario de creacion de rol');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth', 'can:Guardar rol');
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth', 'can:Ver datos del rol');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth', 'can:Ver formulario de edicion del rol');
Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth', 'can:Ver formulario de permisos del rol');
Route::put('/admin/rol/{id}/update_permisos', [App\Http\Controllers\RoleController::class, 'updatePermisos'])->name('admin.roles.updatePermisos')->middleware('auth', 'can:Actualizar permisos del rol');
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth', 'can:Actualizar rol');
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth', 'can:Eliminar rol');

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth', 'can:Ver listado de usuarios');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth', 'can:Ver formulario de creacion de usuario');
Route::post('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth', 'can:Guardar usuario');
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware('auth', 'can:Restaurar usuario');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth', 'can:Ver datos del usuario');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth', 'can:Ver formulario de edicion del usuario');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth', 'can:Actualizar usuario');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth', 'can:Eliminar usuario');

//Rutas para clientes
Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index')->middleware('auth', 'can:Ver listado de clientes');
Route::get('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create')->middleware('auth', 'can:Ver formulario de creacion de cliente');
Route::post('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store')->middleware('auth', 'can:Guardar cliente');
Route::post('/admin/cliente/{id}/restaurar', [App\Http\Controllers\ClienteController::class, 'restaurar'])->name('admin.clientes.restaurar')->middleware('auth', 'can:Restaurar cliente');
Route::get('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'show'])->name('admin.clientes.show')->middleware('auth', 'can:Ver datos del cliente');
Route::get('/admin/cliente/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware('auth', 'can:Ver formulario de edicion del cliente');
Route::put('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update')->middleware('auth', 'can:Actualizar cliente');
Route::delete('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware('auth', 'can:Eliminar cliente');

//Rutas para categorias
Route::get('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('admin.categorias.index')->middleware('auth', 'can:Ver listado de categorias');
Route::post('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class, 'store'])->name('admin.categorias.store')->middleware('auth', 'can:Guardar categoria');
Route::put('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('admin.categorias.update')->middleware('auth', 'can:Actualizar categoria');
Route::delete('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('admin.categorias.destroy')->middleware('auth', 'can:Eliminar categoria');

//Rutas para prestamos
Route::get('/admin/prestamos', [App\Http\Controllers\PrestamoController::class, 'index'])->name('admin.prestamos.index')->middleware('auth', 'can:Ver listado de prestamos');
Route::get('/admin/prestamos/create', [App\Http\Controllers\PrestamoController::class, 'create'])->name('admin.prestamos.create')->middleware('auth', 'can:Ver formulario de creacion de prestamo');
Route::get('/admin/prestamo/{id}/contrato', [App\Http\Controllers\PrestamoController::class, 'contrato'])->name('admin.prestamos.contrato')->middleware('auth', 'can:Ver contrato de prestamo');
Route::post('/admin/prestamos/create', [App\Http\Controllers\PrestamoController::class, 'store'])->name('admin.prestamos.store')->middleware('auth', 'can:Guardar prestamo');
Route::post('/admin/prestamos/{id}/liquidar', [App\Http\Controllers\PrestamoController::class, 'liquidar'])->name('admin.prestamos.liquidar')->middleware('auth', 'can:Liquidar prestamo');
Route::get('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class, 'show'])->name('admin.prestamos.show')->middleware('auth', 'can:Ver datos del prestamo');
Route::get('/admin/prestamo/{id}/edit', [App\Http\Controllers\PrestamoController::class,'edit'])->name('admin.prestamos.edit')->middleware('auth', 'can:Ver formulario de edicion del prestamo');
Route::put('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class,'update'])->name('admin.prestamos.update')->middleware('auth', 'can:Actualizar prestamo');
Route::delete('/admin/prestamo/{id}', [App\Http\Controllers\PrestamoController::class,'destroy'])->name('admin.prestamos.destroy')->middleware('auth', 'can:Eliminar prestamo');

//rutas para pagos
Route::post('/admin/pago/create', [App\Http\Controllers\PagoController::class, 'store'])->name('admin.pagos.store')->middleware('auth', 'can:Guardar pago');
Route::get('/admin/pago/{id}/comprobante', [App\Http\Controllers\PagoController::class, 'comprobante'])->name('admin.pagos.comprobante')->middleware('auth', 'can:Ver comprobante del pago');
Route::post('/admin/pago/{id}/borrar', [App\Http\Controllers\PagoController::class, 'destroy'])->name('admin.pagos.destroy')->middleware('auth', 'can:Eliminar pago');

//rutas para pagos parciales
Route::post('/admin/pago_parcial/create', [App\Http\Controllers\PagoParcialController::class, 'store'])->name('admin.pago_parcials.store')->middleware('auth', 'can:Guardar pago parcial');
Route::delete('/admin/pago_parcial/{id}', [App\Http\Controllers\PagoParcialController::class, 'destroy'])->name('admin.pago_parcials.destroy')->middleware('auth', 'can:Eliminar pago parcial');

//rutas para notificaciones
Route::get('/admin/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])->name('admin.notificaciones.index')->middleware('auth', 'can:Ver listado de notificaciones');
Route::post('/admin/notificacion/{cliente}/email', [App\Http\Controllers\NotificacionController::class, 'notificarEmail'])->name('admin.notificaciones.notificarEmail')->middleware('auth', 'can:Enviar notificacion por email');
Route::get('/admin/notificacion/{cliente}/whatsapp', [App\Http\Controllers\NotificacionController::class, 'notificarWhatsapp'])->name('admin.notificaciones.notificarWhatsapp')->middleware('auth', 'can:Enviar notificacion por whatsapp');

//rutas para backups
Route::get('/admin/backups', [App\Http\Controllers\BackupController::class, 'index'])->name('admin.backups.index')->middleware('auth', 'can:Ver listado de backups');
Route::post('/admin/backups/create', [App\Http\Controllers\BackupController::class, 'store'])->name('admin.backups.store')->middleware('auth', 'can:Crear backup');
Route::get('/admin/backups/{file}/download', [App\Http\Controllers\BackupController::class, 'download'])->name('admin.backups.download')->middleware('auth', 'can:Descargar backup');
Route::post('/admin/backups/{file}/delete', [App\Http\Controllers\BackupController::class, 'destroy'])->name('admin.backups.destroy')->middleware('auth', 'can:Eliminar backup');


