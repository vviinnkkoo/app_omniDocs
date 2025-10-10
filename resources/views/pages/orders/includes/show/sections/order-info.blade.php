{{-- Order info header --}}
<div class="card-header d-flex align-items-center" style="font-weight: 900;">

    {{-- Header left side --}}
    <a class="gray-mark-extra" href="/narudzbe/prikaz/neodradene"><i class="bi bi-arrow-left"></i></a>

    <span style="font-size:100%; margin-left:10px;">
        Narudžba: {{$order->id}}
    </span>
    
    <span style="font-size:100%; margin-left:30px;" class="badge bg-secondary">
        Naručeno: {{ number_format($order->subtotal, 2, ',') }} €
    </span>

    <span style="font-size:100%; margin-left:15px; color:#333" class="badge bg-warning">
        Dostava: {{ number_format($order->delivery_cost, 2, ',') }} €
    </span>

    <span style="font-size:100%; margin-left:15px;" >
        >>
    </span>

    <span style="font-size:100%; margin-left:15px; margin-right:30px;" class="badge bg-success">
        Sveukupno: {{ number_format($order->total, 2, ',') }} €
    </span>

    {{-- Header right side --}}
    {{-- Invoice check START --}}
    <span class="ms-auto">Račun:
    @isset($order->receipt_id)
        <a href="/dokument/racun/{{ $order->receipt_id }}" target="_blank"
        class="btn {{ $order->is_paid ? 'btn-success' : 'btn-danger' }} btn-sm"><i class="bi bi-filetype-pdf"></i> {{ $order->is_paid ? 'Plaćen' : 'Nenaplaćen' }}</a>
    @else
        <button id="popupButton" class="btn btn-primary btn-sm" style="font-weight:bold;" data-bs-toggle="modal" data-bs-target="#invoiceModal"><i class="bi bi-file-earmark-plus"></i> Izradi</button>
    @endisset
    </span>

    {{-- Invoice check END --}}
    <div style="width:4px; background-color:#333; margin-left:10px;"></div>
    <a class="btn bg-warning btn-sm" style="margin-left:10px; color:#333; font-weight:bold;" href="/dokument/ponuda/{{$order->id}}" target="_blank"><i class="bi bi-file-pdf-fill"></i> Ponuda</a>
    <a class="btn bg-info btn-sm" style="margin-left:10px; color:#333; font-weight:bold;" href="/dokument/otpremnica/{{$order->id}}" target="_blank"><i class="bi bi-file-pdf-fill"></i> Otpremnica</a>
</div>

{{-- Order details --}}
<div class="card-body">
    <div class="row">
        <div class="col">

            <div>
                <h5>Kupac:</h5>
            </div>

            <div>
                <h6 style="font-weight: 900">
                    {{ $order->customer_name }}
                </h6>
            </div>

            <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_address" data-model="narudzbe">
                    {{ $order->delivery_address }}
                </span>
            </div>

            <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_postal" data-model="narudzbe">
                    {{ $order->delivery_postal }}
                </span>, 
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_city" data-model="narudzbe">
                    {{ $order->delivery_city }}
                </span>
            </div>

            <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_country_id" data-model="narudzbe">
                {{-- Display the selected value --}}
                <span>{{ $order->countryName }}</span>
                {{-- Hidden select element with options --}}
                <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi državu...</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach 
                </select>
            </div>

            <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_email" data-model="narudzbe">{{ $order->delivery_email }}</span>
            </div>

            <div>
                <span class="editable" data-id="{{ $order->id }}" data-field="delivery_phone" data-model="narudzbe">{{ $order->delivery_phone }}</span>
            </div>

        </div>

        <div class="col">

            {{-- Date ordered --}}
            <div class="mb-3">
                <div>Naručeno:</div>
                <x-editable-date :model="$order" field="date_ordered" modelName="narudzbe" />
            </div>

            {{-- Deadline date --}}
            <div class="mb-3">
                <div>Rok za dostavu:</div>
                <x-editable-date :model="$order" field="date_deadline" modelName="narudzbe" />
            </div>

            {{-- Payment type --}}
            <div class="mb-3">                
                <div>Način plaćanja:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="payment_type_id" data-model="narudzbe">
                    {{-- Display the selected value --}}
                    <span class="gray-mark">{{ $order->paymentTypeName }}</span>                  
                    {{-- Hidden select element with options --}}
                    <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi način plaćanja...</option>
                        @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                        @endforeach 
                    </select>
                </div>
            </div>

            {{-- Sales source --}}
            <div class="mb-3">
                <div>Kanal prodaje:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="source_id" data-model="narudzbe">
                    {{-- Display the selected value --}}
                    <span class="gray-mark">{{ $order->source_name }}</span>                  
                    {{-- Hidden select element with options --}}
                    <select class="edit-select form-select" style="display: none !important">
                    <option value="" selected>Odaberi kanal prodaje...</option>
                        @foreach ($sources as $source)
                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach 
                    </select>
                </div>
            </div>

        </div>

        <div class="col">

            {{-- Date sent --}}
            <div class="mb-3">
                <div>Datum slanja:</div>
                <x-editable-date :model="$order" field="date_sent" modelName="narudzbe" />
            </div>

            {{-- Delivery company --}}
            <div class="mb-3">
                <div>Dostavna služba:</div>
                <div class="editable-select" data-id="{{ $order->id }}" data-field="delivery_service_id" data-model="narudzbe">
                    {{-- Display the selected value --}}
                    <span class="gray-mark">{{ $order->delivery_company_name }} - {{ $order->delivery_service_name }}</span>
                    {{-- Hidden select element with options --}}
                    <select class="edit-select form-select" style="display: none !important">
                        <option selected>Odaberi dostavnu službu...</option>
                            @foreach ($deliveryCompanies as $company)
                            <optgroup label="{{ $company->name }}">
                                @foreach ($company->deliveryServices as $service)
                                @if ($service->in_use == 1)
                                    <option value="{{ $service->id }}">{{ $service->name }} >> {{ $service->default_cost }} €</option>
                                @endif
                                @endforeach
                            @endforeach
                    </select>                    
                </div>
            </div>

            {{-- Tracking code --}}
            <div class="mb-3">
                <div>Kod za praćenje:</div>
                <div>
                    <span class="editable gray-mark" data-id="{{ $order->id }}" data-field="tracking_code" data-model="narudzbe">{{ $order->tracking_code }}</span>&nbsp;
                    <span>
                    @include("includes.tracking-code-condition")
                    </span>
                </div>
            </div>
        </div>

        <div class="col">
            {{-- Completion date --}}
            <div class="mb-3">
                <div>Datum dostave / završetka:</div>
                <x-editable-date :model="$order" field="date_delivered" modelName="narudzbe" />
            </div>
            {{-- Canceling date --}}
            <div class="mb-3">
                <div>Datum otkazivanja:</div>
                <x-editable-date :model="$order" field="date_cancelled" modelName="narudzbe" />
            </div>
        </div>

    </div>
</div>