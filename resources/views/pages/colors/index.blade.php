@extends('layouts.app')

@section('title', 'Omnius Art | Boje / Opisi proizvoda')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-12">          
        <div class="card">
          <div class="card-body">
            <!-- Button to trigger the pop-up -->
            <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#colorModal"><i class="bi bi-file-earmark-plus"></i> Nova boja proizvoda</button>

            <x-search-form/>

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Boja / Opis proizvoda</th>
                  <th class="delete-column"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($colors as $color)
                  <tr>
                    <td class="align-middle">{{ $colors->firstItem() + $loop->index }}</td>
                    <td class="align-middle">
                      <span class="editable" data-id="{{ $color->id }}" data-field="name" data-model="opis">{{ $color->name }}</span>
                    </td>
                    <td class="align-middle text-center px-4">
                      <x-delete-button :id="$color->id" model="opis" />
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <x-table-pagination :items="$colors" />

          </div>
        </div>
      </div>
    </div>
</div>

@include('includes.shared.modals.add-color')
@include('includes.shared.modals.delete-confirmation')

@endsection