{{-- Shipment modal --}}
<div class="modal fade" id="shipmentModal" tabindex="-1" aria-labelledby="shipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shipmentModalLabel">Nova pošiljka</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/shipments" id="shipmentSubmission">
          {{ csrf_field() }}
          <div class="form-group">

            {{-- Order ID --}}
            <div class="mb-3">
              <label for="order_id">Narudžba (order_id):</label>
              <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Predefinirano iz otvorene narudžbe" readonly>
            </div>

            {{-- Delivery service --}}
            <div class="mb-3">
              <label for="delivery_service_id">Dostavna služba:</label>
              <input type="text" class="form-control" id="delivery_service_id" name="delivery_service_id" placeholder="Predefinirano iz narudžbe" readonly>
            </div>

            {{-- Status --}}
            <input type="hidden" name="status" value="created">

            {{-- Tracking number --}}
            <div class="mb-3">
              <label for="tracking_number">Tracking broj:</label>
              <input type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Nije obavezno">
            </div>

            {{-- Dates (optional) --}}
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="date_shipped">Datum slanja:</label>
                <input type="date" class="form-control" id="date_shipped" name="date_shipped">
              </div>
              <div class="col-md-4 mb-3">
                <label for="date_delivered">Datum dostave:</label>
                <input type="date" class="form-control" id="date_delivered" name="date_delivered">
              </div>
              <div class="col-md-4 mb-3">
                <label for="date_cancelled">Datum otkazivanja:</label>
                <input type="date" class="form-control" id="date_cancelled" name="date_cancelled">
              </div>
            </div>

            {{-- Weight & COD --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="weight">Težina (kg):</label>
                <input type="number" step="0.01" class="form-control" id="weight" name="weight">
              </div>
              <div class="col-md-6 mb-3">
                <label for="cod_price">Cijena pouzeća (€):</label>
                <input type="number" step="0.01" class="form-control" id="cod_price" name="cod_price">
              </div>
            </div>

            {{-- Recipient address toggle --}}
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="useOrderAddress" checked>
              <label class="form-check-label" for="useOrderAddress">Učitaj adresu iz narudžbe</label>
            </div>

            {{-- Recipient details --}}
            <div id="recipientFields" style="display: none;">
              <div class="mb-3">
                <label for="recipient_name">Ime i prezime:</label>
                <input type="text" class="form-control" id="recipient_name" name="recipient_name">
              </div>
              <div class="row">
                <div class="col-md-8 mb-3">
                  <label for="recipient_address_name">Ulica:</label>
                  <input type="text" class="form-control" id="recipient_address_name" name="recipient_address_name">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="recipient_address_number">Kućni broj:</label>
                  <input type="text" class="form-control" id="recipient_address_number" name="recipient_address_number">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="recipient_postcode">Poštanski broj:</label>
                  <input type="text" class="form-control" id="recipient_postcode" name="recipient_postcode">
                </div>
                <div class="col-md-8 mb-3">
                  <label for="recipient_city">Grad:</label>
                  <input type="text" class="form-control" id="recipient_city" name="recipient_city">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="recipient_country">Država:</label>
                  <input type="text" class="form-control" id="recipient_country" name="recipient_country">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="recipient_country_code">Country code:</label>
                  <input type="text" class="form-control" id="recipient_country_code" name="recipient_country_code" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="recipient_email">Email:</label>
                  <input type="email" class="form-control" id="recipient_email" name="recipient_email">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="recipient_phone">Telefon:</label>
                  <input type="text" class="form-control" id="recipient_phone" name="recipient_phone">
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="shipmentSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Toggle recipient fields
  document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('useOrderAddress');
    const fields = document.getElementById('recipientFields');
    checkbox.addEventListener('change', function () {
      fields.style.display = this.checked ? 'none' : 'block';
    });
  });
</script>
