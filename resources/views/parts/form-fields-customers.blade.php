{{ csrf_field() }}
<div class="form-group">

    <div class="mb-3">
        <label for="name">Ime:</label>
        <input type="text" class="form-control" placeholder="Unesi ime..." id="name" name="name">
    </div>

    <div class="mb-3">
        <label for="name">OIB:</label>
        <input type="text" class="form-control" placeholder="Unesi OIB..." id="oib" name="oib" value="- - -">
    </div>

    <div class="mb-3">
        <label for="email">Email:</label>
        <input type="text" class="form-control" placeholder="Unesi email..." id="email" name="email" value="- - -">
    </div>

    <div class="mb-3">
        <label for="phone">Mobitel:</label>
        <input type="text" class="form-control" placeholder="Unesi mobitel..." id="phone" name="phone" value="- - -">
    </div>

    <div class="mb-3">
        <label for="address">Adresa:</label>
        <input type="text" class="form-control" placeholder="Unesi adresu..." id="address" name="address">
    </div>

    <div class="mb-3">
        <label for="address">Kućni broj:</label>
        <input type="text" class="form-control" placeholder="Unesi kućni broj (17, 17 A, 21 / 4)..." id="house_number" name="house_number">
    </div>

    <div class="mb-3">
        <label for="city">Grad:</label>
        <input type="text" class="form-control" placeholder="Unesi grad..." id="city" name="city">
    </div>

    <div class="mb-3">
        <label for="postal">Poštanski broj:</label>
        <input type="text" class="form-control" placeholder="Unesi poštanski broj..." id="postal" name="postal">
    </div>

    <div class="mb-3">
        <label for="country_id">Država:</label>
        <select class="form-select searchable-customer-modal" id="country_id" name="country_id">
        <option selected>Odaberi državu...</option>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}">{{ $country->country_name }}</option>                                  
        @endforeach
        </select>
    </div>
    
</div>