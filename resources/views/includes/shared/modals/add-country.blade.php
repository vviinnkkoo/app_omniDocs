{{-- New country modal --}}
<div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="ountryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ountryModalLabel">Nova država poslovanja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('drzave-poslovanja.store') }}" id="countrySubmission">
          {{ csrf_field() }}
            <div class="form-group">

              <div class="mb-3">
                <label for="name">Naziv države:</label>
                <input type="text" class="form-control" placeholder="Unesi novu državu poslovanja..." id="name" name="name">
              </div>

            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="countrySubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>