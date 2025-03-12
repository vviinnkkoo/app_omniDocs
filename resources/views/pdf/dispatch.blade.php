<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Otpremnica kupcu br: {{ $orderData['id'] }}</title>
    
    @include('includes.pdf.style')

</head>
<body>

    @include('includes.pdf.dispatch-header')

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
                    <div><h4>Datum isporuke:</h4></div>
                    <div>{{ $orderData['dateSent'] }}</div>
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
        <p><b>Broj narudžbe:</b> {{ $orderData['id'] }}</p>
    </div>

    <div class="margin-signatures">
        <table class="w-full info">
            <tr>
                <td class="w-tri center"><div style="border-top: solid 1px black;">Robu izdao</div></td>
                <td class="w-tri center"></td>
                <td class="w-tri center"><div style="border-top: solid 1px black;">Robu zaprimio</div></td>
            </tr>
        </table>
    </div>

    {{-- PDF content - END --}}

</body>
</html>