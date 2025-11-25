<div class="mb-3">
    <div>{{ $label }}</div>

    <div class="editable-date d-flex align-items-center gap-2"
         data-id="{{ $model->id }}"
         data-field="{{ $field }}"
         data-model="{{ $modelName }}"
         data-value="{{ $model->{'input_formated_' . $field} }}"
         style="cursor:pointer;">

        <span class="date-text">
            {{ $model->{'formated_' . $field} ?? 'Nema' }}
        </span>

        <button class="edit-btn btn btn-sm btn-light p-1" style="border:none;">
            <i class="bi bi-pencil-fill"></i>
        </button>

    </div>
</div>