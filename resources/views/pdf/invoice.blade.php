<!doctype html>
<html lang="hr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Račun {{ $invoice->number }}-{{ $invoice->businessSpace->name }}-{{ $invoice->businessDevice->name }}</title>

    @include('pdf.includes.style')
</head>
<body>

@include('pdf.includes.invoice-header')

{{-- PDF content - START --}}

<div class="margin-first">
    <table class="w-full info">
        <tr>
            <td class="w-tri">
                <div><h4>Kupac:</h4></div>
                <div>{{ $invoice->customer_name }}</div>
                <div>{{ $invoice->customer_address }}</div>
                <div>{{ $invoice->customer_city }}, {{ $invoice->customer_postal }}</div>
                <div>{{ $invoice->customer_country }}</div>

                @if($invoice->customer_phone || $invoice->customer_email)
                    <div style="margin-top:5px">
                        @if($invoice->customer_phone)
                            <div><b>T:</b> {{ $invoice->customer_phone }}</div>
                        @endif

                        @if($invoice->customer_email)
                            <div><b>E:</b> {{ $invoice->customer_email }}</div>
                        @endif
                    </div>
                @endif

                @if($invoice->customer_oib)
                    <div style="margin-top:5px">
                        <b>OIB:</b> {{ $invoice->customer_oib }}
                    </div>
                @endif
            </td>

            <td class="w-tri">
                <div><h4>Datum i vrijeme izdavanja:</h4></div>
                <div>{{ $invoice->businessSpace->city }}</div>
                <div>{{ $invoice->formatted_issued_date }}</div>
                <div>u {{ $invoice->formatted_issued_time }}</div>

                @if($invoice->shipping_date)
                    <div style="margin-top:10px">
                        <b>Datum isporuke:</b>
                        {{ $invoice->formatted_shipping_date }}
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

@include('pdf.includes.shared.invoice-items')

<div class="notes">
    <div style="mt-10">
        <b>Napomena:</b> Oslobođeno PDV-a temeljem članka 90. st. 1 Zakona o PDV-u.
    </div>

    <div class="mt-10">
        <b>Račun izdaje:</b> {{ $invoice->issued_by }}
    </div>

    <div class="mt-10">
        <div><b>Poziv na broj:</b> {{ $invoice->number }}-{{ $invoice->year }}</div>
        @if($invoice->order_id)
            <div><b>Interni broj narudžbe:</b> {{ $invoice->order_id }}</div>
        @endif
    </div>

    <div class="mt-10">
        <div><b>Način plaćanja:</b> {{ $invoice->paymentType->name }}</div>
    </div>
</div>

{{-- PDF content - END --}}

</body>
</html>
