<?php

use App\Http\Controllers\AddAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleControlelr;
use App\Http\Controllers\SectionsController;
use App\Models\invoices_details;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('invoice')->group(function () {
    Route::get('/create', [InvoicesController::class, 'create'])->name('invoices.create');
    Route::post('/store', [InvoicesController::class, 'store'])->name('invoices.store');
    Route::get('/index', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/edit/{id}', [InvoicesController::class, 'edit'])->name('invoices.edit');
    Route::get('/show/status/{id}', [InvoicesController::class, 'showstatus'])->name('invoices.show.status');
    Route::post('/edit/status/{id}', [InvoicesController::class, 'editstatus'])->name('invoices.edit.status');
    Route::patch('/update/{id}', [InvoicesController::class, 'update'])->name('invoices.update');
    Route::delete('/delete', [InvoicesController::class, 'destroy'])->name('invoices.destroy');

    //////////////
    Route::get('/show/{id}', [invoices_details::class, 'edit'])->name('invoices.show');
    Route::get('/paid', [InvoicesController::class, 'Invoice_Paid'])->name('invoices.paid');
    Route::get('/partial/paid', [InvoicesController::class, 'Invoice_Partial'])->name('invoices.partial.paid');
    Route::get('/unpaid', [InvoicesController::class, 'Invoice_unPaid'])->name('invoices.unpaid');


});

Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);

Route::prefix('invoices/detail')->group(function () {
    Route::get('/view/file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'viewFile'])->name('view.file');
    Route::post('/store', [InvoiceAttachmentsController::class, 'store'])->name('detaile.store');
    Route::get('/index', [InvoicesDetailsController::class, 'index'])->name('detaile.index');
    Route::get('/show/{id}', [InvoicesDetailsController::class, 'show'])->name('detaile.show');
    Route::get('/edit/{id}', [InvoicesDetailsController::class, 'edit'])->name('detaile.edit');
    Route::patch('/update', [InvoicesDetailsController::class, 'update'])->name('detaile.update');
    Route::post('/delete', [InvoicesDetailsController::class, 'destroy'])->name('detaile.destroy');
});

Route::prefix('section')->group(function () {
    Route::get('/create', [SectionsController::class, 'create'])->name('sections.create');
    Route::post('/store', [SectionsController::class, 'store'])->name('sections.store');
    Route::get('/index', [SectionsController::class, 'index'])->name('sections.index');
    Route::get('/show/{id}', [SectionsController::class, 'show'])->name('sections.show');
    Route::get('/edit/{id}', [SectionsController::class, 'edit'])->name('sections.edit');
    Route::patch('/update', [SectionsController::class, 'update'])->name('sections.update');
    Route::delete('/delete', [SectionsController::class, 'destroy'])->name('sections.destroy');
});

Route::get('/index', [SectionsController::class, 'index'])->name('section.index');


Route::prefix('product')->group(function () {
    Route::get('/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/store', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/index', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/show/{id}', [ProductsController::class, 'show'])->name('products.show');
    Route::get('/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit');
    Route::patch('/update', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/delete', [ProductsController::class, 'destroy'])->name('products.destroy');
});

Route::prefix('archive')->group(function () {
    Route::get('/create', [InvoiceArchiveController::class, 'create'])->name('archive.create');
    Route::post('/store', [InvoiceArchiveController::class, 'store'])->name('archive.store');
    Route::get('/index', [InvoiceArchiveController::class, 'index'])->name('archive.index');
    Route::get('/show/{id}', [InvoiceArchiveController::class, 'show'])->name('archive.show');
    Route::get('/edit/{id}', [InvoiceArchiveController::class, 'edit'])->name('archive.edit');
    Route::patch('/update', [InvoiceArchiveController::class, 'update'])->name('archive.update');
    Route::delete('/delete', [InvoiceArchiveController::class, 'destroy'])->name('archive.destroy');
});

///////////////// permissiom and role

Route::prefix('permission')->group(function () {
    Route::get('/create', [RoleControlelr::class, 'create'])->name('permission.create');
    Route::post('/store', [RoleControlelr::class, 'store'])->name('permission.store');
    Route::get('/index', [RoleControlelr::class, 'index'])->name('permission.index');
    Route::get('/show/{id}', [RoleControlelr::class, 'show'])->name('permission.show');
    Route::get('/edit/{id}', [RoleControlelr::class, 'edit'])->name('permission.edit');
    Route::post('/update/{id}', [RoleControlelr::class, 'update'])->name('permission.update');
    Route::get('/delete', [RoleControlelr::class, 'destroy'])->name('permission.destroy');
});
Route::prefix('role')->group(function () {
    Route::get('/create', [RoleControlelr::class, 'createrole'])->name('role.create');
    Route::post('/store', [RoleControlelr::class, 'storerole'])->name('role.store');
    Route::get('/index', [RoleControlelr::class, 'indexrole'])->name('role.index');
    Route::get('/show/{id}', [RoleControlelr::class, 'showrole'])->name('role.show');
    Route::get('/edit/{id}', [RoleControlelr::class, 'editrole'])->name('role.edit');
    Route::post('/update/{id}', [RoleControlelr::class, 'updaterole'])->name('role.update');
    Route::get('/delete/{id}', [RoleControlelr::class, 'destroyrole'])->name('role.destroy');
});

Route::prefix('role/permission')->group(function () {
    Route::get('/all/role/permission', [RoleControlelr::class, 'AllRolePermission'])->name('all.role.permission');
    Route::get('/add/role/permission', [RoleControlelr::class, 'AddRolePermission'])->name('add.role.permission');
    Route::post('/store/role/permission', [RoleControlelr::class, 'StoreRolePermission'])->name('role.pemission.store');
    Route::post('/update/role/permission/{id}', [RoleControlelr::class, 'UpdateRolePermission'])->name('update.role.permission');
    Route::get('/edit/role/permission/{id}', [RoleControlelr::class, 'EditRolePermission'])->name('edit.role.permission');
    Route::get('/delete/role/permission/{id}', [RoleControlelr::class, 'DeleteRolePermission'])->name('delete.role.permission');
});


/////////////////////////////


Route::prefix('admin')->group(function () {
    Route::get('/create', [AddAdminController::class, 'create'])->name('admin.create');
    Route::post('/store', [AddAdminController::class, 'store'])->name('admin.store');
    Route::get('/index', [AddAdminController::class, 'index'])->name('admin.index');
    Route::get('/show/{id}', [AddAdminController::class, 'show'])->name('admin.show');
    Route::get('/edit/{id}', [AddAdminController::class, 'edit'])->name('admin.edit');
    Route::post('/update/{id}', [AddAdminController::class, 'update'])->name('admin.update');
    Route::get('/delete/{id}', [AddAdminController::class, 'destroy'])->name('admin.destroy');
});


require __DIR__ . '/auth.php';


