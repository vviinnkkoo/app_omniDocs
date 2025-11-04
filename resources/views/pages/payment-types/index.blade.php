@extends('layouts.app')

@section('title', 'Omnius Art | Načini plaćanja')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#paymentTypeModal"><i class="bi bi-file-earmark-plus"></i> Novi način plaćanja</button>

          <x-search-form />

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Način plaćanja</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($paymentTypes as $paymentType)
                  <tr>

                    <td class="align-middle text-right">
                      {{ $paymentTypes->firstItem() + $loop->index }}
                    </td>

                    <td class="align-middle text-right">
                      <span class="editable" data-id="{{ $paymentType->id }}" data-field="name" data-model="nacin-placanja">{{ $paymentType->name }}</span>
                    </td>

                    <td class="align-middle text-center px-4">
                      <x-delete-button :id="$paymentType->id" model="nacin-placanja" />
                    </td>

                  <tr>
                @endforeach
              </tbody>
            </table>

            <x-table-pagination :items="$paymentTypes" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.payment-types.includes.index.modals.add-payment-type')
@include('includes.shared.modals.delete-confirmation')

@endsection