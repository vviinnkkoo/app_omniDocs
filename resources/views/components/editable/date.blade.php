@if($label && !$labelInline)
    <label class="editable-label">{{ $label }}</label>
@endif

@if($labelInline)
    <div class="d-flex align-items-center gap-2">
        <span class="editable-inline-label">{{ $label }}:</span>
@endif

<div class="editable-date-wrapper d-flex align-items-center gap-2"
     data-id="{{ $model->id }}"
     data-field="{{ $field }}"
     data-model="{{ $modelName }}"
     data-inputdate="{{ $model->{'input_formated_' . $field} }}"
     data-formateddate="{{ $model->{'formated_' . $field} ?? 'Nema' }}">

    @if($leftIcon)
        <i class="bi {{ $leftIcon }} text-secondary small"></i>
    @endif

    <span class="date-text me-2">{{ $model->{'formated_' . $field} ?? 'Nema' }}</span>

    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-start">
        <i class="bi bi-pencil-fill"></i>
    </button>

    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-confirm d-none">
        <i class="bi bi-check-lg text-success"></i>
    </button>

    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-cancel d-none">
        <i class="bi bi-x-lg text-danger"></i>
    </button>
</div>

@if($labelInline)
    </div>
@endif