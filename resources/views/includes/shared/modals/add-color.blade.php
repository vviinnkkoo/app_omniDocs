<!-- New color modal -->
<div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="colorModalLabel">Nova boja/opis proizvoda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('opis.store') }}" id="colorSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="color">Boja / opis:</label>
                  <input type="text" class="form-control" placeholder="Unesi novu boju ili opis proizvoda..." id="color" name="name">
                </div>

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="colorSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>