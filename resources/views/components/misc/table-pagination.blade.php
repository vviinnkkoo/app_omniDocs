<div class="d-flex justify-content-center">
  {{ $items->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
</div>