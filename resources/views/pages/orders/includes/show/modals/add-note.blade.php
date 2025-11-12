{{-- Modal for notes --}}
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteModalLabel">Dodaj novu napomenu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('napomena.store') }}" id="orderNoteSubmission">
          @csrf
          <div class="form-group">

            {{-- Note text area --}}
            <div class="mb-3">
              <label for="note">Napomena:</label>
              <textarea class="form-control" placeholder="Unesi dodatni opis..." id="note" name="note" rows="3">- - -</textarea>                  
            </div>
            
            {{-- Hidden order ID --}}
            <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="orderNoteSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>