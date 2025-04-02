@extends('layouts.app')

@section('title', 'Omnius Art | Računi')

@section('content')
<div class="containerx" style="margin-left:5%; margin-right:5%">

    <div class="row justify-content-center">
        <div>          
            <div class="card">

                {{-- <div class="card-header">{{ __('Dostavne službe') }}</div> --}}

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi račun</button>

                  @include('includes.tablesearch')

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Kupac</th>
                          <th scope="col">Narudžba</th>
                          <th scope="col">Dostava</th>
                          <th scope="col">Datum dostave</th>
                          <th scope="col">Način plaćanja</th>
                          <th scope="col">Vrijeme izdavanja</th>
                          <th scope="col">Iznos na računu</th>
                          <th scope="col">Storno</th>
                          <th scope="col">Povezana uplata</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($receipts as $receipt)
                                  @if ($receipt->is_cancelled == TRUE )
                                    <tr class="cancelled-order">
                                  @else
                                    <tr>
                                  @endif

                                    <td class="align-middle text-right">{{ $receipt->number }}</td>

                                    <td class="align-middle text-right">
                                      <span>{{ App\Models\Customer::find(App\Models\Order::find($receipt->order_id)->customer_id)->name }}</span>                                    
                                    </td>

                                    <td class="align-middle text-right">
                                      <a href="/uredi-narudzbu/{{ $receipt->order_id }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $receipt->order_id }}</span></a>                                    
                                    </td>

                                    <td class="align-middle text-right">
                                      @if (isset(App\Models\Order::find($receipt->order_id)->date_delivered))
                                        <span class="btn btn-sm btn-success">Dostavljeno</span>
                                      @else
                                        <span class="btn btn-sm btn-warning">Nije dostavljeno</span>
                                      @endif
                                      {{ App\Models\DeliveryService::find(App\Models\Order::find($receipt->order_id)->delivery_service_id)->name }}
                                    </td>

                                    <td class="align-middle text-right">
                                      <div class="date-display">{{ \Carbon\Carbon::parse(App\Models\Order::find($receipt->order_id)->date_delivered)->format('d.m.Y') }}</div>
                                    </td>

                                    <td class="align-middle text-right">
                                      {{ App\Models\PaymentType::find(App\Models\Order::find($receipt->order_id)->payment_type_id)->type_name }}
                                    </td>

                                    <td class="align-middle text-right">
                                      <div class="editable-date-invoice" data-id="{{ $receipt->id }}" data-field="created_at" data-model="receipt" data-raw-date="{{ $receipt->created_at }}">
                                        <div class="date-display">{{ \Carbon\Carbon::parse($receipt->created_at)->format('d.m.Y - H:i:s') }}</div>
                                      </div>
                                    </td>

                                    <td class="align-middle text-right">
                                      {{ $receipt->totalAmount }} €
                                    </td>

                                    <td class="align-middle text-right">
                                      <div class="form-check form-switch order-item" data-id="{{ $receipt->id }}" data-model="racuni">
                                        <input class="form-check-input edit-checkbox" type="checkbox" name="is_cancelled" id="flexSwitchCheckDefault" {{ $receipt->is_cancelled ? 'checked' : '' }}>
                                      </div>
                                    </td>

                                    <td>
                                      @if ( App\Models\KprItemList::where( 'receipt_id', $receipt->id )->exists() )
                                        <a href="/uredi-uplatu/{{ App\Models\KprItemList::where('receipt_id', $receipt->id)->first()->kpr_id }}" class="btn btn-warning" target="_blank"><i class="bi bi-filetype-pdf"></i> Uplata <span class="badge badge-secondary" style="background-color:darkred">ID: {{ App\Models\Kpr::find(App\Models\KprItemList::where('receipt_id', $receipt->id)->first()->kpr_id)->id }}</span></a>
                                      @endif
                                    </td>
                                    

                                    <td>
                                      <a href="/dokument/racun/{{$receipt->id}}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> Račun</a>
                                    </td>

                                    <td>
                                      <button class="btn btn-danger delete-btn-x" data-id="{{ $receipt->id }}" data-model="receipt"><i class="bi bi-x-lg"></i></button>
                                    </td>

                                <tr>
                        @endforeach
                      </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $receipts->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                    
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
        <h5 class="modal-title" id="exampleModalLabel">Novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/racuni" id="receiptSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="number">Redni broj računa:</label>
                  <input type="number" class="form-control" placeholder="Unesi redni broj računa..." id="number" name="number" value="{{ $latest }}">
                </div>

                <div class="mb-3">
                  <label for="order_id">Povezana narudžba:</label><br>
                  <select class="form-select searchable-select-modal" id="order_id" name="order_id">
                      <option selected>Odaberi narudžbu...</option>
                      @foreach ($orders as $order)

                        @if (!App\Models\Receipt::where('order_id', $order->id)->where('is_cancelled', 0)->exists() )
                            <option value="{{ $order->id }}">{{ $order->id }} - {{App\Models\Customer::find($order->customer_id)->name}}</option>
                        @endif
                      @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="year">Godina računa:</label>
                  <select class="form-select searchable-select-modal" id="year" name="year">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>