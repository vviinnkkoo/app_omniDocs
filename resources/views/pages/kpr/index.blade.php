@extends('layouts.app')

@section('title', 'Omnius Art | Knjiga prometa')

@section('content')
<div class="container-fluid px-2 px-lg-5">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">

        <div class="card-body">
          
          <x-buttons.open-modal target="#addPaymentModal" text="Nova uplata"/>
          <x-misc.search-form/>

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Platitelj</th>
                  <th scope="col">Datum</th>
                  <th scope="col">Mjesto uplate</th>
                  <th scope="col">Broj naloga</th>
                  <th scope="col">Opis</th>
                  <th scope="col">Iznos</th>
                  <th scope="col">Povezani računi</th>
                  <th></th>
                  <th class="delete-column"></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($kprs as $item)
                  <tr class="{{ $item->exists ? 'kpr-has-receipt' : 'kpr-no-receipt' }}">

                    {{-- Index --}}
                    <td class="align-middle text-start">
                      {{ $item->index }}
                    </td>

                    {{-- Payer name --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->payer }}</div>
                    </td>

                    {{-- Date of payment --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->date }}</div>
                    </td>

                    {{-- Payment type --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->payment_type_name }}</div>
                    </td>

                    {{-- Payment reference number --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->origin }}</div>
                    </td>

                    {{-- Description --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->info }}</div>
                    </td>

                    {{-- Amount --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->formated_amount }} €</div>
                    </td>

                    {{-- Related receipts total --}}
                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->formated_receipts_total }} €</div>
                    </td>

                    {{-- Edit button --}}
                    <td class="align-middle text-start">
                      <div class="date-display"><a href="{{ route('knjiga-prometa.show', $item->id) }}" class="btn btn-success">Uredi</a></div>
                    </td>

                    {{-- Delete button --}}
                    <td class="align-middle text-center px-4">
                      <x-buttons.delete-item :id="$item->id" model="knjiga-prometa" />
                    </td>

                  </tr>
                @endforeach
              </tbody>
            </table>

            <x-misc.table-pagination :items="$kprs"/>

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.kpr.includes.index.modals.add-payment')
@include('includes.shared.modals.delete-confirmation')

@endsection