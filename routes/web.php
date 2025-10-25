<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Omnicontrol;
use App\Http\Controllers\DeliveryServiceController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\WorkYearsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemListController;
use App\Http\Controllers\DoomPDFController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PrintLabelController;
use App\Http\Controllers\KprController;
use App\Http\Controllers\KprItemListController;
use App\Http\Controllers\OrderNoteController;

/*
|--------------------------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------------------------
*/
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::get('/prijava-u-app', [LoginController::class, 'showLoginForm'])->name('auth.login')->middleware('throttle:3,1');
Route::post('/prijava-u-app', [LoginController::class, 'login'])->middleware('throttle:3,1');

/*
|--------------------------------------------------------------------------------------------
| Index page route
|--------------------------------------------------------------------------------------------
*/
Route::get('/', [Omnicontrol::class, 'index']);

/*
|--------------------------------------------------------------------------------------------
| Custom routes
|--------------------------------------------------------------------------------------------
*/
Route::get('/racuni/godina/{year}', [ReceiptController::class, 'index'])->name('racuni.index_by_year'); // Index override for the invoice view
Route::get('/knjiga-prometa/godina/{year}', [KprController::class, 'index'])->name('knjiga-prometa.index_by_year'); // Index override for the KPR view
Route::get('/narudzbe/prikaz/{type}/{customerId?}', [OrderController::class, 'index'])->name('narudzbe.index_by_type'); // Filter orders by type or customer
Route::get('/proizvodi/prikaz/{mode}', [OrderItemListController::class, 'showProductionItems']); // Production items view
Route::get('/u-izradi-po-boji', [OrderItemListController::class, 'productionItemsGroupByColor']); // Production items grouped by color
Route::get('/u-izradi-po-proizvodu', [OrderItemListController::class, 'productionItemsGroupByProduct']); // Production items grouped by product

/*
|--------------------------------------------------------------------------------------------
| JSON data routes for AJAX calls
|--------------------------------------------------------------------------------------------
*/
Route::get('/racuni/zadnji-broj/{year}', [ReceiptController::class, 'getLatestNumber'])->name('racuni.getLatestNumber');

/*
|--------------------------------------------------------------------------------------------
| Resource routes
|--------------------------------------------------------------------------------------------
*/
Route::resources([
    'dostavne-usluge' => DeliveryServiceController::class,
    'kupci' => CustomerController::class,
    'racuni' => ReceiptController::class,
    'knjiga-prometa' => KprController::class,
    'drzave-poslovanja' => CountryController::class,
    'narudzbe' => OrderController::class,
    'narudzbe-proizvodi' => OrderItemListController::class,
    'napomena' => OrderNoteController::class,
    'proizvodi' => ProductController::class,
    'radne-godine' => WorkYearsController::class,
    'opis' => ColorController::class,
    'nacin-placanja' => PaymentTypeController::class,
    'vrste-proizvoda' => ProductTypeController::class,
    'kanali-prodaje' => SourceController::class,
    'paketi' => PackageController::class
]);

/*
|--------------------------------------------------------------------------------------------
| Bolean switch routes - CHECKBOX STATUS CHANGE
|--------------------------------------------------------------------------------------------
*/
Route::put('/note-on-invoice/status/{id}', [OrderItemListController::class, 'updateNoteOnInvoiceStatus']);
Route::put('/order-item-list/status/{id}', [OrderItemListController::class, 'updateIsDoneStatus']);
Route::put('/racuni/status/{id}', [ReceiptController::class, 'updateIsCancelledStatus']);
Route::put('dostavne-usluge/status/{id}', [DeliveryServiceController::class, 'updateIsUsedStatus']);

/*
|--------------------------------------------------------------------------------------------
| Invoice routes
|--------------------------------------------------------------------------------------------
*/
Route::post('/invoice-to-kpr/{id}', [KprItemListController::class, 'store'])->name('invoice-to-kpr.store');;    
Route::delete('/kpr-item-list/{id}', [KprItemListController::class, 'destroy'])->name('kpr-item-list.delete');

/*
|--------------------------------------------------------------------------------------------
| PDF rendering routs
|--------------------------------------------------------------------------------------------
*/
Route::get('/racun/{id}', [DoomPDFController::class, 'invoice']);
Route::get('/dokument/{mode}/{id}', [DoomPDFController::class, 'generateDocument']);
Route::get('/etikete', [DoomPDFController::class, 'shippingLabels']);
Route::get('/p10m/{id}', [DoomPDFController::class, 'p10mLabels']);

/*
|--------------------------------------------------------------------------------------------
| Shipping labels
|--------------------------------------------------------------------------------------------
*/
Route::get('/dostavne-etikete', [PrintLabelController::class, 'showShippingLabels']);
Route::post('/dostavne-etikete', [PrintLabelController::class, 'saveShippingLabel']);
Route::delete('/delete-shipping-label/{id}', [PrintLabelController::class, 'destroyShippingLabel'])->name('shipping_label.delete');
Route::get('/obrisi-etikete', [PrintLabelController::class, 'deleteAllLabels']);