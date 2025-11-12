@extends('layouts.app')

@section('title', 'Omnius Art | Vrste proizvoda')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          
          <x-modal-button target="#addProductTypeModal" text="Nova vrsta proizvoda"/>
          <x-search-form />

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Vrsta proizvoda</th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($productTypes as $productType)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle text-right">
                    {{ $productTypes->firstItem() + $loop->index }}
                  </td>

                  {{-- Name --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $productType->id }}" data-field="name" data-model="vrste-proizvoda">{{ $productType->name }}</span>
                  </td>

                  {{-- Delete button --}}
                  <td class="align-middle text-center px-4">
                    <x-delete-button :id="$productType->id" model="vrste-proizvoda" />
                  </td>

                </tr>
              @endforeach
            </tbody>
          </table>

          <x-table-pagination :items="$productTypes" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.product-types.includes.index.modals.add-product-type')
@include('includes.shared.modals.delete-confirmation')

@endsection