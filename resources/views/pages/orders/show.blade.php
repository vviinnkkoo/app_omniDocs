@extends('layouts.app')

@section('title', $order->id . ' - ' . $order->customer_name . ' - Uredi narudžbu')

@section('content')
  <div class="container-fluid px-2 px-lg-5">
    <div class="row g-3 justify-content-center">
      <div class="col-md-12">
        <div class="card">
          @include('pages.orders.includes.show.sections.order-info')
        </div>
      </div>

      @include('pages.orders.includes.show.sections.order-item-list')
      @include('pages.orders.includes.show.sections.order-notes')
    </div>
  </div>

  @include('pages.orders.includes.show.modals.add-note')
  @include('pages.orders.includes.show.modals.add-product')
  @include('pages.orders.includes.show.modals.add-invoice')
  @include('includes.modals.delete-confirmation')
@endsection
