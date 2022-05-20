<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

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

Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});
Route::get('/', [Controllers\HomeController::class, 'home'])->name('home')->middleware('auth');

Route::get('auth', function () {return view('auth.index');})->name('auth'); //HIDE this route
Route::get('/redirect', [Controllers\SocialiteController::class, 'redirectToProvider'])->name('redirectToProvider');
Route::get('/callback', [Controllers\SocialiteController::class, 'handleProviderCallback'])->name('handleProvider');

/*
 *  GET         /photos                 index       photos.index
 *  GET         /photos/create          create      photos.create
 *  POST        /photos                 store       photos.store
 *  GET         /photos/{photo}         show        photos.show
 *  GET         /photos/{photo}/edit    edit        photos.edit
 *  PUT/PATCH   /photos/{photo}         update      photos.update
 *  DELETE      /photos/{photo}         destroy     photos.destroy
 */
Route::resources([
    'users'                     => Controllers\UserController::class,
    'movements'                 => Controllers\MovementController::class,
    'accounts'                  => Controllers\AccountController::class,
    'accounts.movements'        => Controllers\Account\MovementController::class,

    'subaccounts.orders'        => Controllers\Subaccount\OrderController::class,
    'subaccounts.assignments'   => Controllers\Subaccount\AssignmentController::class,
    'subaccounts.charges'       => Controllers\Subaccount\ChargeController::class,

    'areas'                     => Controllers\AreaController::class,
    'areas.orders'              => Controllers\Area\OrderController::class,
    'areas.movements'           => Controllers\Area\MovementController::class,
    'orders'                    => Controllers\OrderController::class,
    'orders.products'           => Controllers\Order\ProductController::class,
    'orders.invoices'           => Controllers\Order\InvoiceController::class,
    'orders.incidences'         => Controllers\Order\IncidenceController::class,
    'suppliers'                 => Controllers\SupplierController::class,
    'suppliers.contacts'        => Controllers\Supplier\ContactController::class,
    'suppliers.invoiceds'       => Controllers\Supplier\InvoicedController::class,
    'suppliers.incidences'      => Controllers\Supplier\IncidenceController::class,
    'suppliers.orders'          => Controllers\Supplier\OrderController::class,
    'suppliers.movements'       => Controllers\Supplier\MovementController::class,
    'invoiceCharges'            => Controllers\InvoiceChargeController::class,
    'settings'                  => Controllers\SettingsController::class,
], [
    'middleware' => 'auth'
]);
Route::post('/orders/{order}/status', [Controllers\OrderController::class, 'status'])->name('orders.status');
Route::get('/imports', [Controllers\InvoiceCharge\ImportController::class, 'create'])->name('imports.create');
Route::post('/imports', [Controllers\InvoiceCharge\ImportController::class, 'list'])->name('imports.list');
Route::post('/imports.store', [Controllers\InvoiceCharge\ImportController::class, 'store'])->name('imports.store');
// Reports
Route::get('/reports/orders', [Controllers\ReportController::class, 'orders'])->name('reports.orders');
