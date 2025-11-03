@extends('layouts.app')

@section('title', 'Omnius Art | Knjiga prometa')

@section('content')
<div class="container-fluid px-2 px-lg-5">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">

        <div class="card-body">

          <button id="popupButton" class="btn btn-primary float-start" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#invoiceModal"><i class="bi bi-file-earmark-plus"></i> Nova uplata</button>

          <x-search-form/>

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
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($kprs as $item)
                  <tr class="{{ $item->exists ? 'kpr-has-receipt' : 'kpr-no-receipt' }}">

                    <td class="align-middle text-start">
                      {{ $item->index }}
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->payer }}</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->date }}</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->payment_type_name }}</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->origin }}</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $item->info }}</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $formated_amount }} €</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display">{{ $formated_receipts_total }} €</div>
                    </td>

                    <td class="align-middle text-start">
                      <div class="date-display"><a href="{{ route('knjiga-prometa.show', $item->id) }}" class="btn btn-success">Uredi</a></div>
                    </td>

                    <td>
                      <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="knjiga-prometa"><i class="bi bi-x-lg"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <x-table-pagination :items="$kprs"/>

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.kpr.includes.index.modals.add-payment')
@include('includes.shared.modals.delete-confirmation')

@endsection