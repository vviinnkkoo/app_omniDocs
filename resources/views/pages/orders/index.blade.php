@extends('layouts.app')

@section('title', 'Omnius Art | Narudžbe')

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">

    <div class="row justify-content-center">
      <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                   <!-- New order and customer buttons, search bar section -->
                  <div class="clearfix mb-3">
                    <button id="popupButton" class="btn btn-primary float-start mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <i class="bi bi-file-earmark-plus"></i> Nova narudžba
                    </button>
                    <button id="popupButton" class="btn btn-primary float-start ms-2 mb-2" data-bs-toggle="modal" data-bs-target="#customerModal">
                      <i class="bi bi-file-earmark-plus"></i> Novi kupac
                    </button>

                    <form method="GET" action="{{ $currentUrl }}" class="float-end mb-2">
                      <div class="input-group w-25">
                        <input type="text" name="search" class="form-control" placeholder="Upiši traženi pojam..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Pretraži</button>
                      </div>
                    </form>
                  </div>

                  <!-- Order type filter buttons -->
                  <div class="clearfix mb-3">
                    <a class="btn btn-primary btn-sm float-start" href="/narudzbe/prikaz/sve">Sve narudžbe</a>
                    <a class="btn btn-success btn-sm ms-1 float-start" href="/narudzbe/prikaz/poslane">Poslane narudžbe</a>
                    <a class="btn btn-warning btn-sm ms-1 float-start" href="/narudzbe/prikaz/neodradene">Neodrađene narudžbe</a>
                    <a class="btn btn-danger btn-sm ms-1 float-start" href="/narudzbe/prikaz/otkazane">Otkazane</a>
                  </div>

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>                          
                          <th scope="col">ID</th>
                          <th scope="col">Kupac</th>
                          <th scope="col">Datum</th>
                          <th scope="col">Izvor</th>
                          <th scope="col">Poštanski br.</th>
                          <th scope="col">Dostava</th>
                          <th scope="col">Plaćanje</th>
                          <th scope="col">Status</th>
                          <th scope="col">Broj pošiljke</th>
                          <th scope="col">Iznos</th>
                          <th scope="col">Račun</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($orders as $order)
                                
                                @if (isset($order->date_cancelled))
                                    <tr class="cancelled-order">
                                @else
                                    <tr>
                                @endif

                                    <td class="align-middle text-right">{{ $order->id }}</td>

                                    <td class="align-middle text-right"><a class="btn btn-sm btn-primary position-relative" href="/narudzbe/{{ $order->id }}">{{ $order->customer_name }}
                                        @if ($order->isOrderDone() && is_null($order->date_sent))
                                          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                            <i class="bi bi-check"></i>
                                          </span>
                                        @endif
                                      </a>
                                    </td>

                                    <td class="align-middle text-right">{{ $order->formated_date_ordered }}</td>
                                    <td class="align-middle text-right">{{ $order->source_name }}</td>
                                    <td class="align-middle text-right">{{ $order->delivery_postal }}</td>
                                    <td class="align-middle text-right">{{ $order->delivery_company_name }} - {{ $order->delivery_service_name }}</td>
                                    <td class="align-middle text-right">{{ $order->payment_type_name }}</td>

                                    {{-- Status --}}
                                    <td class="align-middle text-right">

                                      {{-- Check if cancelled --}}
                                      @if (isset($order->date_cancelled))
                                        <span class="btn btn-sm btn-dark">Otkazano<br>
                                          <span style="font-size: 80%"><b>{{ $order->formated_date_cancelled }}</b></span>
                                        </span>

                                      {{-- Check if delivered --}}
                                      @elseif (isset($order->date_delivered))
                                        <span class="btn btn-sm btn-success">Dostavljeno<br>
                                          <span style="font-size: 80%">{{ $order->formated_date_delivered }} (<b>{{ $order->daysToDeliver }} d</b>)</span>
                                        </span>

                                      {{-- If not delivered, check if it's sent --}}
                                      @elseif (isset($order->date_sent))
                                        <span class="btn btn-sm btn-secondary">Poslano<br>
                                          <span style="font-size: 80%">{{ $order->formated_date_sent }}</span>
                                        </span>

                                      {{-- Deadline check --}}
                                      @else
                                        @if (isset($order->date_deadline))
                                          @if ($order->days_left)
                                            <span class="btn btn-sm {{ $order->deadline_class }}">
                                              Rok: <b>{{ $order->days_left }} d</b><br>
                                                <span style="font-size: 80%">{{ $order->formated_date_deadline }}</span>
                                            </span>
                                          @else
                                            <span class="text-danger">Prošao rok</span>
                                          @endif
                                        @else
                                          <span class="text-muted">Nema</span>
                                        @endif
                                      @endif

                                    </td>

                                    {{-- Tracking number --}}
                                    <td class="align-middle text-right">
                                      @include("includes.tracking-code-condition")                                    
                                    </td>

                                    {{-- Total --}}
                                    @if ($order->total_amount == 0)
                                      <td></td>
                                    @else
                                    <td class="align-middle text-right">{{ number_format($order->total_amount, 2, ',') }} €</td>
                                    @endif
                                    
                                    {{-- Has invoice --}}
                                    <td class="align-middle text-right">
                                      @isset($order->receipt_id)
                                      <a href="/dokument/racun/{{ $order->receipt_id }}" target="_blank" 
                                          class="btn {{ $order->is_paid ? 'btn-success' : 'btn-danger' }}">
                                          <i class="bi bi-filetype-pdf"></i></a>
                                      @else
                                        Nema
                                      @endisset
                                      
                                    </td>
                                <tr>
                        @endforeach
                      </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $orders->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Order modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nova narudžba</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/narudzbe" id="orderSubmission">
          {{ csrf_field() }}
          <div class="form-group">

            {{-- Customer --}}
            <div class="mb-3">
              <label for="customer_id">Kupac:</label>
              <select class="form-select searchable-select-modal" id="customer_id" name="customer_id" required>
                <option disabled selected>Odaberi kupca...</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->city }}</option>                                  
                @endforeach
              </select>
            </div>

            {{-- Order date --}}
            <div class="mb-3">
              <label for="date_ordered">Datum narudžbe:</label>
              <input type="date" class="form-control" id="date_ordered" name="date_ordered" required>
            </div>

            {{-- Delivery ETA --}}
            <div class="mb-3">
              <label for="date_ordered">Krajnji rok za isporuku:</label>
              <input type="date" class="form-control" id="date_deadline" name="date_deadline" required>
            </div>          

            {{-- Sales channel --}}
            <div class="mb-3">
              <label for="source_id">Kanal prodaje:</label>
              <select class="form-select searchable-select-modal" id="source_id" name="source_id" required>
                <option disabled selected>Odaberi kanal prodaje...</option>
                @foreach ($sources as $source)
                  <option value="{{ $source->id }}">{{ $source->name }}</option>                                  
                @endforeach
              </select>
            </div>

            {{-- Delivery service --}}
            <div class="mb-3">
              <label for="delivery_service_id">Dostavna služba:</label>
              <select class="form-select searchable-select-modal" id="delivery_service_id" name="delivery_service_id" required>

                <option disabled selected>Odaberi dostavnu službu...</option>

                @foreach ($deliveryCompanies as $company)
                  <optgroup label="{{ $company->name }}">
                    @foreach ($company->deliveryServices as $service)
                      @if ($service->in_use == 1)
                        <option value="{{ $service->id }}">{{ $service->name }} >> {{ $service->default_cost }} €</option>
                      @endif
                    @endforeach
                @endforeach

              </select>
            </div>

            {{-- Payment type --}}
            <div class="mb-3">
              <span style="display: block;">Način plaćanja:</span>
                @foreach ($paymentTypes as $paymentType)
                  <input type="radio" class="btn-check" name="payment_type_id" autocomplete="off" value="{{ $paymentType->id }} " id="option{{ $paymentType->id }}" required>
                  <label class="btn btn-secondary btn-sm me-2 mb-1" for="option{{ $paymentType->id }}">{{ $paymentType->name }}</label>
                @endforeach
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="orderSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModal" aria-hidden="true">
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

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>