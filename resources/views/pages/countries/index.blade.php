@extends('layouts.app')

@section('title', 'Omnius Art | Države poslovanja')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#countryModal"><i class="bi bi-file-earmark-plus"></i> Nova država</button>

                  <x-search-form/>

                  <table class="table table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Naziv države</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($countries as $country)
                        <tr>
                            <td class="align-middle text-right">{{ $countries->firstItem() + $loop->index }}</td>
                            <td class="align-middle text-right">
                              <span class="editable" data-id="{{ $country->id }}" data-field="name" data-model="drzave-poslovanja">{{ $country->name }}</span>
                            </td>
                            <td>
                              <button class="btn btn-danger delete-btn-x" data-id="{{ $country->id }}" data-model="drzave-poslovanja"><i class="bi bi-x-lg"></i>
                              </button>
                            </td>
                        <tr>
                      @endforeach
                    </tbody>
                  </table>

                  <x-table-pagination :items="$countries" />

                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.shared.modals.add-country')
@include('includes.shared.modals.delete-confirmation')

@endsection