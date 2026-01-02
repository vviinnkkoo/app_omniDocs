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

        @foreach($groupedItems as $groupKey => $items)
            <tr class="items-header">
                <td colspan="6" class="font-weight-bold pl-5">
                    {{ $groupLabels[$groupKey] }}
                </td>
            </tr>

            @foreach ($items as $item)
                <tr class="items">

                    <td class="center">
                        {{ $item->item_id }}
                    </td>

                    <td>
                        {{ $item->name ?? 'Nije definirano' }}
                        <div class="small-text">
                            @if(!empty($item->description))
                            <b>Opis:</b> {{ $item->description ?? '' }}
                            @endif
                            @if(!empty($item->note))
                                &nbsp;<b>Napomena:</b> {{ $item->note }}
                            @endif
                        </div>
                    </td>

                    <td class="center">
                        @if ($item->unit == 'kom')
                            {{ (int) number_format($item->amount, 0) }}
                        @else
                            {{ number_format($item->amount, 2, ',', '.') }}
                        @endif
                        {{ $item->unit }}
                    </td>

                    <td class="right">
                        {{ number_format($item->price ?? 0, 2, ',', '.') }} €
                    </td>

                    <td class="center">
                        {{ (int) ($item->discount_percentage ?? 0) }} %
                        @if (($item->discount_percentage ?? 0) != 0)
                            <div class="small-text">{{ number_format($item->discount_amount ?? 0, 2, ',', '.') }} €</div>
                        @endif
                    </td>

                    <td class="center">
                        {{ number_format($item->total ?? 0, 2, ',', '.') }} €
                    </td>

                </tr>
            @endforeach
        @endforeach

        {{-- Total --}}
        <tr class="total">
            <td colspan="5" class="totalAmountText">Sveukupno: </td>
            <td class="totalAmount center"><b>{{ number_format($total, 2, ',', '.') }} €</b></td>
        </tr>
    </table>
</div>
