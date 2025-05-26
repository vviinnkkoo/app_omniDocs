{{ csrf_field() }}
<div class="form-group">

    <div class="mb-3">
        <label for="name">Ime:</label>
        <input type="text" class="form-control" placeholder="Unesi ime..." id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="name">OIB:</label>
        <input type="text" class="form-control" placeholder="Unesi OIB..." id="oib" name="oib" value="- - -" required>
    </div>

    <div class="mb-3">
        <label for="email">Email:</label>
        <input type="text" class="form-control" placeholder="Unesi email..." id="email" name="email" value="- - -" required>
    </div>

    <div class="mb-3">
        <label for="phone">Mobitel:</label>
        <input type="text" class="form-control" placeholder="Unesi mobitel..." id="phone" name="phone" value="- - -" required>
    </div>

    <div class="mb-3">
        <label for="address">Adresa:</label>
        <input type="text" class="form-control" placeholder="Unesi adresu..." id="address" name="address" required>
    </div>

    <div class="mb-3">
        <label for="address">Kućni broj:</label>
        <input type="text" class="form-control" placeholder="Unesi kućni broj (17, 17 A, 21 / 4)..." id="house_number" name="house_number" required>
    </div>

    <div class="mb-3">
        <label for="city">Grad:</label>
        <input type="text" class="form-control" placeholder="Unesi grad..." id="city" name="city" required>
    </div>

    <div class="mb-3">
        <label for="postal">Poštanski broj:</label>
        <input type="text" class="form-control" placeholder="Unesi poštanski broj..." id="postal" name="postal" required>
    </div>

    <div class="mb-3">
        <label for="country_id">Država:</label>
        <select class="form-select searchable-customer-modal" id="country_id" name="country_id" required>
        <option disabled selected>Odaberi državu...</option>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>                                  
        @endforeach
        </select>
    </div>
    
</div>