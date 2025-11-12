{{-- Modal --}}
<div class="modal fade" id="addSourceModal" tabindex="-1" aria-labelledby="addSourceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSourceModalLabel">Novi kanal prodaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('kanali-prodaje.store') }}" id="sourceSubmission">
            @csrf
            <div class="form-group">

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name">Kanal prodaje:</label>
                    <input type="text" class="form-control" placeholder="Unesi kanal prodaje..." id="name" name="name">
                </div>

            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="sourceSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>