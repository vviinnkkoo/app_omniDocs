<form method="GET" action="{{ $action }}" class="{{ $class }}">
  <div class="input-group">
    <input 
      type="text" 
      name="search" 
      class="form-control" 
      placeholder="{{ $placeholder }}" 
      value="{{ request('search') }}"
    >

    <button type="submit" class="btn btn-primary">
      {{ $buttonText }}
    </button>

    @if (request('search'))
      <a href="{{ $action }}" class="btn btn-outline-secondary">
        <i class="bi bi-x-lg"></i>
      </a>
    @endif
  </div>
</form>
