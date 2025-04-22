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

                <td style="center">{{$item->productID}}-{{$item->colorID}}</td>

                <td>
                    {{ $item->productName }}<br>
                    <span style="font-size:70%"><b>Opis:</b> {{ $item->colorName }}</span>
                    @if($item->note_on_invoice)
                        <span style="font-size:70%">&nbsp;<b>Napomena:</b> {{ $item->note }}</span>
                    @endif
                </td>

                <td class="center">
                    @if ($item->productUnit == 'kom')
                        {{ number_format($item->amount, 0) }}
                    @else
                        {{ number_format($item->amount, 2, ',', '.') }}
                    @endif
                    {{ $item->productUnit }}
                </td>

                <td class="center">{{ number_format($item->price, 2, ',', '.') }} €</td>
                <td class="center">{{ $item->discount }} %</td>
                <td class="center">{{ number_format($item->itemTotal, 2, ',', '.') }} €</td>                
            </tr>
        @endforeach

        {{-- Delivery service --}}
        <tr class="items" >
            <td></td>
            <td><b>Dostava: </b>{{ $orderData['deliveryCompanyName'] }} - {{ $orderData['deliveryServiceName'] }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td  class="center">{{ $orderData['deliveryCost'] }} €</td>  
        </tr>

        {{-- Total --}}
        <tr class="total">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="totalAmountText">Sveukupno: </td>
            <td class="totalAmount center"><b>{{ number_format($orderData['total'], 2, ',', '.') }} €</b></td>
        </tr>

    </table>
</div>