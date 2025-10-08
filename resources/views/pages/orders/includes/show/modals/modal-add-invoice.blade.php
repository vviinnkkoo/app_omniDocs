{{-- Invoice modal --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel">Novi račun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
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