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

                @include('includes.tablesearch')
                  
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
                    @php ($count = 1)
                    @foreach ($deliveryServices as $deliveryService)
                      <tr>
                          <td class="align-middle text-right">
                            {{ $count++ }}
                          </td>

                          <td class="align-middle text-right">
                            {{-- Name --}}
                            <span class="editable" data-id="{{ $deliveryService->id }}" data-field="name" data-model="dostavne-usluge">{{ $deliveryService->name }}</span>
                          </td>

                          <td class="align-middle text-right">
                            {{-- Company --}}
                            <div class="editable-select" data-id="{{ $deliveryService->id }}" data-field="delivery_company_id" data-model="dostavne-usluge">

                              <!-- Display the selected value -->
                              <span>{{ App\Models\DeliveryCompany::find($deliveryService->delivery_company_id)->name }}</span>
                              
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
        <h5 class="modal-title" id="exampleModalLabel">Nova dostavna usluga</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Modal content -->
        <form method="POST" action="{{ route('dostavne-usluge.store') }}" id="deliveryServiceSubmission">
          @csrf      
          <div class="form-group">
              <div class="mb-3">
                  <label for="name">Naziv dostavne usluge:</label>
                  <input type="text" class="form-control" placeholder="Unesi naziv..." id="name" name="name">
              </div>
      
              <div class="mb-3">
                  <label for="company_id">Dostavna služba:</label><br>
                  <select class="form-select searchable-select-modal" id="company_id" name="company_id">
                      <option selected>Odaberi dostavnu službu</option>
                      @foreach ($deliveryCompanies as $deliveryCompany)
                          <option value="{{ $deliveryCompany->id }}">{{ $deliveryCompany->name }}</option>
                      @endforeach
                  </select>
              </div>
      
              <div class="mb-3">
                  <label for="default_cost">Standardna cijena:</label>
                  <input type="number" class="form-control" placeholder="Unesi standardnu cijenu" id="default_cost" name="default_cost" step=".01">
              </div>
          </div>
        </form>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="deliveryServiceSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection