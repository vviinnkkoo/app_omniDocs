{{-- Deletion confirmation modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="confirmationModalLabel">Upozorenje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <div class="modal-body">
        Da li ste sigurni da želite obrisati ovaj podatak? Ova akcija je nepovratna.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Da, obriši</button>
      </div>
    </div>
  </div>
</div>