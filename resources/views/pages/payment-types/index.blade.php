@extends('layouts.app')

@section('title', 'Omnius Art | Načini plaćanja')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          
          <x-modal-button target="#paymentTypeModal" text="Novi način plaćanja"/>
          <x-search-form />

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Način plaćanja</th>
                  <th class="delete-column"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($paymentTypes as $paymentType)
                  <tr>

                    {{-- Index number --}}
                    <td class="align-middle text-right">
                      {{ $paymentTypes->firstItem() + $loop->index }}
                    </td>

                    {{-- Payment type name --}}
                    <td class="align-middle text-right">
                      <span class="editable" data-id="{{ $paymentType->id }}" data-field="name" data-model="nacin-placanja">{{ $paymentType->name }}</span>
                    </td>

                    {{-- Delete button --}}
                    <td class="align-middle text-center px-4">
                      <x-delete-button :id="$paymentType->id" model="nacin-placanja" />
                    </td>

                  </tr>
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