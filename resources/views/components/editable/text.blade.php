{{-- SIMPLE MODE: samo span, bez labela, ikona, gumba --}}
@if($simple)
    <span class="editable"
          data-id="{{ $model->id }}"
          data-field="{{ $field }}"
          data-model="{{ $modelName }}">
        {{ $value }}
    </span>
    @php return; @endphp
@endif


{{-- LABEL IZNAD --}}
@if($label && !$labelInline)
    <label class="editable-label">{{ $label }}</label>
@endif


{{-- INLINE LABEL (lijevo od teksta) --}}
@if($labelInline)
    <div class="d-flex align-items-center gap-2">
        <span class="editable-inline-label">{{ $label }}:</span>
@endif


{{-- G L A V N I   W R A P P E R --}}
<div class="editable-text-wrapper d-flex align-items-center gap-2"
     data-id="{{ $model->id }}"
     data-field="{{ $field }}"
     data-model="{{ $modelName }}">

    {{-- Lijeva ikona (bootstrap ikon class) --}}
    @if($leftIcon)
        <i class="bi {{ $leftIcon }} text-secondary small"></i>
    @endif

    {{-- Tekst --}}
    <span class="editable-text-value">{{ $value }}</span>

    {{-- Edit pencil --}}
    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-start">
        <i class="bi bi-pencil-square"></i>
    </button>
</div>


{{-- Zatvaranje inline label wrappa --}}
@if($labelInline)
    </div>
@endif
