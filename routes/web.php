<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\Admin\DamageReportController as AdminDamageReportController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\MaterialController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('login/{provider}', [LoginController::class, 'redirectToProvider']);
Route::get('{provider}/callback', [LoginController::class,'handleProviderCallback']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Reservación
Route::get('reservacion/{slug}', [BookingController::class,'index']);
Route::get('reservacion/json/{id?}', [BookingController::class,'json']);
Route::post('reservacion/store', [BookingController::class,'store']);
Route::get('agenda-espacios', [BookingController::class,'areas']);

Route::get('reporte', [DamageReportController::class,'create']);
Route::post('reportes', [DamageReportController::class,'store']);

Route::get('consulta', [DamageReportController::class,'consulta']);
Route::get('reportes/buscar', [DamageReportController::class,'buscar']);

Route::get('material', [MaterialController::class,'consulta']);
Route::get('material/buscar', [MaterialController::class,'buscar']);


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'noCache']], function() {
    Route::view('/', 'admin/dashboard');

    //perfil
    Route::view('perfil/editar','admin.editar-perfil');
    Route::put('perfil/editar', [ProfileController::class, 'update']);

    //permisos
    Route::get('permisos', [PermissionController::class, 'index']);
    Route::view('agregar-permisos', 'admin.permisos.crear');
    Route::post('permiso/crear', [PermissionController::class, 'save']);
    Route::get('permiso/{id}/editar', [PermissionController::class, 'edit']);
    Route::put('permiso/{id}/actualizar', [PermissionController::class, 'update']);
    Route::delete('permiso/eliminar/{id}', [PermissionController::class, 'delete']);

    //roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::view('agregar-roles', 'admin.roles.crear');
    Route::post('roles/crear', [RoleController::class, 'save']);
    Route::get('roles/{id}/editar', [RoleController::class, 'edit']);
    Route::put('roles/{id}/actualizar', [RoleController::class, 'update']);
    Route::delete('roles/eliminar/{id}', [RoleController::class, 'delete']);

    //equipo
    Route::get('inventario', [EquipmentController::class, 'index']);
    Route::view('agregar-inventario', 'admin.inventario.crear');
    Route::post('inventario/crear', [EquipmentController::class, 'save']);
    Route::get('inventario/{id}/editar', [EquipmentController::class, 'edit']);
    Route::put('inventario/{id}/actualizar', [EquipmentController::class, 'update']);
    Route::delete('inventario/eliminar/{id}', [EquipmentController::class, 'delete']);

    //Vales Material
    Route::get('vales-equipo', [VoucherController::class, 'index']);
    Route::get('agregar-vale-material', [VoucherController::class, 'create']);
    Route::post('vales-equipo/crear', [VoucherController::class, 'save']);
    Route::get('vales-equipo/{id}/editar', [VoucherController::class, 'edit']);
    Route::get('pdf-vale-equipo/{id}', [PdfController::class, 'pdfVoucher']);
    Route::put('vales-equipo/{id}/actualizar', [VoucherController::class, 'update']);
    Route::delete('vales-equipo/eliminar/{id}', [VoucherController::class, 'delete']);

    //Usuarios
    Route::get('usuarios', [UserController::class, 'index']);

    //Password
    Route::view('cambiar-contrasena', 'principal.cambiar-contrasena');
    Route::post('cambiar-contrasena', 'Auth\PasswordController@update');

    //equipo asignado
    Route::get('lista-equipo-asignado', [AssignmentController::class, 'index']);
    Route::get('asignar-equipo', [AssignmentController::class, 'create']);
    Route::post('asignar-equipo/crear', [AssignmentController::class, 'save']);
    Route::get('asignar-equipo/{id}/editar', [AssignmentController::class, 'edit']);
    Route::put('asignar-equipo/{id}/actualizar', [AssignmentController::class, 'update']);
    Route::delete('asignar-equipo/eliminar/{id}', [AssignmentController::class, 'delete']);

    //Reservaciones
    Route::get('reservaciones', [AdminBookingController::class,'index']);
    Route::get('reservaciones/{id}/editar', [AdminBookingController::class, 'edit']);
    Route::put('reservaciones/{id}/actualizar', [AdminBookingController::class, 'update']);
    Route::delete('reservaciones/eliminar/{id}', [AdminBookingController::class, 'delete']);

    //Reportes
    Route::get('reportes', [AdminDamageReportController::class,'index']);
    Route::get('reportes/{id}/editar', [AdminDamageReportController::class, 'edit']);
    Route::put('reportes/{id}/actualizar', [AdminDamageReportController::class, 'update']);
    Route::delete('reportes/eliminar/{id}', [AdminDamageReportController::class, 'delete']);

    //Estadísticas
    Route::get('estadisticas', [StatisticController::class, 'index'])
    ->name('admin.statistics');

});