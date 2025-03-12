<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ponuda br: {{ $orderData['id'] }}</title>

    @include('includes.pdf.style')

</head>
<body>

    @include('includes.pdf.quotation-header')

    {{-- PDF content - START --}}

    <div class="margin-first">
        <table class="w-full info">
            <tr>
                <td class="w-tri">
                    <div><h4>Kupac:</h4></div>
                    <div>{{ $orderData['customerName'] }}</div>
                    <div>{{ $orderData['deliveryAddress'] }}</div>
                    <div>{{ $orderData['deliveryCity'] }}, {{ $orderData['deliveryPostal'] }}</div>
                    <div>{{ $orderData['countryName'] }}</div>
                    <div style="margin-top:10px"><b>OIB: </b>{{ $orderData['customerOib'] }}</div>
                </td>
                <td class="w-tri">
                    <div><h4>Datum i vrijeme izdavanja:</h4></div>
                    <div>{{ $appSettings['address_city'] }}</div>
                    <div>{{ $orderData['dateOrdered'] }}</div>
                    <div>u {{ $orderData['timeOrdered'] }}</div>
                    <div><b>Ponuda vrijedi do: </b>{{ $orderData['eta'] }}</div>
                </td>
                <td class="w-tri">
                    <div><h4>Kontakt:</h4></div>
                    <div><b>Email:</b> {{ $appSettings['contact_email'] }}</div>
                    <div><b>Mob:</b> {{ $appSettings['contact_phone'] }}</div>
                </td>
                
            </tr>
        </table>
    </div>

    @include('includes.pdf.shared.order-items')

    <div class="notes">
        <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.</p>
        <p><b>Način plaćanja:</b> {{ $orderData['paymentTypeName'] }} &nbsp;&nbsp; <b>Ponudu izdaje:</b> {{ $appSettings['invoice_issuer_01'] }}</p>
        <p><b>Poziv na broj:</b> 1512-{{ $orderData['id'] }}</p>
        <p><b>Broj narudžbe:</b> {{ $orderData['id'] }}</p>
    </div>

    {{-- PDF content - END --}}

    @include('includes.pdf.shared.signature-stamp')

</body>
</html>