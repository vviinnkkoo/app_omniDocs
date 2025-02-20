@extends('layouts.app')

@section('title', 'Omnius Art | Naslovna')

@section('content')
<div class="container">

    <div class="container">

      <div class="row">
        <h1>Status narudžbi</h1>
        <div class="col-sm">
              <div class="card text-white bg-success mb-3">
                <div class="card-header">Sveukupno naručeno: <span class="badge text-bg-warning rounded-pill">{{ $countOrders }}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{ $earningTotal }} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
        <div class="col-sm">
              <div class="card text-white bg-primary mb-3">
                <div class="card-header">Neisporučeno do danas: <span class="badge text-bg-warning rounded-pill">{{ $countUndeliveredOrders }}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{ $earningUndelivered }} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
        <div class="col-sm">          
              <div class="card text-white bg-dark mb-3">
                <div class="card-header">Naručeno ovaj mjesec: <span class="badge text-bg-warning rounded-pill">{{ $countThisMonthOrders }}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{ $earningCurrentMonth }} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
      </div>

      <div class="row mt-5">
        <h1>Računi</h1>
        
        <div class="col-sm">
              <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Izdano računa u <b>2023</b>: <span class="badge text-bg-warning rounded-pill">{{-- App\Http\Controllers\ReceiptController::countReceipts(2023) --}}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{-- App\Http\Controllers\ReceiptController::getTotalForAllReceipts(2023) --}} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
        <div class="col-sm">          
              <div class="card text-white bg-dark mb-3">
                <div class="card-header">Uplate u <b>2023</b>: <span class="badge text-bg-warning rounded-pill">{{-- App\Http\Controllers\KprController::countPayments(2023) --}}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{-- App\Http\Controllers\KprController::getTotalPayments(2023) --}} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
      </div>

      <div class="row mt-5">
        
        <div class="col-sm">
              <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Izdano računa u <b>2024</b>: <span class="badge text-bg-warning rounded-pill">{{-- App\Http\Controllers\ReceiptController::countReceipts(2024) --}}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{-- App\Http\Controllers\ReceiptController::getTotalForAllReceipts(2024) --}} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
        <div class="col-sm">          
              <div class="card text-white bg-dark mb-3">
                <div class="card-header">Uplate u <b>2024</b>: <span class="badge text-bg-warning rounded-pill">{{-- App\Http\Controllers\KprController::countPayments(2024) --}}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{-- App\Http\Controllers\KprController::getTotalPayments(2024) --}} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
      </div>

    </div>
</div>


@endsection