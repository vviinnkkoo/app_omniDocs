@extends('layouts.app')

@section('title', $order->id . ' - ' . $order->customer_name . ' - Uredi narudžbu' )

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">
  <div class="row g-2 justify-content-center">
    <div class="col-md-12">
      <div class="card">

        {{-- Order info --}}
        <div class="card-header d-flex align-items-center" style="font-weight: 900;">
          {{-- Header left side --}}
          <a class="gray-mark-extra" href="/narudzbe/prikaz/neodradene"><i class="bi bi-arrow-left"></i></a>
          <span style="font-size:100%; margin-left:10px;">Narudžba: {{$order->id}}</span>
          <span style="font-size:100%; margin-left:30px;" class="badge bg-secondary">Naručeno: {{ number_format($order->subtotal, 2, ',') }} € </span>
          <span style="font-size:100%; margin-left:15px; color:#333" class="badge bg-warning">Dostava: {{ number_format($order->delivery_cost, 2, ',') }} €</span>
          <span style="font-size:100%; margin-left:15px;" >>></span>
          <span style="font-size:100%; margin-left:15px; margin-right:30px;" class="badge bg-success">Sveukupno: {{ number_format($order->total, 2, ',') }} €</span>
          {{-- Header right side --}}
          {{-- Invoice check START --}}
          <span class="ms-auto">Račun:
            @isset($order->receipt_id)
              <a href="/dokument/racun/{{ $order->receipt_id }}" target="_blank"
                class="btn {{ $order->is_paid ? 'btn-success' : 'btn-danger' }} btn-sm"><i class="bi bi-filetype-pdf"></i> {{ $order->is_paid ? 'Plaćen' : 'Nenaplaćen' }}</a>
            @else
              <button id="popupButton" class="btn btn-primary btn-sm" style="font-weight:bold;" data-bs-toggle="modal" data-bs-target="#invoiceModal"><i class="bi bi-file-earmark-plus"></i> Izradi</button>
            @endisset
          </span>
          {{-- Invoice check END --}}
          <div style="width:4px; background-color:#333; margin-left:10px;"></div>
          <a class="btn bg-warning btn-sm" style="margin-left:10px; color:#333; font-weight:bold;" href="/dokument/ponuda/{{$order->id}}" target="_blank"><i class="bi bi-file-pdf-fill"></i> Ponuda</a>
          <a class="btn bg-info btn-sm" style="margin-left:10px; color:#333; font-weight:bold;" href="/dokument/otpremnica/{{$order->id}}" target="_blank"><i class="bi bi-file-pdf-fill"></i> Otpremnica</a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">

              <div>
                <h5>Kupac:</h5>
              </div>

              <div>
                <h6 style="font-weight: 900">{{ $order->customer_name }}</h6>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_address" data-model="narudzbe">{{ $order->delivery_address }}</span>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_postal" data-model="narudzbe">{{ $order->delivery_postal }}</span>, 
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_city" data-model="narudzbe">{{ $order->delivery_city }}</span>
              </div>


              <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_country_id" data-model="narudzbe">
                <!-- Display the selected value -->
                <span>{{ $order->countryName }}</span>
                <!-- Hidden select element with options -->
                <select class="edit-select form-select" style="display: none !important">
                  <option value="" selected>Odaberi državu...</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach 
                </select>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_email" data-model="narudzbe">{{ $order->delivery_email }}</span>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_phone" data-model="narudzbe">{{ $order->delivery_phone }}</span>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <div>Naručeno:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_ordered" data-model="narudzbe">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->input_formated_date_ordered }}">
                </div>
              </div>

              <div class="mb-3">
                <div>Rok za dostavu:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_deadline" data-model="narudzbe">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->input_formated_date_deadline }}">
                </div>
              </div>

              <div class="mb-3">                
                <div>Način plaćanja:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="payment_type_id" data-model="narudzbe">

                  <!-- Display the selected value -->
                  <span class="gray-mark">{{ $order->paymentTypeName }}</span>
                  
                  <!-- Hidden select element with options -->
                  <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi način plaćanja...</option>
                      @foreach ($paymentTypes as $paymentType)
                      <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                      @endforeach 
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <div>Kanal prodaje:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="source_id" data-model="narudzbe">

                  <!-- Display the selected value -->
                  <span class="gray-mark">{{ $order->source_name }}</span>
                  
                  <!-- Hidden select element with options -->
                  <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi kanal prodaje...</option>
                      @foreach ($sources as $source)
                      <option value="{{ $source->id }}">{{ $source->name }}</option>
                      @endforeach 
                  </select>
                </div>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <div>Datum slanja:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_sent" data-model="narudzbe">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->input_formated_date_sent }}">
                </div>
              </div>

              <div class="mb-3">
                <div>Dostavna služba:</div>
                  <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_service_id" data-model="narudzbe">

                    <!-- Display the selected value -->
                    <span class="gray-mark">{{ $order->delivery_company_name }} - {{ $order->delivery_service_name }}</span>
                    
                    <!-- Hidden select element with options -->
                    <select class="edit-select form-select" style="display: none !important">

                      <option selected>Odaberi dostavnu službu...</option>

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
              </div>

              <div class="mb-3">
                <div>Kod za praćenje:</div>
                <div>
                  <span class="editable gray-mark" data-id="{{ $order->id }}" data-field="tracking_code" data-model="narudzbe">{{ $order->tracking_code }}</span>&nbsp;
                  <span>
                    @include("includes.tracking-code-condition")
                  </span>
                </div>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <span><i class="bi bi-check-circle-fill" style="color: green;"></i> Datum dostave / završetka:</span>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_delivered" data-model="narudzbe">
                  <input type="date" class="form-control" style="width:80%;" value="{{ $order->input_formated_date_delivered }}">
                </div>
              </div>

              <div class="mb-3">
                <span><i class="bi bi-x-circle-fill" style="color: red;"></i> Datum otkazivanja:</span>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_cancelled" data-model="narudzbe">
                  <input type="date" class="form-control" style="width:80%;" value="{{ $order->input_formated_date_cancelled }}">
                </div>                
              </div>

            </div>            
          </div>
        </div>
      </div>
    </div>

    {{-- Order item list part --}}
    <div class="col-xl-12">
      <div class="card" style="margin-top: 30px;">
        <div class="card-header d-flex align-items-center" style="font-weight: 900; background-color: #19875411;">
          <span class="me-2">Proizvodi</span>
          <button id="popupButton" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-weight: 900;">+</button>
        </div>
        <div class="card-body" style=" border: solid 4px #19875411">
          <table class="table table-hover">
            <thead class="table-secondary">
              <tr>                          
                <th scope="col">#</th>
                <th scope="col">Proizvod</th>
                <th scope="col">Boja</th>
                <th scope="col">Količina</th>
                <th scope="col">Cijena</th>
                <th scope="col">Popust</th>
                <th scope="col">Opis</th>
                <th scope="col">Opis na računu</th>
                <th scope="col">Status izrade</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @php ($count = 1)
              @foreach ($orderItemList as $item)
                <tr>
                  {{-- # --}}
                  <td class="align-middle text-right">{{ $count++ }}</td>

                  {{-- Proizvod --}}
                  <td class="align-middle text-right">
                    <div class="editable-select" data-id="{{ $item->id }}" data-field="product_id" data-model="order-item-list">
                      <!-- Display the selected value -->
                      <span>{{ $item->productName }}</span>
                      
                      <!-- Hidden select element with options -->
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi proizvod...</option>
                          @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>                                  
                          @endforeach 
                      </select>
                    </div>
                  </td>

                  {{-- Boja --}}
                  <td class="align-middle text-right">
                    <div class="editable-select" data-id="{{ $item->id }}" data-field="color_id" data-model="order-item-list">
                      <!-- Display the selected value -->
                      <span>{{ $item->colorName }}</span>
                      
                      <!-- Hidden select element with options -->
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi boju...</option>
                          @foreach ($colors as $color)
                          <option value="{{ $color->id }}">{{ $color->name }}</option>
                          @endforeach 
                      </select>
                    </div>
                  </td>

                  {{-- Količina --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $item->id }}" data-field="amount" data-model="order-item-list">{{ $item->formattedAmount }}</span> {{ $item->unit }}
                  </td>

                  {{-- Cijena --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $item->id }}" data-field="price" data-model="order-item-list">{{ $item->price }}</span> €
                  </td>

                  {{-- Popust --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $item->id }}" data-field="discount" data-model="order-item-list">{{ $item->discount }}</span> %
                  </td>

                  {{-- Opis --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $item->id }}" data-field="note" data-model="order-item-list">{{ $item->note }}</span>
                  </td>

                  {{-- Prikaz napomene na računu --}}
                  <td class="align-middle text-right">
                    <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="note-on-invoice">
                      <input class="form-check-input edit-checkbox" type="checkbox" name="note_on_invoice" id="flexSwitchCheckDefault" {{ $item->note_on_invoice ? 'checked' : '' }}>
                    </div>
                  </td>

                  {{-- Status izrade --}}
                  <td class="align-middle text-right">
                    <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="order-item-list">
                      <input class="form-check-input edit-checkbox" type="checkbox" name="is_done" id="flexSwitchCheckDefault" {{ $item->is_done ? 'checked' : '' }}>
                    </div>
                  </td>

                  {{-- Delete button --}}
                  <td>
                    <x-delete-button :id="$item->id" model="order-item-list" />
                  </td>
                <tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Notes part --}}
    <div class="row g-1" style="margin-top: 30px;">

      {{-- Paketi (70% desktop, 100% mobile) --}}
      <div class="col-12 col-lg-8 mb-3">
          <div class="card">
              <div class="card-header" style="font-weight: 900; background-color: #007bff11;">
                  Paketi
              </div>
              <div class="card-body" style="border: solid 4px #007bff11; min-height:200px;">
                  <p class="text-muted">Ovdje ide sadržaj za pakete...</p>
              </div>
          </div>
      </div>

      {{-- Napomene (30% desktop, 100% mobile) --}}
      <div class="col-12 col-lg-4 mb-3">
        <div class="card">
          <div class="card-header d-flex align-items-center" style="font-weight: 900; background-color: #ffc10711;">
            <span class="me-2">Napomene</span>
            <button id="popupButton" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#expensesModal" style="font-weight: 900;">+</button>
          </div>

          <div class="card-body" style="border: solid 4px #ffc10711">
            <div class="row">
              @foreach ($orderNotes as $item)
                <div class="col-12 col-md-6 mb-3 ajax-deletable"> {{-- 2 u redu na desktopu --}}
                  <div class="p-3 rounded" style="background:#f8f9fa; border:1px solid #dee2e6;">

                    {{-- Header (datum + delete) --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <small class="text-muted">{{ $item->created_at->format('d. m. Y. H:i') }}</small>
                      <x-delete-button :id="$item->id" model="napomena" />
                    </div>

                    {{-- Napomena --}}
                    <div>
                      <span class="editable" data-id="{{ $item->id }}" data-field="note" data-model="napomena"> {{ $item->note }}</span>
                    </div>

                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


{{-- Modal for notes --}}
<div class="modal fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expensesModalLabel">Dodaj novu napomenu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- popup content --}}
        <form method="POST" action="/napomena" id="noteForOrderSubmission">
          @csrf
              <div class="form-group">

                <div class="mb-3">
                  <label for="note">Napomena:</label>
                  <textarea class="form-control" placeholder="Unesi dodatni opis..." id="note" name="note" rows="3">- - -</textarea>
                  <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
                </div>

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="noteForOrderSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>


