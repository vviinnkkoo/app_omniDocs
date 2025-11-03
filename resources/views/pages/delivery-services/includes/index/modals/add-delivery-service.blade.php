{{-- Delivery service modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nova dostavna usluga</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Modal content --}}
        <form method="POST" action="{{ route('dostavne-usluge.store') }}" id="deliveryServiceSubmission">
          @csrf      
          <div class="form-group">

            {{-- Name --}}
            <div class="mb-3">
              <label for="name">Naziv dostavne usluge:</label>
              <input type="text" class="form-control" placeholder="Unesi naziv..." id="name" name="name">
            </div>
    
            {{-- Delivery Company --}}
            <div class="mb-3">
              <label for="company_id">Dostavna služba:</label><br>
              <select class="form-select searchable-select-modal" id="delivery_company_id" name="delivery_company_id">
                <option selected>Odaberi dostavnu službu</option>
                @foreach ($deliveryCompanies as $deliveryCompany)
                    <option value="{{ $deliveryCompany->id }}">{{ $deliveryCompany->name }}</option>
                @endforeach
              </select>
            </div>
    
            {{-- Default Cost --}}
            <div class="mb-3">
              <label for="default_cost">Standardna cijena:</label>
              <input type="number" class="form-control" placeholder="Unesi standardnu cijenu" id="default_cost" name="default_cost" step=".01">
            </div>

          </div>
        </form>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="deliveryServiceSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>