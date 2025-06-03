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

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::get('/prijava-u-app', [LoginController::class, 'showLoginForm'])->name('auth.login')->middleware('throttle:3,1');
Route::post('/prijava-u-app', [LoginController::class, 'login'])->middleware('throttle:3,1');

// Index page
Route::get('/', [Omnicontrol::class, 'index']);

// Custom routes //
Route::get('/racuni/godina/{year}', [ReceiptController::class, 'index'])->name('racuni.index'); // Index override for the invoice view
Route::get('/racuni/zadnji-broj/{year}', [ReceiptController::class, 'getLatestNumber'])->name('racuni.getLatestNumber'); // Return JSON format for AJAX refresh
Route::get('/knjiga-prometa/godina/{year}', [KprController::class, 'index'])->name('knjiga-prometa.index');
Route::get('/narudzbe/prikaz/{type}/{customerId?}', [OrderController::class, 'index'])->name('narudzbe.index'); // Filter orders by type or customer
Route::get('/proizvodi/prikaz/{mode}', [OrderItemListController::class, 'showProductionItems']);

// Resource routes
Route::resources([
    'dostavne-usluge' => DeliveryServiceController::class,
    'kupci' => CustomerController::class,
    'racuni' => ReceiptController::class,
    'knjiga-prometa' => KprController::class,
    'drzave-poslovanja' => CountryController::class,
    'narudzbe' => OrderController::class,
    'napomena' => OrderNoteController::class,
    'proizvodi' => ProductController::class,
    'radne-godine' => WorkYearsController::class,
    'opis' => ColorController::class
]);

// Bolean switch routes - CHECKBOX STATUS CHANGE
Route::put('/note-on-invoice/status/{id}', [OrderItemListController::class, 'updateNoteOnInvoiceStatus']);
Route::put('/order-item-list/status/{id}', [OrderItemListController::class, 'updateIsDoneStatus']);
Route::put('/racuni/status/{id}', [ReceiptController::class, 'updateIsCancelledStatus']);
Route::put('dostavne-usluge/status/{id}', [DeliveryServiceController::class, 'updateIsUsedStatus']);

// Invoices //
Route::post('/invoice-to-kpr/{id}', [KprItemListController::class, 'add']);    
Route::delete('/kpr-item-list/{id}', [KprItemListController::class, 'destroy'])->name('delete.row');

// Order item lists //
Route::post('update-order-products/{id}', [OrderItemListController::class, 'add']);
Route::get('/u-izradi-po-boji', [OrderItemListController::class, 'productionItemsGroupByColor']);
Route::get('/u-izradi-po-proizvodu', [OrderItemListController::class, 'productionItemsGroupByProduct']);
Route::put('/order-item-list/{id}', [OrderItemListController::class, 'update']);
Route::delete('/order-item-list/{id}', [OrderItemListController::class, 'destroy'])->name('delete.row');

// Payment types //
Route::get('/nacin-placanja', [PaymentTypeController::class, 'show']);
Route::post('/nacin-placanja', [PaymentTypeController::class, 'save']);
Route::put('/update-payment-type/{id}', [PaymentTypeController::class, 'updatePaymentType']);
Route::delete('/delete-payment-type/{id}', [PaymentTypeController::class, 'destroy'])->name('delete.row');

// Sources, sales channels //
Route::get('/kanali-prodaje', [SourceController::class, 'show']);
Route::post('/kanali-prodaje', [SourceController::class, 'save']);
Route::put('/update-source/{id}', [SourceController::class, 'update']);
Route::delete('/delete-source/{id}', [SourceController::class, 'destroy'])->name('delete.row');

// Product types //
Route::get('/vrste-proizvoda', [ProductTypeController::class, 'show']);
Route::post('/vrste-proizvoda', [ProductTypeController::class, 'save']);
Route::put('/update-product-type/{id}', [ProductTypeController::class, 'update']);
Route::delete('/delete-product-type/{id}', [ProductTypeController::class, 'destroy'])->name('delete.row');

// PDF render //
Route::get('/racun/{id}', [DoomPDFController::class, 'invoice']);
Route::get('/dokument/{mode}/{id}', [DoomPDFController::class, 'generateDocument']);
Route::get('/etikete', [DoomPDFController::class, 'shippingLabels']);
Route::get('/p10m/{id}', [DoomPDFController::class, 'p10mLabels']);

// LABELS   //
// Shipping //
Route::get('/dostavne-etikete', [PrintLabelController::class, 'showShippingLabels']);
Route::post('/dostavne-etikete', [PrintLabelController::class, 'saveShippingLabel']);
Route::delete('/delete-shipping-label/{id}', [PrintLabelController::class, 'destroyShippingLabel'])->name('delete.row');
Route::get('/obrisi-etikete', [PrintLabelController::class, 'deleteAllLabels']);