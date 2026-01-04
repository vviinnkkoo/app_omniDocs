@extends('layouts.app')

@section('title', 'Omnius Art | Računi')

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">

          <x-buttons.open-modal target="#addInvoiceModal" text="Novi račun"/>
          <x-misc.search-form/>
            
        </div>
      </div>
    </div>
  </div>
</div>

@include('includes.shared.modals.delete-confirmation')

@endsection