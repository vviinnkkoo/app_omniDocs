<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Otpremnica kupcu</title>
    
    @include('parts.pdf.style')

</head>
<body>

    @include('parts.pdf.dispatch-header')

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
                    <div><b>Email:</b> {{$appSettings['contact_email']}}</div>
                    <div><b>Mob:</b> {{$appSettings['contact_phone']}}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Šifra</th>
                <th>Naziv</th>
                <th>Količina</th>
                <th>Cijena</th>
                <th>Popust</th>
                <th>Iznos</th>
            </tr>

            {{-- Order items loop --}}
            @foreach ($orderItemList as $item)
                <tr class="items">
                    <td style="center">{{ $item->product_id }}-{{ $item->color_id }}</td>
                    <td>
                        {{ App\Models\Product::find($item->product_id)->product_name }}<br>
                        <span style="font-size:70%">Boja: {{ App\Models\Color::find($item->color_id)->color_name }}</span>
                    </td>
                    <td class="center">
                        @if (App\Models\Product::find($item->product_id)->unit == 'kom')
                            {{ number_format(str_replace(',', '.', $item->amount), 0) }} {{ App\Models\Product::find($item->product_id)->unit }}
                        @else
                            {{ $item->amount }} {{ App\Models\Product::find($item->product_id)->unit }}
                        @endif
                    </td>
                    <td class="center">{{ $item->price }} €</td>
                    <td class="center">{{ $item->discount }} %</td>
                    <td class="center">{{ App\Http\Controllers\OrderItemListController::sumSingleItem($item->id) }} €</td>
                </tr>
            @endforeach

            {{-- Delivery service --}}
            <tr class="items">
                <td></td>
                <td><b>Dostava:</b> {{ $deliveryService->name }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="center">{{ $deliveryCost }} €</td>  
            </tr>

            {{-- Total --}}
            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="totalAmountText">Sveukupno: </td>
                <td class="totalAmount center"><b>{{ $total }} €</b></td>
            </tr>

        </table>
    </div>

    <div class="notes">
        <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.</p>
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