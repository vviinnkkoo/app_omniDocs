{{-- Order item list part --}}
<div class="col-xl-12">
    <div class="card" style="margin-top: 30px;">
        <div class="card-header d-flex align-items-center fw-bolder" style="background-color: #19875411;">
            <span class="me-2">Proizvodi</span>
            <button id="popupButton" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-weight: 900;">+</button>
        </div>
        <div class="card-body" style=" border: solid 4px #19875411">
            <div class="table-responsive-md">
                <table class="table table-hover">
                    <thead class="table-light">
                    <tr>                          
                        <th scope="col">#</th>
                        <th scope="col">Proizvod</th>
                        <th scope="col">Boja</th>
                        <th scope="col">Količina</th>
                        <th scope="col">Cijena</th>
                        <th scope="col">Popust</th>
                        <th scope="col">Opis</th>
                        <th scope="col">Opis na računu</th>
                        <th scope="col">Status izrade</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItemList as $item)
                            <tr>
                                {{-- Broj --}}
                                <td class="align-middle text-right">{{ $loop->iteration }}</td>
                                {{-- Ime proizvoda --}}
                                <td class="align-middle text-right">
                                    <div class="editable-select" data-id="{{ $item->id }}" data-field="product_id" data-model="order-item-list">
                                    {{-- Display the selected value --}}
                                    <span>{{ $item->productName }}</span>                                    
                                    {{-- Hidden select element with options --}}
                                    <select class="edit-select form-select" style="display: none !important">
                                        <option value="" selected>Odaberi proizvod...</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>                                  
                                        @endforeach 
                                    </select>
                                    </div>
                                </td>
                                {{-- Boja --}}
                                <td class="align-middle text-right">
                                    <div class="editable-select" data-id="{{ $item->id }}" data-field="color_id" data-model="order-item-list">
                                    {{-- Display the selected value --}}
                                    <span>{{ $item->colorName }}</span>                                    
                                    {{-- Hidden select element with options --}}
                                    <select class="edit-select form-select" style="display: none !important">
                                        <option value="" selected>Odaberi boju...</option>
                                        @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach 
                                    </select>
                                    </div>
                                </td>
                                {{-- Količina --}}
                                <td class="align-middle text-right">
                                    <span class="editable" data-id="{{ $item->id }}" data-field="amount" data-model="order-item-list">{{ $item->formattedAmount }}</span> {{ $item->unit }}
                                </td>
                                {{-- Cijena --}}
                                <td class="align-middle text-right">
                                    <span class="editable" data-id="{{ $item->id }}" data-field="price" data-model="order-item-list">{{ $item->price }}</span> €
                                </td>
                                {{-- Popust --}}
                                <td class="align-middle text-right">
                                    <span class="editable" data-id="{{ $item->id }}" data-field="discount" data-model="order-item-list">{{ $item->discount }}</span> %
                                </td>
                                {{-- Opis --}}
                                <td class="align-middle text-right">
                                    <span class="editable" data-id="{{ $item->id }}" data-field="note" data-model="order-item-list">{{ $item->note }}</span>
                                </td>
                                {{-- Prikaz napomene na računu --}}
                                <td class="align-middle text-right">
                                    <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="note-on-invoice">
                                    <input class="form-check-input edit-checkbox" type="checkbox" name="note_on_invoice" id="flexSwitchCheckDefault" {{ $item->note_on_invoice ? 'checked' : '' }}>
                                    </div>
                                </td>
                                {{-- Status izrade --}}
                                <td class="align-middle text-right">
                                    <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="order-item-list">
                                    <input class="form-check-input edit-checkbox" type="checkbox" name="is_done" id="flexSwitchCheckDefault" {{ $item->is_done ? 'checked' : '' }}>
                                    </div>
                                </td>
                                {{-- Delete button --}}
                                <td>
                                    <x-delete-button :id="$item->id" model="order-item-list" />
                                </td>
                            <tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>