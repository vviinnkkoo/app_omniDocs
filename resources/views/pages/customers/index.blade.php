@extends('layouts.app')

@section('title', 'Omnius Art | Kupci')

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">
                <div class="card-body">
                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary float-start" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#customerModal"><i class="bi bi-file-earmark-plus"></i> Novi kupac</button>

                  {{-- @include('includes.tablesearch') --}}

                  <form method="GET" action="/kupci" class="mb-3">
                    <div class="input-group w-25 float-end">
                        <input type="text" name="search" class="form-control" placeholder="Upiši traženi pojam..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Pretraži</button>
                    </div>
                  </form>
                    
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
                          <th></th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @php ($count = 1)
                        @foreach ($customers as $customer)
                                <tr>

                                    <td class="align-middle text-right">
                                      {{ $count++ }}
                                    </td>

                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="name" data-model="kupci">{{ $customer->name }}</span>
                                    </td>

                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="email" data-model="kupci">{{ $customer->email }}</span>
                                    </td>

                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="phone" data-model="kupci">{{ $customer->phone }}</span>
                                    </td>

                                    <td class="align-middle text-end">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="address" data-model="kupci">{{ $customer->address }}</span>
                                    </td>

                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="house_number" data-model="kupci">{{ $customer->house_number }}</span>
                                    </td>

                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="city" data-model="kupci">{{ $customer->city }}</span>
                                    </td>

                                    <td class="align-middle text-right">

                                      <div class="editable-select" data-id="{{ $customer->id }}" data-field="country_id" data-model="kupci">
                                      

                                        <!-- Display the selected value -->
                                        <span>{{ $customer->country->name }}</span>
                                        
                                        <!-- Hidden select element with options -->
                                        <select class="edit-select form-select" style="display: none !important">
                                          <option value="" selected>Odaberi državu...</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>                                  
                                            @endforeach 
                                        </select>
                                    </div>

                                    </td>
                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $customer->id }}" data-field="postal" data-model="kupci">{{ $customer->postal }}</span>
                                    </td>

                                    <td class="align-middle text-right">
                                      <span>{{ $customer->formattedTotalOrderedAmount }} €</span>
                                    </td>

                                    <td>
                                      <a href="/narudzbe/prikaz/kupac/{{ $customer->id }}" class="btn btn-primary"><i class="bi bi-box-arrow-up-right"></i>
                                        </i> Sve narudžbe <span class="badge badge-secondary" style="background-color:green">{{ $customer->orders_count }}</span>
                                      </a>
                                    </td>

                                    <td>
                                      <button class="btn btn-danger delete-btn-x" data-id="{{ $customer->id }}" data-model="kupci"><i class="bi bi-x-lg"></i>
                                      </button>
                                    </td>

                                <tr>
                        @endforeach
                      </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $customers->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Novi kupac</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/kupci" id="customerSubmission">
          @include('includes.form-fields-customers')
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="customerSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>