@extends('layouts.app')

@section('title', 'Omnius Art | Proizvodi')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          <button id="popupButton" class="btn btn-primary float-start" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi proizvod</button>

          <x-search-form />

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Naziv proizvoda</th>
                <th scope="col">Vrsta</th>
                <th scope="col">Standardna cijena</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
                <tr>
                  <td class="align-middle text-right">{{ $products->firstItem() + $loop->index }}</td>
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $product->id }}" data-field="name" data-model="update-product">{{ $product->name }}</span>
                  </td>
                  <td class="align-middle text-right">
                    <div class="editable-select" data-id="{{ $product->id }}" data-field="product_type_id" data-model="update-product">
                    

                      <!-- Display the selected value -->
                      <span>{{ App\Models\ProductType::find($product->product_type_id)->name }}</span>
                      
                      <!-- Hidden select element with options -->
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi vrstu proizvoda...</option>
                          @foreach ($productTypes as $productType)
                          <option value="{{ $productType->id }}">{{ $productType->name }}</option>                                  
                          @endforeach 
                      </select>
                    </div>

                  </td>
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $product->id }}" data-field="default_price" data-model="update-product">{{ $product->default_price }}</span> €
                  </td>
                  <td>
                    <button class="btn btn-danger delete-btn-x" data-id="{{ $product->id }}" data-model="delete-product"><i class="bi bi-x-lg"></i>
                    </button>
                  </td>
                <tr>
              @endforeach
            </tbody>
          </table>

          <x-table-pagination :items="$products" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.products.includes.index.modals.add-product')
@include('includes.shared.modals.delete-confirmation')

@endsection