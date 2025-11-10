@extends('layouts.app')

@section('title', 'Omnius Art | Narudžbe')

@section('content')
<div class="container-fluid px-2 px-lg-5">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">

          {{-- New order and customer buttons, search bar section --}}
          <div class="clearfix mb-3">
            <button id="popupButton" class="btn btn-primary float-start mb-2" data-bs-toggle="modal" data-bs-target="#newOrderModal">
              <i class="bi bi-file-earmark-plus"></i> Nova narudžba
            </button>
            <button id="popupButton" class="btn btn-primary float-start ms-2 mb-2" data-bs-toggle="modal" data-bs-target="#customerModal">
              <i class="bi bi-file-earmark-plus"></i> Novi kupac
            </button>

            <x-search-form/>

          </div>

          {{-- Order type filter buttons --}}
          <div class="clearfix mb-3">
            <a class="btn btn-primary btn-sm float-start" href="{{ route('narudzbe.indexByType', ['type' => 'sve']) }}">Sve narudžbe</a>
            <a class="btn btn-success btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'poslane']) }}">Poslane narudžbe</a>
            <a class="btn btn-warning btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'neodradene']) }}">Neodrađene narudžbe</a>
            <a class="btn btn-danger btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'otkazane']) }}">Otkazane</a>
          </div>

          @include('pages.orders.includes.index.sections.order-list')

          <x-table-pagination :items="$orders" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.orders.includes.index.modals.add-order')
@include('includes.shared.modals.add-customer')

@endsection