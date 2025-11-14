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
        <form method="POST" action="/narudzbe" id="orderSubmission">
          {{ csrf_field() }}
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
            <div class="mb-3">
              <span style="display: block;">Kanal prodaje:</span>
                @foreach ($sources as $source)
                  <input type="radio" class="btn-check" name="source_id" autocomplete="off" value="{{ $source->id }} " id="source{{ $source->id }}" required>
                  <label class="btn btn-light btn-sm me-1 mb-2" for="source{{ $source->id }}">{{ $source->name }}</label>
                @endforeach
            </div>      

            {{-- Payment type --}}
            <div class="mb-3">
              <span style="display: block;">Način plaćanja:</span>
                @foreach ($paymentTypes as $paymentType)
                  <input type="radio" class="btn-check" name="payment_type_id" autocomplete="off" value="{{ $paymentType->id }} " id="payment{{ $paymentType->id }}" required>
                  <label class="btn btn-light btn-sm me-1 mb-1" for="payment{{ $paymentType->id }}">{{ $paymentType->name }}</label>
                @endforeach
            </div>

            {{-- Delivery service --}}
            <div class="mb-3 omniselect-dropdown">
                <label for="delivery_service_id">Dostavna služba:</label>
                <input type="text" class="form-control omniselect"
                      data-name="delivery_service_id"
                      placeholder="Pretraži dostavnu službu..."
                      autocomplete="off"
                      required>
                <input type="hidden" name="delivery_service_id" class="omniselect-hidden">
                <ul class="dropdown-menu w-100">
                  @foreach ($deliveryCompanies as $company)
                    {{-- Group label --}}
                    <li class="dropdown-group">{{ $company->name }}</li>
                    
                    @foreach ($company->deliveryServices as $service)
                      @if ($service->in_use == 1)
                        <li>
                          <a href="#" data-value="{{ $service->id }}">
                            {{ $service->name }} >> {{ $service->default_cost }} €
                          </a>
                        </li>
                      @endif
                    @endforeach
                  @endforeach
                </ul>
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