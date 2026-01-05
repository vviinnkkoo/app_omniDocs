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
                  <th scope="col">Broj računa
                  <th scope="col">Kupac</th>
                  <th scope="col">Vrsta</th>
                  <th scope="col">Način plaćanja</th>
                  <th scope="col">Vrijeme izdavanja</th>
                  <th scope="col">Iznos na računu</th>
                  <th scope="col">Narudžba</th>
                  <th scope="col">Uplata</th>
                  <th></th>
                  <th></th>
                  <th class="delete-column"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoices as $item)
                  <tr @class(['cancelled-row' => $item->is_cancelled])>

                    {{-- Invoice number --}}
                    <td class="align-middle text-right">
                        {{ $item->number }}-{{ $item->businessSpace->name }}-{{ $item->businessDevice->name }}
                    </td>

                    {{-- Customer name --}}
                    <td class="align-middle text-right">
                      <span>{{ $item->customer_name }}</span>                                    
                    </td>

                    {{-- Invoice type --}}
                    <td class="align-middle text-right">
                      {{ $item->type_text }}
                    </td>

                    {{-- Payment type --}}
                    <td class="align-middle text-right">
                      {{ $item->paymentType?->name }} ({{$item->paymentType?->fiscal_code_key}})
                    </td>

                    {{-- Created at --}}
                    <td class="align-middle text-right">
                        <div class="date-display">{{ $item->formatted_issued_date }} {{ $item->formatted_issued_time }} </div>
                      </div>
                    </td>

                    {{-- Total amount --}}
                    <td class="align-middle text-right">
                      {{ $item->item_total }} €
                    </td>

                    {{-- Order link --}}
                    <td class="align-middle text-right">
                      @if ( $item->order_id )
                        <a href="{{ route('narudzbe.show', $item->order_id) }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $item->order_id }}</span> <i class="bi bi-arrow-right"></i></a>                                    
                      @else
                        Nema
                      @endif
                    </td>

                    {{-- Linked payment --}}
                    <td class="align-middle text-right">
                      @if ( $item->hasPayment )
                        <a href="{{ route('knjiga-prometa.show', $item->paymentId) }}" class="btn btn-warning" target="_blank"><i class="bi bi-filetype-pdf">
                          </i> Uplata <span class="badge badge-secondary" style="background-color:darkred">ID: {{ $item->paymentId }}</span>
                        </a>
                      @else
                        Nema
                      @endif
                    </td>

                    {{-- Edit button --}}
                    <td class="align-middle text-start">
                      <div class="date-display"><a href="{{ route('racuni.show', $item->id) }}" class="btn btn-success">Uredi <i class="bi bi-arrow-right"></i></a></div>
                    </td>
                    
                    {{-- Generate PDF document buttons --}}
                    <td>
                      <a href="{{ route('generate.document', ['mode' => 'racun', 'id' => $item->id]) }}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> A4</a>
                    </td>

                    {{-- Delete button --}}
                    <td class="align-middle text-center px-4">
                      <x-buttons.delete-item :id="$item->id" model="racuni" />
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

@include('pages.invoices.includes.index.modals.add-invoice', [
    'workYears' => $workYears
])
@include('includes.shared.modals.delete-confirmation')

@endsection