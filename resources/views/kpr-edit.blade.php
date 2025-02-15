@extends('layouts.app')

@section('title', 'Omnius Art | Uredi uplatu')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header" style="font-weight: 900;">
          <a class="gray-mark-extra" href="/knjiga-prometa/{{ $year}}"><i class="bi bi-arrow-left"></i></a> Uplata: {{$item->number}} - {{ $year }}
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <div><h5 class="fw-bold">Platitelj:</h5></div>              
                <div>
                  <span class="editable" data-id="{{ $item->id }}" data-field="payer" data-model="kpr">{{ $item->payer }}</span>
                </div>
              </div>

              <div class="mb-3">
                <div><h5 class="fw-bold">Datum:</h5></div>
                <div class="editable-date" data-id="{{ $item->id }}" data-field="date" data-model="kpr">
                  <input type="date" class="form-control" style="width:40%" value="{{ $item->date }}">
                </div>
              </div>

              <div class="mb-3">
                <div><h5 class="fw-bold">Iznos:</h5></div>
                <div>
                  <span class="editable" data-id="{{ $item->id }}" data-field="amount" data-model="kpr">{{ $item->amount }}</span> €
                </div>
              </div>
            </div>

            <div class="col">
              <div class="mb-3">
                <div><h5 class="fw-bold">Broj naloga:</h5></div>              
                <div>
                  <span class="editable" data-id="{{ $item->id }}" data-field="origin" data-model="kpr">{{ $item->origin }}</span>
                </div>
              </div>

              <div class="mb-3">
                <div><h5 class="fw-bold">Opis:</h5></div>
                <div>
                  <span class="editable" data-id="{{ $item->id }}" data-field="info" data-model="kpr">{{ $item->info }}</span>
                </div>
              </div>              
            </div>

            <div class="col"></div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-top: 15px;">
  
          <div class="card-body">
            <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Poveži račun</button>

            @include('parts.tablesearch')

              <table class="table table-hover">
                <thead class="table-dark">
                  <tr>                          
                    <th scope="col">Broj računa</th>
                    <th scope="col">Kupac</th>
                    <th scope="col">Narudžba</th>
                    <th scope="col">Kod</th>
                    <th scope="col">Vrijeme izdavanja</th>
                    <th scope="col">Iznos na računu</th>
                    <th scope="col">Račun</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($invoiceList as $item)

                    <tr>

                      <td class="align-middle text-right">{{ $item->receiptNumber }}</td>

                      <td class="align-middle text-right">
                        <span>{{ $item->customerName }}</span>
                      </td>

                      <td class="align-middle text-right">
                        <a href="/uredi-narudzbu/{{ $item->orderId }}" class="btn btn-primary btn-sm">Narudžba <span class="badge badge-secondary" style="background-color:darkred">{{ $item->orderId }}</span></a>
                      </td>

                      <td class="align-middle text-right">
                        {{ $item->trackingCode }}
                      </td>

                      <td class="align-middle text-right">
                        <div class="date-display">{{ $item->receiptDate }}</div>
                      </td>

                      <td class="align-middle text-right">
                        {{ $item->receiptsTotal }} €
                      </td>

                      <td>
                        <a href="/racun/{{ $item->receiptID }}" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i> Račun</a>
                        </button>
                      </td>

                      <td>
                        <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="kpr-item-list"><i class="bi bi-x-lg"></i></button>
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
        <h5 class="modal-title" id="exampleModalLabel">Poveži novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/invoice-to-kpr/{{ $item->id }}" id="productForOrderSubmission">
          @csrf
              <div class="form-group">

                <div class="mb-3">
                  <label for="receipt_id">Odaberi račun:</label><br>
                  <select class="form-select searchable-select-modal" id="receipt_id" name="receipt_id">
                      <option selected>Odaberi račun za povezivanje...</option>
                      @foreach ($receipts as $receipt)

                        @if (!App\Models\KprItemList::where('receipt_id', $receipt->id)->exists() )
                            <option value="{{ $receipt->id }}">{{ $receipt->number }} - {{ App\Models\Customer::find(App\Models\Order::find($receipt->order_id)->customer_id)->name }} - {{ App\Http\Controllers\ReceiptController::getReceiptTotal($receipt->order_id) }} € - {{ App\Models\Order::find($receipt->order_id)->tracking_code }}</option>
                        @endif
                      @endforeach
                  </select>
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