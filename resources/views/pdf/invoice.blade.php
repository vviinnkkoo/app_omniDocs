<!doctype html>
<html lang="hr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Račun {{ $invoice->number }}-{{ $invoice->businessSpace->name }}-{{ $invoice->businessDevice->name }}</title>

    @include('includes.pdf.style')
</head>
<body>

@include('includes.pdf.invoice-header')

{{-- PDF content - START --}}

<div class="margin-first">
    <table class="w-full info">
        <tr>
            <td class="w-tri">
                <div><h4>Kupac:</h4></div>
                <div>{{ $invoice->customer_name }}</div>
                <div>{{ $invoice->customer_address }}</div>
                <div>{{ $invoice->customer_city }}, {{ $invoice->customer_postal }}</div>
                <div style="margin-top:10px">
                    <b>OIB:</b> {{ $invoice->customer_oib }}
                </div>
            </td>

            <td class="w-tri">
                <div><h4>Datum i vrijeme izdavanja:</h4></div>
                <div>{{ $invoice->businessSpace->city }}</div>
                <div>{{ $invoice->formatted_issued_date }}</div>
                <div>u {{ $invoice->formatted_issued_time }}</div>

                @if($invoice->delivery_date)
                    <div style="margin-top:10px">
                        <b>Datum isporuke:</b>
                        {{ $invoice->formatted_delivery_date }}
                    </div>
                @endif

                <div>
                    <b>Datum dospijeća:</b>
                    {{ $invoice->formatted_due_date }}
                </div>
            </td>

            <td class="w-tri">
                <div><h4>Kontakt:</h4></div>
                <div><b>Email:</b> {{ $appSettings['contact_email'] }}</div>
                <div><b>Mob:</b> {{ $appSettings['contact_phone'] }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- Stavke (privremeno još orderItemList) --}}
@include('includes.pdf.shared.order-items')

<div class="notes">
    <p><b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.</p>

    <p>
        <b>Račun izdaje:</b> {{ $invoice->issued_by }}
    </p>

    <p>
        <b>Poziv na broj:</b> {{ $invoice->number }}-{{ $invoice->year }}
    </p>

    <p>
        <b>Broj narudžbe:</b> {{ $invoice->order_id }}
    </p>
</div>

{{-- PDF content - END --}}
{{-- @include('includes.pdf.shared.signature-stamp') --}}

</body>
</html>
