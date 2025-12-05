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
                  <th class="delete-column"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoices as $invoice)
                  <tr @class(['cancelled-order' => $invoice->is_cancelled])>

                    {{-- Invoice number --}}
                    <td class="align-middle text-right">
                      {{ $invoice->number }}
                    </td>

                    {{-- Customer name --}}
                    <td class="align-middle text-right">
                      <span>{{ $invoice->customerName }}</span>                                    
                    </td>

                    {{-- Order link --}}
                    <td class="align-middle text-right">
                      <a href="{{ route('narudzbe.show', $invoice->order_id) }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $invoice->order_id }}</span></a>                                    
                    </td>

                    {{-- Payment type --}}
                    <td class="align-middle text-right">
                      {{ $invoice->paymentTypeName }}
                    </td>

                    {{-- Created at --}}
                    <td class="align-middle text-right">
                      <div class="editable-date-invoice" data-id="{{ $invoice->id }}" data-field="created_at" data-model="racuni" data-raw-date="{{ $invoice->created_at }}">
                        <div class="date-display">{{ $invoice->formatedDateCreatedAt }}</div>
                      </div>
                    </td>

                    {{-- Status --}}
                    <td class="align-middle text-right">
                      {{-- Not in use right now --}}
                    </td>

                    {{-- Total amount --}}
                    <td class="align-middle text-right">
                      {{ $invoice->totalAmount }} €
                    </td>

                    {{-- Is cancelled, this needs to be chaged --}}
                    <td class="align-middle text-right">
                      <div class="form-check form-switch order-item" data-id="{{ $invoice->id }}" data-model="storno">
                        <input class="form-check-input edit-checkbox" type="checkbox" name="is_cancelled" id="flexSwitchCheckDefault" {{ $invoice->is_cancelled ? 'checked' : '' }}>
                      </div>
                    </td>

                    {{-- Linked payment --}}
                    <td>
                      @if ( $invoice->hasPayment )
                        <a href="{{ route('knjiga-prometa.show', $invoice->paymentId) }}" class="btn btn-warning" target="_blank"><i class="bi bi-filetype-pdf">
                          </i> Uplata <span class="badge badge-secondary" style="background-color:darkred">ID: {{ $invoice->paymentId }}</span>
                        </a>
                      @endif
                    </td>
                    
                    {{-- Generate PDF document buttons --}}
                    <td>
                      <a href="{{ route('generate.document', ['mode' => 'racun', 'id' => $invoice->id]) }}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> Račun</a>
                    </td>

                    {{-- Delete button --}}
                    <td class="align-middle text-center px-4">
                      <x-buttons.delete-item :id="$invoice->id" model="racuni" />
                    </td>

                  </tr>
                @endforeach
              </tbody>
            </table>

            <x-misc.table-pagination :items="$invoices" />
            
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.invoices.includes.index.modals.add-invoice')
@include('includes.shared.modals.delete-confirmation')

@endsection