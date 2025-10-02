@extends('layouts.app')

@section('title', 'Omnius Art | Vrste proizvoda')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Nova vrsta proizvoda</button>

                  @include('includes.tablesearch')

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Vrsta proizvoda</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($productTypes as $productType)
                          <tr>
                              <td class="align-middle text-right">{{ $productTypes->firstItem() + $loop->index }}</td>
                              <td class="align-middle text-right">
                                <span class="editable" data-id="{{ $productType->id }}" data-field="name" data-model="update-product-type">{{ $productType->name }}</span>
                              </td>
                              <td>
                                <button class="btn btn-danger delete-btn-x" data-id="{{ $productType->id }}" data-model="delete-product-type"><i class="bi bi-x-lg"></i>
                                </button>
                              </td>
                          <tr>
                        @endforeach
                      </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $productTypes->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nova vrsta proizvoda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/vrste-proizvoda" id="productTypeSubmmission">
          {{ csrf_field() }}
              <div class="form-group">

                  <label for="name">Vrsta proizvoda:</label>
                  <input type="text" class="form-control" placeholder="Unesi novu vrstu proizvoda..." id="name" name="name">

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="productTypeSubmmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection