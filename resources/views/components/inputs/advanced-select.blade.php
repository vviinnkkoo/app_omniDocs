{{-- resources/views/components/inputs/advanced-select.blade.php --}}

<div class="mb-3 omniselect-dropdown">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}:</label>
    @endif

    <input type="text"
           class="form-control omniselect"
           data-name="{{ $name }}"
           placeholder="{{ $placeholder }}"
           autocomplete="off"
           @if($required) required @endif>

    <input type="hidden" name="{{ $name }}" class="omniselect-hidden">

    <ul class="dropdown-menu w-100">
        @if ($grouped)
            @foreach ($items as $group)
                <li class="dropdown-group">{{ $group->name }}</li>

                @php
                    $children = $group->items ?? $group->product ?? [];
                @endphp

                @foreach ($children as $item)
                    <li>
                        <a href="#" data-value="{{ $item->id }}">
                            {!! $renderItem ? $renderItem($item) : e($item->name) !!}
                        </a>
                    </li>
                @endforeach
            @endforeach
        @else
            @foreach ($items as $item)
                <li>
                    <a href="#" data-value="{{ $item->id }}">
                        {!! $renderItem ? $renderItem($item) : e($item->name) !!}
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
