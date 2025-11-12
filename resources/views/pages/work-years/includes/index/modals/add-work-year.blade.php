{{-- Work year modal --}}
<div class="modal fade" id="addWorkYearModal" tabindex="-1" aria-labelledby="addWorkYearModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addWorkYearModalLabel">Novi kanal prodaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('radne-godine.store') }}" id="workYearSubmission">
          @csrf
          <div class="form-group">

            {{-- Year --}}
            <div class="mb-3">
              <label for="name">Radna godina:</label>
              <input type="text" class="form-control" placeholder="Unesi godinu, npr 2023, 2024, itd..." id="year" name="year">
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="workYearSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>