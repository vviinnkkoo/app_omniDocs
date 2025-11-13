@extends('layouts.app')

@section('title', 'Omnius Art | Narudžbe')

@section('content')
<div class="container-fluid px-2 px-lg-5">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">

          {{-- Action buttons and search form --}}
          <x-buttons.open-modal target="#addOrderModal" text="Nova narudžba"/>
          <x-buttons.open-modal target="#addCustomerModal" text="Novi kupac"/>
          <x-misc.search-form/>

          {{-- Order type filter buttons --}}
          <div class="clearfix mb-2 mt-2">
            <a class="btn btn-primary btn-sm float-start" href="{{ route('narudzbe.indexByType', ['type' => 'sve']) }}">Sve narudžbe</a>
            <a class="btn btn-success btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'poslane']) }}">Poslane narudžbe</a>
            <a class="btn btn-warning btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'neodradene']) }}">Neodrađene narudžbe</a>
            <a class="btn btn-danger btn-sm ms-1 float-start" href="{{ route('narudzbe.indexByType', ['type' => 'otkazane']) }}">Otkazane</a>
          </div>

          {{-- Order list --}}
          @include('pages.orders.includes.index.sections.order-list')

          <x-misc.table-pagination :items="$orders" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.orders.includes.index.modals.add-order')
@include('includes.shared.modals.add-customer')

@endsection