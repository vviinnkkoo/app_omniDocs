{{-- Invoice modal --}}
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addInvoiceModalLabel">Novi račun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Popup content --}}
            <form method="POST" action="{{ route('racuni.store') }}" id="invoiceSubmission">
                @csrf
                <div class="form-group">

                    {{-- Invoice number --}}
                    <div class="mb-3">
                        <label for="number">Redni broj računa:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="Unesi redni broj računa..." id="number" name="number" value="{{ $latest }}" required>
                            <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2" id="refresh-number-btn">
                                🔄
                                <div id="numberLoader" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                    <span class="visually-hidden">Učitavanje...</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    {{-- Order for invoice --}}
                    <div class="mb-3">
                        <label for="order_id">Povezana narudžba:</label><br>
                        <select class="form-select searchable-select-modal" id="order_id" name="order_id" required>
                            <option disabled selected>Odaberi narudžbu...</option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Invoice year --}}
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
            <button type="submit" class="btn btn-primary" form="invoiceSubmission">Spremi</button>
        </div>
    </div>
  </div>
</div>