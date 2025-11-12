{{-- Modal --}}
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPaymentModalLabel">Nova uplata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{route('knjiga-prometa.store')}}" id="paymentSubmission">
          @csrf
          <div class="form-group">

            {{-- Payer --}}
            <div class="mb-3">
              <label for="payer">Platitelj:</label>
              <input type="text" class="form-control" placeholder="Unesi platitelja..." id="payer" name="payer">
            </div>

            {{-- Date of payment --}}
            <div class="mb-3">
              <label for="date">Datum uplate:</label>
              <input type="date" class="form-control" id="date" name="date">
            </div>

            {{-- Payment method --}}
            <div class="mb-3">
              <span style="display: block;">Način plaćanja:</span>
              @foreach ($paymentMethods as $method)
                <input type="radio" class="btn-check" name="payment_type_id" autocomplete="off" value="{{ $method->id }} " id="payment{{ $method->id }}" required>
                <label class="btn btn-light btn-sm me-1 mb-1" for="payment{{ $method->id }}">{{ $method->name }}</label>
              @endforeach
            </div>

            {{-- Amount --}}
            <div class="mb-3">
              <label for="amount">Iznos:</label>
              <input type="number" class="form-control" placeholder="Unesi iznos uplate..." id="amount" name="amount" step=".01">
            </div>

            {{-- Payment reference or ID --}}
            <div class="mb-3">
              <label for="source">Referenca naloga:</label>
              <input type="text" class="form-control" placeholder="Unesi referencu naloga..." id="origin" name="origin">
            </div>

            {{-- Description --}}
            <div class="mb-3">
              <label for="source">Opis:</label>
              <input type="text" class="form-control" placeholder="Unesi opis uplate..." id="info" name="info">
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="paymentSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>