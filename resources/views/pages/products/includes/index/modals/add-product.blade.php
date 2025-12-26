{{-- Product modal --}}
<div class="modal fade" id="addProductModal" aria-labelledby="addProductModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Novi proizvod</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        {{-- Popup content --}}
        <form method="POST" action="{{ route('proizvodi.store') }}" id="productSubmission">
          @csrf
          <div class="form-group">

            {{-- Product name --}}
            <label for="name">Naziv proizvoda:</label>
            <input type="text" class="form-control" placeholder="Unesi naziv novog proizvoda..." id="name" name="name">

            {{-- Group --}}
            <label for="item_group_key">Grupa:</label>
            <select class="form-select searchable-select-modal" id="item_group_key" name="item_group_key">
                <option selected disabled>Odaberi grupu proizvoda...</option>
                @foreach($groups as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            {{-- Product type --}}
            <label for="product_type_id">Vrsta proizvoda:</label>
            <select class="form-select searchable-select-modal" id="product_type_id" name="product_type_id">
              <option selected>Odaberi vrstu proizvoda...</option>
              @foreach ($productTypes as $productType)
                <option value="{{ $productType->id }}">{{ $productType->name }}</option>                                  
              @endforeach
            </select>

            {{-- Default price --}}
            <label for="default_price">Standardna cijena:</label>
            <input type="number" class="form-control" placeholder="Unesi standardnu cijenu" id="default_price" name="default_price" step=".01">

          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="productSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>