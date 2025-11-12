{{-- Connect invoice modal --}}
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInvoiceModalLabel">Poveži novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="/invoice-to-kpr/{{ $kprInstance->id }}" id="invoiceSubmission">
          @csrf
              <div class="form-group">

                {{-- Invoice select --}}
                <div class="mb-3">
                  <label for="receipt_id">Odaberi račun:</label><br>
                  <select class="form-select searchable-select-modal" id="receipt_id" name="receipt_id">
                      <option selected>Odaberi račun za povezivanje...</option>
                      @foreach($receiptOptions as $item)
                        <option value="{{ $item['id'] }}">
                            {{ $item['number'] }} - {{ $item['customerName'] }} - {{ $item['total'] }} € - {{ $item['trackingCode'] }}
                        </option>
                      @endforeach
                  </select>
                </div>

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