@extends('layouts.app')

@section('title', 'Omnius Art | Dostavne usluge')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">          
      <div class="card">
        <div class="card-body">
          
          <x-buttons.open-modal target="#addDeliveryServiceModal" text="Nova dostavna služba"/>
          <x-misc.search-form/>
            
          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Naziv</th>
                <th scope="col">Dostavna služba</th>
                <th scope="col">Standardna cijena</th>
                <th scope="col">Vidljivost</th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($deliveryServices as $deliveryService)
                <tr>

                    {{-- Index number --}}
                    <td class="align-middle text-right">
                      {{ $deliveryServices->firstItem() + $loop->index }}
                    </td>

                    {{-- Delivery service name --}}
                    <td class="align-middle text-right">                      
                      {{-- <span class="editable" data-id="{{ $deliveryService->id }}" data-field="name" data-model="dostavne-usluge">{{ $deliveryService->name }}</span> --}}
                      <x-editable.text :model="$deliveryService" field="name" modelName="dostavne-usluge" :value="$deliveryService->name" simple="true"/>
                    </td>

                    {{-- Delivery company selector --}}
                    <td class="align-middle text-right">
                      <div class="editable-select" data-id="{{ $deliveryService->id }}" data-field="delivery_company_id" data-model="dostavne-usluge">
                        {{-- Display the selected value --}}
                        <span>
                          {{ $deliveryService->delivery_company_name }}
                        </span>                      
                        {{-- Hidden select element with options --}}
                        <select class="edit-select form-select" style="display: none !important">
                          <option value="" selected>Odaberi kanal prodaje...</option>
                            @foreach ($deliveryCompanies as $deliveryCompany)
                              <option value="{{ $deliveryCompany->id }}">{{ $deliveryCompany->name }}</option>
                            @endforeach 
                        </select>
                      </div>
                    </td>

                    {{-- Cost --}}
                    <td class="align-middle text-right">                    
                      <x-editable.text :model="$deliveryService" field="name" modelName="dostavne-usluge" :value="$deliveryService->default_price" simple="true" suffix=" €"/>
                    </td>

                    {{-- Visibility selector --}}
                    <td class="align-middle text-right">
                      <div class="form-check form-switch delivery-service-item" data-id="{{ $deliveryService->id }}" data-model="vidljivost-dostave">
                        <input class="form-check-input edit-checkbox-delivery-service" type="checkbox" name="in_use" id="flexSwitchCheckDefault" {{ $deliveryService->in_use ? 'checked' : '' }}>
                      </div>
                    </td>

                    {{-- Delete button --}}
                    <td class="align-middle text-center px-4">
                      <x-buttons.delete-item :id="$deliveryService->id" model="dostavne-usluge" />
                    </td>
                    
                  </tr>
              @endforeach
            </tbody>
          </table>

          <x-misc.table-pagination :items="$deliveryServices" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.delivery-services.includes.index.modals.add-delivery-service')
@include('includes.shared.modals.delete-confirmation')

@endsection