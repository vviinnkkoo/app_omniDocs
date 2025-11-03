<!-- Payment type modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="paymentTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentTypeModal">Novi način plaćanja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('nacin-placanja.store') }}" id="paymentSubmission">
          @csrf
          <div class="form-group">

            {{-- Payment type name --}}
            <div class="mb-3">
              <label for="type_name">Način plaćanja:</label>
              <input type="text" class="form-control" placeholder="Unesi naziv..." id="name" name="name">
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