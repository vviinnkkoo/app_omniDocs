{{-- Notes (30% desktop, 100% mobile) --}}
<div class="col-12 col-lg-3 mb-3 d-flex">
    <div class="card flex-fill">

        <div class="card-header d-flex align-items-center fw-bolder" style="background-color: #ffc10711;">
            <span class="me-2">Napomene</span>
            <x-modal-button target="#addNoteModal" text="+"/>
        </div>

        <div class="card-body" style="border: solid 4px #ffc10711">
            <div class="row">
                @foreach ($orderNotes as $item)
                <div class="mb-3 ajax-deletable">
                    <div class="p-3 rounded" style="background:#f8f9fa; border:1px solid #dee2e6;">

                    {{-- Header (datum + delete) --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">{{ $item->created_at->format('d. m. Y. H:i') }}</small>
                        <x-delete-button :id="$item->id" model="napomena" />
                    </div>

                    {{-- Note --}}
                    <div>
                        <span class="editable" data-id="{{ $item->id }}" data-field="note" data-model="napomena"> {{ $item->note }}</span>
                    </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
