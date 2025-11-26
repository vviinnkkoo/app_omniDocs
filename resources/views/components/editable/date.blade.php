<div class="mb-3">
    <div>{{ $label }}</div>
    <div class="editable-date d-flex align-items-center"
        data-id="{{ $model->id }}"
        data-field="{{ $field }}"
        data-model="{{ $modelName }}"
        data-inputdate="{{ $model->{'input_formated_' . $field} }}"
        data-formateddate="{{ $model->{'formated_' . $field} ?? 'Nema' }}">
        <span class="date-text me-2">{{ $model->{'formated_' . $field} ?? 'Nema' }}</span>
        <button class="edit-btn btn btn-sm btn-light" style="border:none; background:none; cursor:pointer;">
            <i class="bi bi-pencil-fill"></i>
        </button>
    </div>
</div>