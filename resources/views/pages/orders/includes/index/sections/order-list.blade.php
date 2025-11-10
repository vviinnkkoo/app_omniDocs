{{-- Orders table --}}
<div class="table-responsive-md">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Kupac</th>
                <th scope="col">Datum</th>
                <th scope="col">Izvor</th>
                <th scope="col">Poštanski br.</th>
                <th scope="col">Dostava</th>
                <th scope="col">Plaćanje</th>
                <th scope="col">Status</th>
                <th scope="col">Broj pošiljke</th>
                <th scope="col">Iznos</th>
                <th scope="col">Račun</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                    
                @if (isset($order->date_cancelled))
                    <tr class="cancelled-order">
                @else
                    <tr>
                @endif

                    <td class="align-middle text-right">{{ $order->id }}</td>

                    <td class="align-middle text-right"><a class="btn btn-sm btn-primary position-relative" href="{{ route('narudzbe.show', $order->id) }}">{{ $order->customer_name }}
                        @if ($order->isOrderDone() && is_null($order->date_sent))
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                            <i class="bi bi-check"></i>
                            </span>
                        @endif
                        </a>
                    </td>

                    <td class="align-middle text-right">{{ $order->formated_date_ordered }}</td>
                    <td class="align-middle text-right">{{ $order->source_name }}</td>
                    <td class="align-middle text-right">{{ $order->delivery_postal }}</td>
                    <td class="align-middle text-right">{{ $order->delivery_company_name }} - {{ $order->delivery_service_name }}</td>
                    <td class="align-middle text-right">{{ $order->payment_type_name }}</td>

                    {{-- Status --}}
                    <td class="align-middle text-right">

                        {{-- Check if cancelled --}}
                        @if (isset($order->date_cancelled))
                        <span class="btn btn-sm btn-dark">Otkazano<br>
                            <span style="font-size: 80%"><b>{{ $order->formated_date_cancelled }}</b></span>
                        </span>

                        {{-- Check if delivered --}}
                        @elseif (isset($order->date_delivered))
                        <span class="btn btn-sm btn-success">Dostavljeno<br>
                            <span style="font-size: 80%">{{ $order->formated_date_delivered }} (<b>{{ $order->daysToDeliver }} d</b>)</span>
                        </span>

                        {{-- If not delivered, check if it's sent --}}
                        @elseif (isset($order->date_sent))
                        <span class="btn btn-sm btn-secondary">Poslano<br>
                            <span style="font-size: 80%">{{ $order->formated_date_sent }}</span>
                        </span>

                        {{-- Deadline check --}}
                        @else
                        @if (isset($order->date_deadline))
                            @if ($order->days_left)
                            <span class="btn btn-sm {{ $order->deadline_class }}">
                                Rok: <b>{{ $order->days_left }} d</b><br>
                                <span style="font-size: 80%">{{ $order->formated_date_deadline }}</span>
                            </span>
                            @else
                            <span class="text-danger">Prošao rok</span>
                            @endif
                        @else
                            <span class="text-muted">Nema</span>
                        @endif
                        @endif

                    </td>

                    {{-- Tracking number --}}
                    <td class="align-middle text-right">
                        @include("includes.tracking-code-condition")                                    
                    </td>

                    {{-- Total --}}
                    @if ($order->total_amount == 0)
                        <td></td>
                    @else
                    <td class="align-middle text-right">{{ number_format($order->total_amount, 2, ',') }} €</td>
                    @endif
                    
                    {{-- Has invoice --}}
                    <td class="align-middle text-right">
                        @isset($order->receipt_id)
                        
                        <a href="{{ route('generate.document', ['mode' => 'racun', 'id' => $order->receipt_id]) }}" target="_blank" 
                            class="btn {{ $order->is_paid ? 'btn-success' : 'btn-danger' }}">
                            <i class="bi bi-filetype-pdf"></i></a>
                        @else
                        Nema
                        @endisset
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>