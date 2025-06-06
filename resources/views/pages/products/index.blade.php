@extends('layouts.app')

@section('title', 'Omnius Art | Proizvodi')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary float-start" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi proizvod</button>

                  {{-- @include('includes.tablesearch') --}}

                  <form method="GET" action="/proizvodi" class="mb-3">
                    <div class="input-group w-25 float-end">
                        <input type="text" name="search" class="form-control" placeholder="Upiši traženi pojam..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Pretraži</button>
                    </div>
                  </form>

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
                        @php ($count = 1)
                        @foreach ($products as $product)
                                <tr>
                                    <td class="align-middle text-right">{{ $count++ }}</td>
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

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $products->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Novi proizvod</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- popup content -->
        <form method="POST" action="/proizvodi" id="productSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                  <label for="name">Naziv proizvoda:</label>
                  <input type="text" class="form-control" placeholder="Unesi naziv novog proizvoda..." id="name" name="name">

                  <label for="product_type_id">Vrsta proizvoda:</label><br>
                  <select class="form-select searchable-select-modal" id="product_type_id" name="product_type_id">
                      <option selected>Odaberi vrstu proizvoda...</option>
                      @foreach ($productTypes as $productType)
                        <option value="{{ $productType->id }}">{{ $productType->name }}</option>                                  
                      @endforeach
                  </select>

                  <label for="default_price">Standardna cijena:</label>
                  <input type="number" class="form-control" placeholder="Unesi standardnu cijenu" id="default_price" name="default_price" step=".01">

              </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="productSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection