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

      <div class="row g-2 d-flex align-items-stretch" style="margin-top: 30px;">
        @include('pages.orders.includes.show.sections.order-packages')
        @include('pages.orders.includes.show.sections.order-notes')
      </div>
      
    </div>
  </div>

  @include('pages.orders.includes.show.modals.add-note')
  @include('pages.orders.includes.show.modals.add-product')
  @include('pages.orders.includes.show.modals.add-invoice')
  @include('pages.orders.includes.show.modals.add-package')
  @include('includes.modals.delete-confirmation')
@endsection