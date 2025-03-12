<!-- Define header and footer blocks before your content -->
<header>
    <table class="w-full" style="border-bottom: solid 1px black">
        <tr>
            <td class="w-half">
                <img src="{{ asset( $appSettings['invoice_logo'] ) }}" height="54" />
            </td>
            <td class="w-half">
                <h2>PONUDA br: <span class="gray-overlay">{{ $orderData['id'] }}</span></h2>
            </td>
        </tr>
    </table>
</header>

<footer>
    <div class="center footer-content">
        <div><b>{{ $appSettings['company_name'] }}</b>, {{ $appSettings['company_extra_info'] }} | Adresa vlasnika: <b>{{ $appSettings['address'] }}, {{ $appSettings['address_city'] }}</b> | OIB: <b>{{ $appSettings['company_oib'] }}</b></div>
        <div>Porezni broj: <b>{{ $appSettings['company_vat_id'] }}</b> | Žiro račun IBAN: <b>{{ $appSettings['company_iban'] }}</b> otvoren u: <b>{{ $appSettings['company_bank'] }}</b></div>
    </div>
</footer>