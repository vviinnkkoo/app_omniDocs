{{-- Invoice modal --}}
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInvoiceModalLabel">Novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('racuni.store') }}" id="invoiceSubmission">
          @csrf
          <div class="row g-3">

            {{-- Business place and device --}}
            <div class="col-6">
              <label for="business_space_id">Poslovni prostor:</label>
              <select class="form-select searchable-select-modal" id="business_space_id" name="business_space_id" required>
                @foreach ($businessSpaces as $space)
                  <option value="{{ $space->id }}">{{ $space->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label for="business_device_id">Uređaj:</label>
              <select class="form-select searchable-select-modal" id="business_device_id" name="business_device_id" required>
                @foreach ($businessDevices as $device)
                  <option value="{{ $device->id }}">{{ $device->name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Invoice number and year --}}
            <div class="col-6">
              <label for="number">Redni broj računa:</label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder="Unesi redni broj računa..." id="number" name="number" value="{{ $latestInvoiceNumber }}" required>
                <button type="button" class="btn btn-light d-flex align-items-center gap-2 border" id="refresh-number-btn">
                    <i class="bi bi-arrow-clockwise" id="refresh-icon"></i>
                    <div id="numberLoader" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                        <span class="visually-hidden">Učitavanje...</span>
                    </div>
                </button>
              </div>
            </div>
            <div class="col-6">
              <label for="year">Godina računa:</label>
              <select class="form-select searchable-select-modal" id="year" name="year" required>
                @foreach ($workYears as $workYear)
                  <option {{ $loop->last ? 'selected' : '' }}>{{ $workYear->year }}</option>
                @endforeach
              </select>
            </div>

            {{-- Checkbox for editing customer data --}}
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="editCustomerData">
                <label class="form-check-label" for="editCustomerData">Izmjeni podatke kupca</label>
              </div>
            </div>

            {{-- Customer fields (hidden by default) --}}
            <div class="col-12 d-none border-start border-3 border-secondary p-3 bg-light shadow-sm" id="customerFields">
              <div class="row g-2">

                {{-- Name --}}
                <div class="col-md-6">
                  <label for="customer_name">Ime i prezime:</label>
                  <input type="text" class="form-control customer-visible" id="customer_name" name="customer_name" data-hidden-id="hidden_customer_name">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_name" value="{{ $customer->name ?? '' }}">
                </div>

                {{-- OIB --}}
                <div class="col-md-6">
                  <label for="customer_oib">OIB:</label>
                  <input type="text" class="form-control customer-visible" id="customer_oib" name="customer_oib" data-hidden-id="hidden_customer_oib">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_oib" value="{{ $customer->oib ?? '' }}">
                </div>

                {{-- Address --}}
                <div class="col-md-6">
                  <label for="customer_address">Adresa:</label>
                  <input type="text" class="form-control customer-visible" id="customer_address" name="customer_address" data-hidden-id="hidden_customer_address">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_address" value="{{ $customer->address ?? '' }} {{ $customer->house_number ?? '' }}">
                </div>

                {{-- Postal number --}}
                <div class="col-md-6">
                  <label for="customer_postal">Poštanski broj:</label>
                  <input type="text" class="form-control customer-visible" id="customer_postal" name="customer_postal" data-hidden-id="hidden_customer_postal">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_postal" value="{{ $customer->postal ?? '' }}">
                </div>

                {{-- City --}}
                <div class="col-md-6">
                  <label for="customer_city">Grad:</label>
                  <input type="text" class="form-control customer-visible" id="customer_city" name="customer_city" data-hidden-id="hidden_customer_city">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_city" value="{{ $customer->city ?? '' }}">
                </div>

                {{-- Phone --}}
                <div class="col-md-6">
                  <label for="customer_phone">Telefon:</label>
                  <input type="text" class="form-control customer-visible" id="customer_phone" name="customer_phone" data-hidden-id="hidden_customer_phone">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_phone" value="{{ $customer->phone ?? '' }}">
                </div>

                {{-- Email --}}
                <div class="col-md-12">
                  <label for="customer_email">Email:</label>
                  <input type="email" class="form-control customer-visible" id="customer_email" name="customer_email" data-hidden-id="hidden_customer_email">
                  <input type="hidden" class="customer-hidden" id="hidden_customer_email" value="{{ $customer->email ?? '' }}">
                </div>

              </div>
            </div>

            {{-- Issued by / Dates --}}
            <div class="col-12">
              <label for="issued_by">Račun izdaje:</label>
              <input type="text" class="form-control" id="issued_by" name="issued_by" value="{{ auth()->user()->name ?? '' }}" required>
            </div>

            <div class="col-4">
              <label for="issued_at">Datum izdavanja:</label>
              <input type="datetime-local" class="form-control" id="issued_at" name="issued_at" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="col-4">
              <label for="due_at">Datum dospijeća:</label>
              <input type="date" class="form-control" id="due_at" name="due_at" value="{{ now()->addDays(7)->format('Y-m-d') }}">
            </div>
            <div class="col-4">
              <label for="shipping_date">Datum isporuke:</label>
              <input type="date" class="form-control" id="shipping_date" name="shipping_date" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            {{-- Hidden order ID and Type --}}
            <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}" required>
            <input type="hidden" id="type" name="type" value="invoice" required>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="invoiceSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

{{-- JS za toggle customer polja --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const editCheckbox = document.getElementById('editCustomerData');
      const customerFieldsWrapper = document.getElementById('customerFields');
      const hiddenFields = document.querySelectorAll('.customer-hidden');
      const visibleFields = document.querySelectorAll('.customer-visible');

      // Postavi default vrijednosti iz hidden inputa u visible input
      visibleFields.forEach((field, i) => field.value = hiddenFields[i].value);

      editCheckbox.addEventListener('change', () => {
          if(editCheckbox.checked) {
              customerFieldsWrapper.classList.remove('d-none');
              // Kad se aktivira, visible inputi dobiju vrijednosti iz hidden
              visibleFields.forEach((field, i) => field.value = hiddenFields[i].value);
          } else {
              customerFieldsWrapper.classList.add('d-none');
              // Kad se deaktivira, visible inputi resetiraju na hidden vrijednosti
              visibleFields.forEach((field, i) => field.value = hiddenFields[i].value);
          }
      });
  });
</script>
