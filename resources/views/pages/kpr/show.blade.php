@extends('layouts.app')

@section('title', 'Omnius Art | Uredi uplatu')
@section('title', $kprInstance->payer . ' - Uredi uplatu')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header fw-bold">
          <a class="gray-mark-extra" href="{{ route('knjiga-prometa.indexByYear', $year) }}"><i class="bi bi-arrow-left"></i></a> Uplata: {{$kprInstance->id}} - {{ $year }}
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">

              <div class="mb-3">
                <div>
                  <h5 class="fw-bold">Platitelj:</h5>
                </div>              
                <div>
                  <span class="editable" data-id="{{ $kprInstance->id }}" data-field="payer" data-model="knjiga-prometa">{{ $kprInstance->payer }}</span>
                </div>
              </div>

              <div class="mb-3">
                <div>
                  <h5 class="fw-bold">Datum:</h5>
                </div>
                <div class="editable-date" data-id="{{ $kprInstance->id }}" data-field="date" data-model="knjiga-prometa">
                  <input type="date" class="form-control" style="width:40%" value="{{ $kprInstance->date }}">
                </div>
              </div>

              <div class="mb-3">
                <div>
                  <h5 class="fw-bold">Iznos:</h5>
              </div>
                <div>
                  <span class="editable" data-id="{{ $kprInstance->id }}" data-field="amount" data-model="knjiga-prometa">{{ $kprInstance->amount }}</span> €
                </div>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <div>
                  <h5 class="fw-bold">Broj naloga:</h5>
                </div>              
                <div>
                  <span class="editable" data-id="{{ $kprInstance->id }}" data-field="origin" data-model="knjiga-prometa">{{ $kprInstance->origin }}</span>
                </div>
              </div>

              <div class="mb-3">
                <div>
                  <h5 class="fw-bold">Opis:</h5>
                </div>
                <div>
                  <span class="editable" data-id="{{ $kprInstance->id }}" data-field="info" data-model="knjiga-prometa">{{ $kprInstance->info }}</span>
                </div>
              </div>  
                          
            </div>

            <div class="col">
              <div class="mb-3">
                {{-- Empty column for spacing --}}
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="card" style="margin-top: 15px;">
        <div class="card-body">
          
          <x-buttons.open-modal target="#addInvoiceModal" text="Poveži račun"/>
          @include('includes.tablesearch')

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>                          
                <th scope="col">Broj računa</th>
                <th scope="col">Kupac</th>
                <th scope="col">Narudžba</th>
                <th scope="col">Kod</th>
                <th scope="col">Vrijeme izdavanja</th>
                <th scope="col">Iznos na računu</th>
                <th scope="col">Račun</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($invoiceList as $invoiceItem)
                <tr>

                  {{-- Invoice Number --}}
                  <td class="align-middle text-right">
                    {{ $invoiceItem->receiptNumber }}
                  </td>

                  {{-- Customer Name --}}
                  <td class="align-middle text-right">
                    <span>{{ $invoiceItem->customerName }}</span>
                  </td>

                  {{-- Order ID --}}
                  <td class="align-middle text-right">
                    <a href="{{ route('narudzbe.show', $invoiceItem->orderId) }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $invoiceItem->orderId }}</span></a>
                  </td>
                  
                  {{-- Tracking Code --}}
                  <td class="align-middle text-right">
                    {{ $invoiceItem->trackingCode }}
                  </td>

                  {{-- Issue Date --}}
                  <td class="align-middle text-right">
                    <div class="date-display">{{ $invoiceItem->receiptDate }}</div>
                  </td>

                  {{-- Total Amount --}}
                  <td class="align-middle text-right">
                    {{ $invoiceItem->receiptsTotal }} €
                  </td>

                  {{-- Generate PDF document button --}}
                  <td>
                    <a href="{{ route('generate.document', ['mode' => 'racun', 'id' => $invoiceItem->receiptID]) }}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> Račun</a>
                    </button>
                  </td>

                  {{-- Delete button --}}
                  <td>
                    <button class="btn btn-danger delete-btn-x" data-id="{{ $invoiceItem->id }}" data-model="kpr-item-list"><i class="bi bi-x-lg"></i></button>
                  </td>
                
                <tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

@include('pages.kpr.includes.show.modals.add-invoice')
@include('includes.shared.modals.delete-confirmation')

@endsection