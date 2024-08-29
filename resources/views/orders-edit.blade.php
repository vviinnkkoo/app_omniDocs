@extends('layouts.app')

 {{ $customerName = App\Models\Customer::find($order->customer_id)->name }}
 {{ $orderId = $order->id}}

@section('title', $orderId . ' - ' . $customerName . ' - Uredi narudžbu' )

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">
      <div class="card">

      {{-- ////////// --}}
      {{-- Order info --}}
      {{-- ////////// --}}

      <div class="card-header" style="font-weight: 900;"><a class="gray-mark-extra" href="/narudzbe/3"><i class="bi bi-arrow-left"></i></a>
        Naružba: {{$order->id}}
        <span style="font-size:100%; margin-left:30px;" class="badge bg-secondary">Naručeno: {{ $orderSum }} € </span>
        <span style="font-size:100%; margin-left:15px; color:#333" class="badge bg-warning">Dostava: {{ $deliveryCost }} €</span>
        <span style="font-size:100%; margin-left:15px;" >>></span>
        <span style="font-size:100%; margin-left:15px;" class="badge bg-success">Sveukupno: {{ $orderTotal }} €</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div><h5>Kupac:</h5></div>
              <div><h6 style="font-weight: 900">{{ App\Models\Customer::find($order->customer_id)->name }}</h6></div>
              
              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_address" data-model="order">{{ $order->delivery_address }}</span>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_postal" data-model="order">{{ $order->delivery_postal }}</span>, 
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_city" data-model="order">{{ $order->delivery_city }}</span>
              </div>


              <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_country_id" data-model="order">


                <!-- Display the selected value -->
                <span>{{ App\Models\Country::find($order->delivery_country_id)->country_name }}</span>

                <!-- Hidden select element with options -->
                <select class="edit-select form-select" style="display: none !important">
                  <option value="" selected>Odaberi državu...</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                    @endforeach 
                </select>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_email" data-model="order">{{ $order->delivery_email }}</span>
              </div>

              <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_phone" data-model="order">{{ $order->delivery_phone }}</span>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <div>Naručeno:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_ordered" data-model="order">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->date_ordered }}">
                </div>
              </div>

              <div class="mb-3">
                <div>Rok za dostavu:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_deadline" data-model="order">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->date_deadline }}">
                </div>
              </div>

              <div class="mb-3">                
                <div>Način plaćanja:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="payment_type_id" data-model="order">

                  <!-- Display the selected value -->
                  <span class="gray-mark">{{ App\Models\PaymentType::find($order->payment_type_id)->type_name }}</span>
                  
                  <!-- Hidden select element with options -->
                  <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi način plaćanja...</option>
                      @foreach ($paymentTypes as $paymentType)
                      <option value="{{ $paymentType->id }}">{{ $paymentType->type_name }}</option>
                      @endforeach 
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <div>Kanal prodaje:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="source_id" data-model="order">

                  <!-- Display the selected value -->
                  <span class="gray-mark">{{ App\Models\Source::find($order->source_id)->name }}</span>
                  
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
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_sent" data-model="order">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->date_sent }}">
                </div>
              </div>

              <div class="mb-3">
                <div>Dostavna služba:</div>
                  <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_service_id" data-model="order">

                    <!-- Display the selected value -->
                    <span class="gray-mark">{{ App\Models\DeliveryService::find($order->delivery_service_id)->name }}</span>
                    
                    <!-- Hidden select element with options -->
                    <select class="edit-select form-select" style="display: none !important">

                      <option selected>Odaberi dostavnu službu...</option>
  
                      @foreach ($deliveryCompanies as $company)
                        <optgroup label="{{ $company->name }}">
                          @foreach ($company->deliveryService as $service)
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
                  <span class="editable gray-mark" data-id="{{ $order->id }}" data-field="tracking_code" data-model="order">{{ $order->tracking_code }}</span>&nbsp;
                  <span>
                    @include("parts.tracking-code-condition")
                  </span>
                </div>
              </div>

              <div class="mb-3">                
                <div>Težina paketa:</div>
                <div>
                  <span class="editable gray-mark" data-id="{{ $order->id }}" data-field="delivery_weight" data-model="order">{{ $order->delivery_weight }}</span><span> g</span> 
                </div>
              </div>

            </div>

            <div class="col">

              <div class="mb-3">
                <div>Datum dostave:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_delivered" data-model="order">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->date_delivered }}">
                </div>
              </div>

              <div class="mb-3">
                <div>Datum otkazivanja:</div>
                <div class="editable-date" data-id="{{ $order->id }}" data-field="date_cancelled" data-model="order">
                  <input type="date" class="form-control" style="width:80%" value="{{ $order->date_cancelled }}">
                </div>
              </div>


            </div>
            
          </div>

          </div>
        </div>

      </div>

      @include("common.errors")

      {{-- //////////////////// --}}
      {{-- Order item list part --}}
      {{-- //////////////////// --}}

      <div class="col-xl-12">
      <div class="card" style="margin-top: 30px;">
  
        <div class="card-header" style="font-weight: 900; background-color: #19875411">Proizvodi</div>

          <div class="card-body" style=" border: solid 4px #19875411">
            <!-- Button to trigger the pop-up -->
            <button id="popupButton" class="btn btn-success" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Dodaj proizvod</button>

            {{-- @include('parts.tablesearch') --}}

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
                          <th scope="col">Status izrade</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @php ($count = 1)
                        @foreach ($productList as $item)
                                <tr>
                                    {{-- # --}}
                                    <td class="align-middle text-right">{{ $count++ }}</td>

                                    {{-- Proizvod --}}
                                    <td class="align-middle text-right">
                                      <div class="editable-select" data-id="{{ $item->id }}" data-field="product_id" data-model="order-item-list">
                                        <!-- Display the selected value -->
                                        <span>{{ App\Models\Product::find($item->product_id)->product_name }}</span>
                                        
                                        <!-- Hidden select element with options -->
                                        <select class="edit-select form-select" style="display: none !important">
                                          <option value="" selected>Odaberi proizvod...</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>                                  
                                            @endforeach 
                                        </select>
                                      </div>
                                    </td>

                                    {{-- Boja --}}
                                    <td class="align-middle text-right">
                                      <div class="editable-select" data-id="{{ $item->id }}" data-field="color_id" data-model="order-item-list">
                                        <!-- Display the selected value -->
                                        <span>{{ App\Models\Color::find($item->color_id)->color_name }}</span>
                                        
                                        <!-- Hidden select element with options -->
                                        <select class="edit-select form-select" style="display: none !important">
                                          <option value="" selected>Odaberi boju...</option>
                                            @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->color_name }}</option>                                  
                                            @endforeach 
                                        </select>
                                      </div>
                                    </td>

                                    {{-- Količina --}}
                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $item->id }}" data-field="amount" data-model="order-item-list">{{ $item->amount }}</span> {{ App\Models\Product::find($item->product_id)->unit }}
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

                                    {{-- Status izrade --}}
                                    <td class="align-middle text-right">
                                      <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="order-item-list">
                                        <input class="form-check-input edit-checkbox" type="checkbox" name="is_done" id="flexSwitchCheckDefault" {{ $item->is_done ? 'checked' : '' }}>
                                      </div>
                                    </td>

                                    {{-- Delete button --}}
                                    <td>
                                      <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="order-item-list"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                <tr>
                        @endforeach
                      </tbody>
                    </table>
          </div>

      </div>
      </div>

      {{-- //////////////////// --}}
      {{-- Expenses list part   --}}
      {{-- //////////////////// --}}
      <div class="col-xl-12">
        <div class="card" style="margin-top: 30px;">
    
          <div class="card-header" style="font-weight: 900; background-color: #ffc10711;">Troškovi narudžbe</div>
  
          <div class="card-body" style=" border: solid 4px #ffc10711">
              <!-- Button to trigger the pop-up -->
              <button id="popupButton" class="btn btn-warning" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#expensesModal"><i class="bi bi-file-earmark-plus"></i> Dodaj trošak</button>
  
              {{-- @include('parts.tablesearch') --}}
  
                      <table class="table table-hover">
                        <thead class="table-secondary">
                          <tr>                          
                            <th scope="col">#</th>
                            <th scope="col">Vrsta troška</th>
                            <th scope="col">Iznos</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Napomena</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @php ($count = 1)
                          @foreach ($expenseList as $item)
                                  <tr>
                                      {{-- # --}}
                                      <td class="align-middle text-right">{{ $count++ }}</td>
  
                                      {{-- Vrsta troška --}}
                                      <td class="align-middle text-right">
                                        <div class="editable-select" data-id="{{ $item->id }}" data-field="type_id" data-model="expense">
                                          <!-- Display the selected value -->
                                          <span>{{ App\Models\ExpenseType::find($item->type_id)->name }}</span>
                                          
                                          <!-- Hidden select element with options -->
                                          <select class="edit-select form-select" style="display: none !important">
                                            <option value="" selected>Odaberi vrstu troška...</option>
                                              @foreach ($expenseTypes as $type)
                                              <option value="{{ $type->id }}">{{ $type->name }}</option>                                  
                                              @endforeach 
                                          </select>
                                        </div>
                                      </td>
  
                                      {{-- Iznos --}}
                                      <td class="align-middle text-right">
                                        <span class="editable" data-id="{{ $item->id }}" data-field="amount" data-model="expense">{{ $item->amount }}</span> €
                                      </td>
  
                                      {{-- Datum --}}
                                      <td class="align-middle text-right">
                                        <div class="editable-date" data-id="{{ $item->id }}" data-field="date" data-model="expense">
                                          <input type="date" class="form-control" style="width:50%" value="{{ $item->date }}">
                                        </div>
                                      </td>
  
                                      {{-- Napomena --}}
                                      <td class="align-middle text-right">
                                        <span class="editable" data-id="{{ $item->id }}" data-field="note" data-model="expense">{{ $item->note }}</span>
                                      </td>
  
                                      {{-- Delete button --}}
                                      <td>
                                        <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="expense"><i class="bi bi-x-lg"></i></button>
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
</div>


{{-- Modal for expenses --}}
<div class="modal fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expensesModalLabel">Dodaj novi trošak</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- popup content --}}
        <form method="POST" action="/add-expense/{{ $order->id}}" id="expenseForOrderSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="type_id">Vrsta troška:</label><br>
                  <select class="form-select searchable-select-modal2" id="type_id" name="type_id">
                      <option selected>Odaberi vrstu troška...</option>
                      @foreach ($expenseTypes as $type)
                          <option value="{{ $type->id }}">{{ $type->name }}</option>                                    
                      @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="expenseAmount">Iznos troška:</label>
                  <input type="number" class="form-control" placeholder="Unesi iznos troška..." id="expenseAmount" name="expenseAmount" step=".01">
                </div>

                <div class="mb-3">
                  <label for="expenseDate">Datum troška:</label>
                  <input type="date" class="form-control" id="expenseDate" name="expenseDate">
                </div>

                <div class="mb-3">
                  <label for="expenseNote">Komentar / opis:</label>
                  <textarea class="form-control" placeholder="Unesi dodatni opis..." id="expenseNote" name="expenseNote" rows="3">- - -</textarea>
                </div>

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="expenseForOrderSubmission">Spremi</button>
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
        {{-- popup content --}}
        <form method="POST" action="/update-order-products/{{ $order->id}}" id="productForOrderSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="product_id">Vrsta proizvoda:</label><br>
                  <select class="form-select searchable-select-modal" id="product_id" name="product_id">
                      <option selected>Odaberi proizvod...</option>
                      @foreach ($productTypes as $productType)
                        <optgroup label="{{ $productType->type_name }}">
                          @foreach ($productType->product as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }} :: {{ $product->default_price }} €</option>
                          @endforeach
                      @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="amount">Količina:</label>
                  <input type="number" class="form-control" placeholder="Unesi količinu proizvoda..." id="amount" name="amount" step="1">
                </div>

                <div class="mb-3">
                  <label for="price">Cijena:</label>
                  <input type="number" class="form-control" placeholder="Unesi cijenu proizvoda..." id="price" name="price" step=".01">
                </div>

                <div class="mb-3">
                  <label for="color_id">Boja proizvoda:</label><br>
                  <select class="form-select searchable-select-modal" id="color_id" name="color_id">
                      <option selected>Odaberi boju proizvoda, ako je usluga stavi neodređeno...</option>
                      @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>                                  
                      @endforeach
                  </select>
                </div>

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





@include('parts.deleteconfirmation')

@endsection