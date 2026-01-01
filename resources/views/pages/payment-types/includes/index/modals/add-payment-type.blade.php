{{-- Payment type modal --}}
<div class="modal fade" id="paymentTypeModal" tabindex="-1" aria-labelledby="paymentTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentTypeModalLabel">Novi način plaćanja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('nacin-placanja.store') }}" id="paymentTypeSubmission">
          @csrf
          <div class="form-group">

            {{-- Payment type name --}}
            <div class="mb-3">
              <label for="type_name">Način plaćanja:</label>
              <input type="text" class="form-control" placeholder="Unesi naziv..." id="name" name="name">
            </div>

            {{-- Fiscal code key --}}
            <div class="mb-3">
              <label for="fiscal_code_key">Fiskalni kod:</label>
              <select class="form-control" id="fiscal_code_key" name="fiscal_code_key" required>
                <option value="" disabled selected>Odaberi kod...</option>
                @foreach($fiscalCodes as $key => $label)
                  <option value="{{ $key }}">{{ $label }} ({{ $key }})</option>
                @endforeach
              </select>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="paymentTypeSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>