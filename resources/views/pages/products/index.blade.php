@extends('layouts.app')

@section('title', 'Omnius Art | Proizvodi')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          
          <x-buttons.open-modal target="#addProductModal" text="Novi proizvod"/>
          <x-misc.search-form />

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Naziv proizvoda</th>
                <th scope="col">Vrsta</th>
                <th scope="col">Standardna cijena</th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle text-right">
                    {{ $products->firstItem() + $loop->index }}
                  </td>

                  {{-- Name --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $product->id }}" data-field="name" data-model="proizvodi">{{ $product->name }}</span>
                  </td>

                  {{-- Product type select --}}
                  <td class="align-middle text-right">
                    <div class="editable-select" data-id="{{ $product->id }}" data-field="product_type_id" data-model="proizvodi">
                      {{-- Display the selected value --}}
                      <span>{{ $product->productType->name ?? 'Nije definirano' }}</span>
                      
                      {{-- Hidden select element with options --}}
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi vrstu proizvoda...</option>
                          @foreach ($productTypes as $productType)
                          <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                          @endforeach 
                      </select>
                    </div>
                  </td>

                  {{-- Default price --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $product->id }}" data-field="default_price" data-model="proizvodi">{{ $product->default_price }}</span> €
                  </td>

                  {{-- Delete button --}}
                  <td class="align-middle text-center px-4">
                    <x-buttons.delete-item :id="$product->id" model="proizvodi" />
                  </td>

                </tr>
              @endforeach
            </tbody>
          </table>

          <x-misc.table-pagination :items="$products" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.products.includes.index.modals.add-product')
@include('includes.shared.modals.delete-confirmation')

@endsection