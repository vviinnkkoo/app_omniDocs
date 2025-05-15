@extends('layouts.app')

@section('title', 'Omnius Art | Naslovna')

@section('content')
<div class="container">
  <div class="container">
    <div class="row">
      <h1>Status narudžbi</h1>
      <div class="col-sm">
            <div class="card text-white bg-success mb-3">
              <div class="card-header">Sveukupno naručeno: <span class="badge text-bg-warning rounded-pill">{{ $countActiveOrders }}</span></div>
              <div class="card-body">
                <h5 class="card-title">{{ $totalEarnings }} €</h5>
                <p class="card-text">...</p>
              </div>
            </div>
      </div>
      <div class="col-sm">
            <div class="card text-white bg-primary mb-3">
              <div class="card-header">Neisporučeno do danas: <span class="badge text-bg-warning rounded-pill">{{ $countUndeliveredOrders }}</span></div>
              <div class="card-body">
                <h5 class="card-title">{{ $undeliveredEarnings }} €</h5>
                <p class="card-text">...</p>
              </div>
            </div>
      </div>
      <div class="col-sm">          
            <div class="card text-white bg-dark mb-3">
              <div class="card-header">Naručeno ovaj mjesec: <span class="badge text-bg-warning rounded-pill">{{ $countThisMonthOrders }}</span></div>
              <div class="card-body">
                <h5 class="card-title">{{ $currentMonthEarnings }} €</h5>
                <p class="card-text">...</p>
              </div>
            </div>
      </div>
    </div>

    <div class="row">
      <h1>Računi i uplate</h1>
    </div>
    @foreach ($yearData as $data)
      <div class="row mt-3">
        <h3>Godina: {{ $data['year'] }}</h2>
        <div class="col-sm">
              <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Izdano računa</b>: <span class="badge text-bg-warning rounded-pill">{{ $data['invoiceCount'] }}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{ $data['invoiceSum'] }} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
        <div class="col-sm">          
              <div class="card text-white bg-dark mb-3">
                <div class="card-header">Uplate</b>: <span class="badge text-bg-warning rounded-pill">{{ $data['paymentCount'] }}</span></div>
                <div class="card-body">
                  <h5 class="card-title">{{ $data['paymentSum'] }} €</h5>
                  <p class="card-text">...</p>
                </div>
              </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection