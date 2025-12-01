<div class="mb-3">
    <div>{{ $label }}</div>

    <div class="editable-date d-flex align-items-center gap-1"
         data-id="{{ $model->id }}"
         data-field="{{ $field }}"
         data-model="{{ $modelName }}"
         data-inputdate="{{ $model->{'input_formated_' . $field} }}"
         data-formateddate="{{ $model->{'formated_' . $field} ?? 'Nema' }}">

        <span class="date-text">{{ $model->{'formated_' . $field} ?? 'Nema' }}</span>

        <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-start">
            <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-confirm d-none">
            <i class="bi bi-check-lg text-success"></i>
        </button>

        <button type="button" class="btn btn-sm p-0 border-0 bg-transparent edit-cancel d-none">
            <i class="bi bi-x-lg text-danger"></i>
        </button>

    </div>
</div>