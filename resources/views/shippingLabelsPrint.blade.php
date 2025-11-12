@extends('layouts.app')

@section('title', 'Omnius Art | Dostavne etikete')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">                  
          <x-modal-button target="#shippingLabelsModal" text="Nova etiketa"/>
          <a class="btn btn-success" style="margin-bottom:20px; margin-left:10px;" href="/etikete" target="_blank"><i class="bi bi-printer"></i> Ispiši etikete</a>

          <a class="btn btn-danger" style="margin-bottom:20px; margin-left:10px;" href="/obrisi-etikete"><i class="bi bi-trash3"></i> Obriši etikete</a>

          @include('includes.tablesearch')

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Narudžba</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @php ($count = 1)
              @foreach ($shippingLabels as $item)
                <tr>

                  <td class="align-middle text-right">
                    {{ $count++ }}
                  </td>

                  <td class="align-middle text-right">
                    <div class="editable-select" data-id="{{ $item->id }}" data-field="order_id" data-model="receipt">
                      <!-- Display the selected value -->
                      <span>{{ App\Models\Customer::find(App\Models\Order::find($item->order_id)->customer_id)->name }} - Narudžba [{{ $item->order_id }}]</span>
                      
                      <!-- Hidden select element with options -->
                      <select class="edit-select form-select" style="display: none !important">
                        <option value="" selected>Odaberi narudžbu...</option>
                          @foreach ($orders as $order)
                          <option value="{{ $order->id }}">{{ App\Models\Customer::find($order->customer_id)->name }} - Narudžba [{{ $order->id }}]</option>
                          @endforeach 
                      </select>
                    </div>
                  </td>

                  <td>
                    <a href="/p10m/{{ $item->order_id }}" class="btn btn-warning" target="_blank"><i class="bi bi-filetype-pdf"></i> P10-m</a>
                  </td>

                  <td>
                    <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="shipping-label"><i class="bi bi-x-lg"></i></button>
                  </td>
                  
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



                    


<!-- Modal -->
<div class="modal fade" id="shippingLabelsModal" tabindex="-1" aria-labelledby="shippingLabelsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shippingLabelsModalLabel">Nova etiketa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/dostavne-etikete" id="labelSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="order_id">Narudžba:</label><br>
                  <select class="form-select searchable-select-modal" id="order_id" name="order_id">
                      <option selected>Odaberi narudžbu...</option>
                      @foreach ($orders as $order)
                        @if ( App\Models\PrintLabel::where('order_id', $order->id)->doesntExist() )
                          <option value="{{ $order->id }}">{{ $order->id }} - {{App\Models\Customer::find($order->customer_id)->name}}</option>
                        @endif
                      @endforeach
                  </select>
                </div>

                <input type="hidden" value="shipping" name="label_type" id="label_type">

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="labelSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.shared.modals.delete-confirmation')

@endsection