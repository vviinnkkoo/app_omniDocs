@if (strlen($order->tracking_code) > 6)

    {{-- GLS --}}
    @if ( App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 2 )
        <a href="https://online.gls-croatia.com/index.php?page=tt_page.php?tt_value={{ $order->tracking_code }}" target="_blank" class="btn btn-sm btn-gls">Provjeri <i class="bi bi-arrow-right-circle-fill"></i></a>

    {{-- HP --}}
    @elseif ( App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 3 || App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 1  )
        <a href="https://posiljka.posta.hr/hr/tracking/trackingdata?barcode={{ $order->tracking_code }}" target="_blank" class="btn btn-sm btn-hposta">Provjeri <i class="bi bi-arrow-right-circle-fill"></i></a>

    {{-- BOXNOW --}}
    @elseif ( App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 6 || App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 1  )
        <a href="https://boxnow.hr/?track={{ $order->tracking_code }}" target="_blank" class="btn btn-sm btn-boxnow">Provjeri <i class="bi bi-arrow-right-circle-fill"></i></a>

    {{-- Tisak --}}
    @elseif ( App\Models\DeliveryService::find( $order->delivery_service_id)->delivery_company_id == 4 )
        <a href="https://lokator.tisak.hr/PacketStatusForm.aspx?authToken=tisak&code={{ $order->tracking_code }}" target="_blank" class="btn btn-sm btn-tisak">Provjeri <i class="bi bi-arrow-right-circle-fill"></i></a>
        
    @endif

@endif