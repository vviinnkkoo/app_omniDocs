<div class="mb-3">
    @if ($label)
        <span style="display: block;">{{ $label }}:</span>
    @endif

    @foreach ($items as $item)
        <input type="radio"
               class="btn-check"
               name="{{ $name }}"
               id="{{ $name }}_{{ $item->id }}"
               value="{{ $item->id }}"
               autocomplete="off"
               @if ($required) required @endif>

        <label class="btn btn-light btn-sm me-1 mb-1"
               for="{{ $name }}_{{ $item->id }}">
            {{ $item->name }}
        </label>
    @endforeach
</div>
