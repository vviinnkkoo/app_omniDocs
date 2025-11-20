{{-- Order modal --}}
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addOrderModalLabel">Nova narudžba</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('narudzbe.store') }}" id="orderSubmission">
          @csrf
          <div class="form-group">

            {{-- Customer --}}
            <x-inputs.advanced-select 
                name="customer_id"
                :items="$customers"
                label="Kupac"
                placeholder="Pretraži kupce..."
                :required="true"
                :renderItem="fn($c) => $c->name . ' - ' . $c->city"
            />

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
            <x-inputs.advanced-radio name="source_id" label="Kanal prodaje" :items="$sources" :required="true"/>

            {{-- Payment type --}}
            <x-inputs.advanced-radio name="payment_type_id" label="Način plaćanja" :items="$paymentTypes" :required="true"/>

            {{-- Delivery service --}}
            <x-inputs.advanced-select 
                name="delivery_service_id"
                :items="$deliveryCompanies"
                grouped="true"
                childrenKey="deliveryServices"
                label="Dostavna služba"
                placeholder="Pretraži dostavnu usluge..."
                :required="true"
                :renderItem="fn($i) => $i->name . ' >> ' . $i->default_cost . ' €'"
            />

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