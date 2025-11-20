<div class="{{ $wrapperClass }}">
    @if($labelText)
        <span style="display: block;">{{ $labelText }}</span>
    @endif

    @foreach($items as $item)
        @php
            $itemId = $name . $item->id;
        @endphp
        <input type="{{ $type }}"
               class="{{ $inputClass }}"
               name="{{ $name }}{{ $type === 'checkbox' ? '[]' : '' }}"
               id="{{ $itemId }}"
               value="{{ $item->id }}"
               autocomplete="off"
               @if($required && $type === 'radio') required @endif>
        <label class="{{ $labelClass }}" for="{{ $itemId }}">
            {{ $item->name }}
        </label>
    @endforeach
</div>