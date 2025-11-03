@extends('layouts.app')

@section('title', 'Omnius Art | Dostavne usluge')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">          
      <div class="card">
        <div class="card-body">
        
          <!-- Button to trigger the pop-up -->
          <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Nova dostavna služba</button>

          <x-search-form/>
            
          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Naziv</th>
                <th scope="col">Dostavna služba</th>
                <th scope="col">Standardna cijena</th>
                <th scope="col">Vidljivost</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($deliveryServices as $deliveryService)
                <tr>
                    <td class="align-middle text-right">
                      {{ $deliveryServices->firstItem() + $loop->index }}
                    </td>

                    <td class="align-middle text-right">
                      {{-- Name --}}
                      <span class="editable" data-id="{{ $deliveryService->id }}" data-field="name" data-model="dostavne-usluge">{{ $deliveryService->name }}</span>
                    </td>

                    <td class="align-middle text-right">
                      {{-- Company --}}
                      <div class="editable-select" data-id="{{ $deliveryService->id }}" data-field="delivery_company_id" data-model="dostavne-usluge">

                        <!-- Display the selected value -->
                        <span>{{ $deliveryService->delivery_company_name }}</span>
                        
                        <!-- Hidden select element with options -->
                        <select class="edit-select form-select" style="display: none !important">
                          <option value="" selected>Odaberi kanal prodaje...</option>
                            @foreach ($deliveryCompanies as $deliveryCompany)
                            <option value="{{ $deliveryCompany->id }}">{{ $deliveryCompany->name }}</option>
                            @endforeach 
                        </select>
                      </div>
                    </td>

                    <td class="align-middle text-right">
                      {{-- Cost --}}
                      <span class="editable" data-id="{{ $deliveryService->id }}" data-field="default_cost" data-model="dostavne-usluge">{{ $deliveryService->default_cost }}</span> €
                    </td>

                    <td class="align-middle text-right">
                      {{-- Is used selector --}}
                      <div class="form-check form-switch delivery-service-item" data-id="{{ $deliveryService->id }}" data-model="dostavne-usluge">
                        <input class="form-check-input edit-checkbox-delivery-service" type="checkbox" name="in_use" id="flexSwitchCheckDefault" {{ $deliveryService->in_use ? 'checked' : '' }}>
                      </div>
                    </td>

                    <td>
                      <button class="btn btn-danger delete-btn-x" data-id="{{ $deliveryService->id }}" data-model="dostavne-usluge"><i class="bi bi-x-lg"></i>
                      </button>
                    </td>
                <tr>
              @endforeach
            </tbody>
          </table>

          <x-table-pagination :items="$deliveryServices" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.delivery-services.includes.index.modals.add-delivery-service')
@include('includes.shared.modals.delete-confirmation')

@endsection