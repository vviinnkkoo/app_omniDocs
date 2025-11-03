{{-- Product types modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nova vrsta proizvoda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Popup content --}}
                <form method="POST" action="{{ route('vrste-proizvoda.store') }}" id="productTypeSubmmission">
                    @csrf
                    <div class="form-group">

                        {{-- Product type --}}
                        <div class="mb-3">
                            <label for="name">Vrsta proizvoda:</label>
                            <input type="text" class="form-control" placeholder="Unesi novu vrstu proizvoda..." id="name" name="name">
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            <button type="submit" class="btn btn-primary" form="productTypeSubmmission">Spremi</button>
            </div>
        </div>
    </div>
</div>