{{-- Modal for products --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dodaj novi proizvod</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="/update-order-products/{{ $order->id}}" id="productForOrderSubmission">
          {{ csrf_field() }}
          <div class="form-group">

            {{-- Product type --}}
            <div class="mb-3">
              <label for="product_id">Proizvod:</label><br>
              <select class="form-select searchable-select-modal" id="product_id" name="product_id">
                  <option selected>Odaberi proizvod...</option>
                  @foreach ($productTypes as $productType)
                    <optgroup label="{{ $productType->name }}">
                      @foreach ($productType->product as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} :: {{ $product->default_price }} €</option>
                      @endforeach
                  @endforeach
              </select>
            </div>

            {{-- Amount --}}
            <div class="mb-3">
              <label for="amount">Količina:</label>
              <input type="number" class="form-control" placeholder="Unesi količinu proizvoda..." id="amount" name="amount" step="1" required>
            </div>

            {{-- Price --}}
            <div class="mb-3">
              <label for="price">Cijena:</label>
              <input type="number" class="form-control" placeholder="Unesi cijenu proizvoda..." id="price" name="price" step=".01" required>
            </div>

            {{-- Discount --}}
            <div class="mb-3">
              <label for="price">Popust (%):</label>
              <input type="number" class="form-control" value="0" min="0" max="100" id="price" name="discount" step="1" required>
            </div>
            
            {{-- Product color --}}
            <div class="mb-3">
              <span style="display: block;">Boja proizvoda:</span>
                @foreach ($colors as $color)
                  <input type="radio" class="btn-check" name="color_id" autocomplete="off" value="{{ $color->id }} " id="color_{{ $color->id }}" required>
                  <label class="btn btn-secondary btn-sm me-1 mb-1" for="color_{{ $color->id }}">{{ $color->name }}</label>
                @endforeach
            </div>

            {{-- Product note --}}
            <div class="mb-3">
              <label for="note">Komentar / opis:</label>
              <textarea class="form-control" placeholder="Unesi dodatni opis..." id="note" name="note" rows="3">- - -</textarea>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="productForOrderSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

<!-- Invoice modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel">Novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/racuni" id="receiptSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="number">Redni broj računa:</label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="Unesi redni broj računa..." id="number" name="number" value="{{ $latestReceiptNumber }}" required>
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2" id="refresh-number-btn">
                      🔄
                      <div id="numberLoader" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                    </button>
                  </div>
                </div>

                <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}" required>

                <div class="mb-3">
                  <label for="year">Godina računa:</label>
                  <select class="form-select searchable-select-modal" id="year" name="year" required>
                    @foreach ($workYears as $workYear)
                      <option {{ $loop->last ? 'selected' : '' }}>{{ $workYear->year }}</option>
                    @endforeach
                  </select>
                </div>

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="receiptSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection