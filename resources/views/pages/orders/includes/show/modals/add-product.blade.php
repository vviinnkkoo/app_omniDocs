{{-- Modal for products --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dodaj novi proizvod</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('narudzbe-proizvodi.store') }}" id="productForOrderSubmission">
          @csrf
          <div class="form-group">

            {{-- Hidden order ID --}}
            <input type="hidden" name="order_id" value="{{ Crypt::encryptString($order->id) }}" required>

            {{-- Product --}}
            <div class="mb-3 omniselect-dropdown">
                <label for="product_id">Proizvod:</label>
                <input type="text" class="form-control omniselect"
                      data-name="product_id"
                      placeholder="Pretraži proizvode..."
                      autocomplete="off"
                      required>
                <input type="hidden" name="product_id" class="omniselect-hidden">
                <ul class="dropdown-menu w-100">
                  @foreach ($productTypes as $type)
                    {{-- Group label --}}
                    <li class="dropdown-group">{{ $type->name }}</li>
                    
                    @foreach ($type->product as $product)
                      <li>
                        <a href="#" data-value="{{ $product->id }}">
                          {{ $product->name }} >> {{ $product->default_price }} €
                        </a>
                      </li>
                    @endforeach
                  @endforeach
                </ul>
            </div>

            {{-- Amount --}}
            <div class="mb-3">
              <label for="amount">Količina:</label>
              <input type="number" class="form-control" placeholder="Unesi količinu proizvoda..." id="amount" name="amount" step="1" required>
            </div>

            {{-- Price --}}
            <div class="mb-3">
              <label for="price">Cijena:</label>
              <input type="number" class="form-control" placeholder="Unesi cijenu proizvoda..." id="price" name="price" step=".01" required>
            </div>

            {{-- Discount --}}
            <div class="mb-3">
              <label for="price">Popust (%):</label>
              <input type="number" class="form-control" value="0" min="0" max="100" id="discount" name="discount" step="1" required>
            </div>
            
            {{-- Product color --}}
            <div class="mb-3">
              <span style="display: block;">Boja proizvoda:</span>
                @foreach ($colors as $color)
                  <input type="radio" class="btn-check" name="color_id" autocomplete="off" value="{{ $color->id }} " id="color_{{ $color->id }}" required>
                  <label class="btn btn-light btn-sm me-1 mb-1" for="color_{{ $color->id }}">{{ $color->name }}</label>
                @endforeach
            </div>

            {{-- Product note --}}
            <div class="mb-3">
              <label for="note">Komentar / opis:</label>
              <textarea class="form-control" placeholder="Unesi dodatni opis..." id="note" name="note" rows="3">- - -</textarea>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="productForOrderSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>