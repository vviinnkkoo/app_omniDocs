@extends('layouts.app')

@section('title', 'Omnius Art | Boje / Opisi proizvoda')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-12">          
        <div class="card">
          <div class="card-body">
            <!-- Button to trigger the pop-up -->
            <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Nova boja proizvoda</button>

            <x-search-form/>

              <table class="table table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Boja / Opis proizvoda</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($colors as $color)
                    <tr>
                      <td class="align-middle text-right">{{ $colors->firstItem() + $loop->index }}</td>
                      <td class="align-middle text-right">
                        <span class="editable" data-id="{{ $color->id }}" data-field="name" data-model="opis">{{ $color->name }}</span>
                      </td>
                      <td>
                        <button class="btn btn-danger delete-btn-x" data-id="{{ $color->id }}" data-model="opis"><i class="bi bi-x-lg"></i>
                        </button>
                      </td>
                    <tr>
                  @endforeach
                </tbody>
              </table>
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
        <h5 class="modal-title" id="exampleModalLabel">Nova boja proizvoda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/opis" id="colorSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                  <label for="color">Boja:</label>
                  <input type="text" class="form-control" placeholder="Unesi novu boju..." id="color" name="name">

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="colorSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.shared.modals.delete-confirmation')

@endsection