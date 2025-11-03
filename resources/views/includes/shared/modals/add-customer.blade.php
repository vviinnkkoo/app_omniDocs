{{-- New customer modal --}}
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Novi kupac</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Popup content --}}
        <form method="POST" action="{{ route('kupci.store') }}" id="customerSubmission">
          @csrf
          <div class="form-group">

            {{-- Name --}}
            <div class="mb-3">
              <label for="name">Ime:</label>
              <input type="text" class="form-control" placeholder="Unesi ime..." id="name" name="name" required>
            </div>

            {{-- OIB --}}
            <div class="mb-3">
              <label for="name">OIB:</label>
              <input type="text" class="form-control" placeholder="Unesi OIB..." id="oib" name="oib" value="- - -" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email">Email:</label>
              <input type="text" class="form-control" placeholder="Unesi email..." id="email" name="email" value="- - -" required>
            </div>

            {{-- Phone --}}
            <div class="mb-3">
              <label for="phone">Mobitel:</label>
              <input type="text" class="form-control" placeholder="Unesi mobitel..." id="phone" name="phone" value="- - -" required>
            </div>

            {{-- Address --}}
            <div class="mb-3">
              <label for="address">Adresa:</label>
              <input type="text" class="form-control" placeholder="Unesi adresu..." id="address" name="address" required>
            </div>

            {{-- House number --}}
            <div class="mb-3">
              <label for="address">Kućni broj:</label>
              <input type="text" class="form-control" placeholder="Unesi kućni broj (17, 17 A, 21 / 4)..." id="house_number" name="house_number" required>
            </div>

            {{-- City --}}
            <div class="mb-3">
              <label for="city">Grad:</label>
              <input type="text" class="form-control" placeholder="Unesi grad..." id="city" name="city" required>
            </div>

            {{-- Postal code --}}
            <div class="mb-3">
              <label for="postal">Poštanski broj:</label>
              <input type="text" class="form-control" placeholder="Unesi poštanski broj..." id="postal" name="postal" required>
            </div>

            {{-- Country --}}
            <div class="mb-3 omniselect-dropdown">
              <label for="country_id">Država:</label>
              <input type="text" class="form-control omniselect"
                data-name="country_id"
                placeholder="Pretraži državu..."
                autocomplete="off"
                required>
              <input type="hidden" name="country_id" class="omniselect-hidden">
              <ul class="dropdown-menu w-100">
                @foreach ($countries as $country)
                  <li>
                    <a href="#" data-value="{{ $country->id }}">
                      {{ $country->name }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="customerSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>