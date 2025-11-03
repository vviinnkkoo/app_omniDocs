@extends('layouts.app')

@section('title', 'Omnius Art | Računi')

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi račun</button>

          <x-search-form/>

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Kupac</th>
                  <th scope="col">Narudžba</th>
                  <th scope="col">Način plaćanja</th>
                  <th scope="col">Vrijeme izdavanja</th>
                  <th scope="col">Status</th>
                  <th scope="col">Iznos na računu</th>
                  <th scope="col">Storno</th>
                  <th scope="col">Povezana uplata</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($receipts as $receipt)
                  <tr @class(['cancelled-order' => $receipt->is_cancelled])>

                    <td class="align-middle text-right">
                      {{ $receipt->number }}
                    </td>

                    <td class="align-middle text-right">
                      <span>{{ $receipt->customerName }}</span>                                    
                    </td>

                    <td class="align-middle text-right">
                      <a href="/narudzbe/{{ $receipt->order_id }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $receipt->order_id }}</span></a>                                    
                    </td>

                    <td class="align-middle text-right">
                      {{ $receipt->paymentTypeName }}
                    </td>

                    <td class="align-middle text-right">
                      <div class="editable-date-invoice" data-id="{{ $receipt->id }}" data-field="created_at" data-model="racuni" data-raw-date="{{ $receipt->created_at }}">
                        <div class="date-display">{{ $receipt->formatedDateCreatedAt }}</div>
                      </div>
                    </td>

                    <td class="align-middle text-right">
                      {{-- Nenaplaćen / Naplaćen / Storno račun / Storniran --}}
                    </td>

                    <td class="align-middle text-right">
                      {{ $receipt->totalAmount }} €
                    </td>

                    <td class="align-middle text-right">
                      <div class="form-check form-switch order-item" data-id="{{ $receipt->id }}" data-model="storno">
                        <input class="form-check-input edit-checkbox" type="checkbox" name="is_cancelled" id="flexSwitchCheckDefault" {{ $receipt->is_cancelled ? 'checked' : '' }}>
                      </div>
                    </td>

                    <td>
                      @if ( $receipt->hasPayment )
                        <a href="/knjiga-prometa/{{ $receipt->paymentId }}" class="btn btn-warning" target="_blank"><i class="bi bi-filetype-pdf">
                          </i> Uplata <span class="badge badge-secondary" style="background-color:darkred">ID: {{ $receipt->paymentId }}</span>
                        </a>
                      @endif
                    </td>
                    

                    <td>
                      <a href="/dokument/racun/{{$receipt->id}}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> Račun</a>
                    </td>

                    <td>
                      <button class="btn btn-danger delete-btn-x" data-id="{{ $receipt->id }}" data-model="racuni"><i class="bi bi-x-lg"></i></button>
                    </td>
                  <tr>
                @endforeach
              </tbody>
            </table>

            <x-table-pagination :items="$receipts" />
            
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.receipts.includes.index.modals.add-receipt')
@include('includes.shared.modals.delete-confirmation')

@endsection