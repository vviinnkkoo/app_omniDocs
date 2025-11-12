@extends('layouts.app')

@section('title', 'Omnius Art | Kupci')

@section('content')
<div class="container-fluid px-2 px-lg-5">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          
          <x-modal-button target="#addCustomerModal" text="Novi kupac"/>
          <x-search-form/>
            
          <table class="table table-hover" id="escalation">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Ime</th>
                <th scope="col">Email</th>
                <th scope="col">Mobitel</th>
                <th scope="col" class="text-end">Adresa</th>
                <th scope="col">Kućni broj</th>
                <th scope="col">Grad</th>
                <th scope="col">Država</th>
                <th scope="col">PBR</th>
                <th scope="col">Sveukupno naručeno</th>
                <th></th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($customers as $customer)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle">
                    {{ $customers->firstItem() + $loop->index }}
                  </td>

                  {{-- Name --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="name" data-model="kupci">{{ $customer->name }}</span>
                  </td>

                  {{-- Email --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="email" data-model="kupci">{{ $customer->email }}</span>
                  </td>

                  {{-- Phone --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="phone" data-model="kupci">{{ $customer->phone }}</span>
                  </td>

                  {{-- Address --}}
                  <td class="align-middle text-end">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="address" data-model="kupci">{{ $customer->address }}</span>
                  </td>

                  {{-- House number --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="house_number" data-model="kupci">{{ $customer->house_number }}</span>
                  </td>

                  {{-- City --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="city" data-model="kupci">{{ $customer->city }}</span>
                  </td>

                  {{-- Country --}}
                  <td class="align-middle">
                    <div class="editable-select" data-id="{{ $customer->id }}" data-field="country_id" data-model="kupci">                          
                      {{-- Display the selected value --}}
                      <span>{{ $customer->country->name }}</span>                              
                      {{-- Hidden select element with options --}}
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi državu...</option>
                          @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>                                  
                          @endforeach 
                      </select>
                    </div>
                  </td>

                  {{-- Postal code --}}
                  <td class="align-middle">
                    <span class="editable" data-id="{{ $customer->id }}" data-field="postal" data-model="kupci">{{ $customer->postal }}</span>
                  </td>

                  {{-- Total ordered amount --}}
                  <td class="align-middle text-end">
                    <span>{{ $customer->formattedTotalOrderedAmount }} €</span>
                  </td>

                  {{-- All orders button --}}
                  <td>
                    <a href="{{ route('narudzbe.indexByType', ['type' => 'kupac', 'customerId' => $customer->id]) }}" class="btn btn-primary">
                      </i> Sve narudžbe <span class="badge badge-secondary" style="background-color:darkgreen">{{ $customer->orders_count }}</span>
                    </a>
                  </td>

                  {{-- Delete button --}}
                  <td class="align-middle text-center px-4">
                    <x-delete-button :id="$customer->id" model="kupci" />
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <x-table-pagination :items="$customers" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('includes.shared.modals.add-customer')
@include('includes.shared.modals.delete-confirmation')

@endsection