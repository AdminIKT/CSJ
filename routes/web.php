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

Route::get('auth', function () {return view('auth.index');})->name('auth'); //FIXME: hide this route
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
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
    'users'                   => Controllers\UserController::class,
    'users.orders'            => Controllers\User\OrderController::class,
    'users.actions'           => Controllers\User\ActionController::class,
    'movements'               => Controllers\MovementController::class,
    'accounts'                => Controllers\AccountController::class,
    'accounts.movements'      => Controllers\Account\MovementController::class,
    'subaccounts.orders'      => Controllers\Subaccount\OrderController::class,
    'subaccounts.assignments' => Controllers\Subaccount\AssignmentController::class,
    'subaccounts.charges'     => Controllers\Subaccount\ChargeController::class,
    'areas'                   => Controllers\AreaController::class,
    'areas.orders'            => Controllers\Area\OrderController::class,
    'areas.movements'         => Controllers\Area\MovementController::class,
    'orders'                  => Controllers\OrderController::class,
    'orders.products'         => Controllers\Order\ProductController::class,
    'orders.invoices'         => Controllers\Order\InvoiceController::class,
    'orders.incidences'       => Controllers\Order\IncidenceController::class,
    'actions'                 => Controllers\ActionController::class,
    'suppliers'               => Controllers\SupplierController::class,
    'suppliers.contacts'      => Controllers\Supplier\ContactController::class,
    'suppliers.invoiceds'     => Controllers\Supplier\InvoicedController::class,
    'suppliers.incidences'    => Controllers\Supplier\IncidenceController::class,
    'suppliers.orders'        => Controllers\Supplier\OrderController::class,
    'suppliers.movements'     => Controllers\Supplier\MovementController::class,
    'invoiceCharges'          => Controllers\InvoiceChargeController::class,
    'settings'                => Controllers\SettingsController::class,
], [
    'middleware' => ['auth', 'user.valid']
]);

Route::middleware(['auth', 'user.valid'])->group(function() {
    Route::get('/', [Controllers\HomeController::class, 'home'])->name('home');
    Route::get('/receptions', [Controllers\OrderController::class, 'receptions'])->name('orders.receptions');
    Route::post('/orders/{order}/status', [Controllers\OrderController::class, 'status'])->name('orders.status');
    Route::get('/suppliers/{supplier}/indicence/{incidence}/close', [Controllers\Supplier\IncidenceController::class, 'close'])->name('suppliers.incidences.close');
    // Charge import
    Route::get('/movements/parse/invoice-charges', [Controllers\InvoiceCharge\Imports\AllExtensionsController::class, 'createStep1'])->name('imports.create.step1');
    Route::post('/movements/parse/invoice-charges', [Controllers\InvoiceCharge\Imports\AllExtensionsController::class, 'storeStep1'])->name('imports.store.step1');
    Route::get('/movements/import/invoice-charges', [Controllers\InvoiceCharge\Imports\AllExtensionsController::class, 'createStep2'])->name('imports.create.step2');
    Route::post('/movements/import/invoice-charges', [Controllers\InvoiceCharge\Imports\AllExtensionsController::class, 'storeStep2'])->name('imports.store.step2');
    // Reports
    Route::get('/reports/orders', [Controllers\ReportController::class, 'orders'])->name('reports.orders');
    Route::get('/reports/movements', [Controllers\ReportController::class, 'movements'])->name('reports.movements');
    Route::get('/reports/suppliers', [Controllers\ReportController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('language/{locale}', function ($locale) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    });
});
