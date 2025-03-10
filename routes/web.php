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
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.login');

// Display and Post routes

    // Index page
    Route::get('/', [Omnicontrol::class, 'index']);

    // Resource routes
    Route::resources([
        'dostavne-usluge' => DeliveryServiceController::class
    ]);

    // Orders //
    Route::get('/narudzbe/{mode}', [OrderController::class, 'showOrders']);
    Route::get('/poslane-narudzbe', [OrderController::class, 'showSent']);
    Route::get('/neodradjene-narudzbe', [OrderController::class, 'showUnfinished']);
    Route::post('/narudzbe', [OrderController::class, 'save']);
    Route::put('/order/{id}', [OrderController::class, 'update']);
    Route::get('/uredi-narudzbu/{id}', [OrderController::class, 'edit']);
    Route::delete('/delete-order/{id}', [OrderController::class, 'destroy'])->name('delete.row');

    // Order item lists //
    Route::post('update-order-products/{id}', [OrderItemListController::class, 'add']);
    Route::get('/proizvodi/{mode}', [OrderItemListController::class, 'showProductionItems']);
    Route::get('/u-izradi-po-boji', [OrderItemListController::class, 'productionItemsGroupByColor']);
    Route::get('/u-izradi-po-proizvodu', [OrderItemListController::class, 'productionItemsGroupByProduct']);
    Route::put('/order-item-list/{id}', [OrderItemListController::class, 'update']);
    Route::delete('/order-item-list/{id}', [OrderItemListController::class, 'destroy'])->name('delete.row');

    // Bolean switch routes - USAGE STATUS
    Route::put('dostavne-usluge/usage-status/{id}', [DeliveryServiceController::class, 'updateIsUsedStatus']);

    // Bolean switch routes - IS DONE STATUS    
    Route::put('/order-item-list/is-done-status/{id}', [OrderItemListController::class, 'updateIsDoneStatus']);

    // Payment types //
    Route::get('/nacin-placanja', [PaymentTypeController::class, 'show']);
    Route::post('/nacin-placanja', [PaymentTypeController::class, 'save']);
    Route::put('/update-payment-type/{id}', [PaymentTypeController::class, 'updatePaymentType']);
    Route::delete('/delete-payment-type/{id}', [PaymentTypeController::class, 'destroy'])->name('delete.row');


    // Customers //
    Route::get('/kupci', [CustomerController::class, 'show']);
    Route::post('/kupci/{ref}', [CustomerController::class, 'save']);
    Route::put('/update-customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/delete-customer/{id}', [CustomerController::class, 'destroy'])->name('delete.row');


    // Sources, sales channels //
    Route::get('/kanali-prodaje', [SourceController::class, 'show']);
    Route::post('/kanali-prodaje', [SourceController::class, 'save']);
    Route::put('/update-source/{id}', [SourceController::class, 'update']);
    Route::delete('/delete-source/{id}', [SourceController::class, 'destroy'])->name('delete.row');

    // Work years //
    Route::get('/radne-godine', [WorkYearsController::class, 'show']);
    Route::post('/radne-godine', [WorkYearsController::class, 'save']);
    Route::put('/update-work-year/{id}', [WorkYearsController::class, 'update']);
    Route::delete('/delete-work-year/{id}', [WorkYearsController::class, 'destroy'])->name('delete.row');


    // Countries //
    Route::get('/drzave-poslovanja', [CountryController::class, 'show']);
    Route::post('/drzave-poslovanja', [CountryController::class, 'save']);
    Route::put('/update-country/{id}', [CountryController::class, 'update']);
    Route::delete('/delete-country/{id}', [CountryController::class, 'destroy'])->name('delete.row');


    // Product types //
    Route::get('/vrste-proizvoda', [ProductTypeController::class, 'show']);
    Route::post('/vrste-proizvoda', [ProductTypeController::class, 'save']);
    Route::put('/update-product-type/{id}', [ProductTypeController::class, 'update']);
    Route::delete('/delete-product-type/{id}', [ProductTypeController::class, 'destroy'])->name('delete.row');


    // Colors //
    Route::get('/boje-proizvoda', [ColorController::class, 'show']);
    Route::post('/boje-proizvoda', [ColorController::class, 'save']);
    Route::put('/update-color/{id}', [ColorController::class, 'update']);
    Route::delete('/delete-color/{id}', [ColorController::class, 'destroy'])->name('delete.row');


    // Products //
    Route::get('/proizvodi', [ProductController::class, 'show']);
    Route::post('/proizvodi', [ProductController::class, 'save']);
    Route::put('/update-product/{id}', [ProductController::class, 'update']);
    Route::delete('/delete-product/{id}', [ProductController::class, 'destroy'])->name('delete.row');


    // PDF render //
    Route::get('/racun/{id}', [DoomPDFController::class, 'invoice']);
    Route::get('/dokument/{mode}/{id}', [DoomPDFController::class, 'generateDocument']);
    Route::get('/etikete', [DoomPDFController::class, 'shippingLabels']);
    Route::get('/p10m/{id}', [DoomPDFController::class, 'p10mLabels']);


    // Receipts //
    Route::get('/racuni/{year}', [ReceiptController::class, 'show']);
    Route::post('/racuni', [ReceiptController::class, 'save']);
    Route::put('/update-receipt/{id}', [ReceiptController::class, 'update']);
    Route::delete('/delete-receipt/{id}', [ReceiptController::class, 'destroy'])->name('delete.row');
    Route::put('/receipt-isdone-status/{id}', [ReceiptController::class, 'updateIsCancelledStatus']);


    // LABELS   //
    // Shipping //
    Route::get('/dostavne-etikete', [PrintLabelController::class, 'showShippingLabels']);
    Route::post('/dostavne-etikete', [PrintLabelController::class, 'saveShippingLabel']);
    Route::delete('/delete-shipping-label/{id}', [PrintLabelController::class, 'destroyShippingLabel'])->name('delete.row');
    Route::get('/obrisi-etikete', [PrintLabelController::class, 'deleteAllLabels']);


    // KPRs //
    Route::get('/knjiga-prometa/{year}', [KprController::class, 'show']);
    Route::post('/knjiga-prometa', [KprController::class, 'save']);
    Route::put('/update-kpr/{id}', [KprController::class, 'update']);
    Route::delete('/delete-kpr/{id}', [KprController::class, 'destroy'])->name('delete.row');
    Route::get('/uredi-uplatu/{id}', [KprController::class, 'edit']);


    // Invoices //
    Route::post('/invoice-to-kpr/{id}', [KprItemListController::class, 'add']);    
    Route::delete('/delete-kpr-item-list/{id}', [KprItemListController::class, 'destroy'])->name('delete.row');


    // Order note edits //
    Route::post('add-note/{id}', [OrderNoteController::class, 'add']);
    Route::put('/update-note/{id}', [OrderNoteController::class, 'update']);
    Route::delete('/delete-note/{id}', [OrderNoteController::class, 'destroy'])->name('delete.row');