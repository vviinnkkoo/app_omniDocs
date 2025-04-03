@extends('layouts.app')

@section('title', 'Omnius Art | Knjiga prometa')

@section('content')
<div class="containerx" style="margin-left:15%; margin-right:15%">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                <div class="card-body">

                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Nova uplata</button>

                  {{-- @include('includes.tablesearch') --}}

                  <form method="GET" action="/knjiga-prometa/{{$year}}" class="mb-3">
                    <div class="input-group w-25 float-end">
                        <input type="text" name="search" class="form-control" placeholder="Upiši traženi pojam..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Pretraži</button>
                    </div>
                  </form>

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Platitelj</th>
                          <th scope="col">Datum</th>
                          <th scope="col">Mjesto uplate</th>
                          <th scope="col">Broj naloga</th>
                          <th scope="col">Opis</th>
                          <th scope="col">Iznos</th>
                          <th scope="col">Povezani računi</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach ($kprs as $item)
                          <tr 
                            @if ( $item->exists )
                              class="kpr-has-receipt">
                            @else
                              class="kpr-no-receipt">
                            @endif

                            <td class="align-middle text-start">{{ $count++ }}</td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ $item->payer }}</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ $item->date }}</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ $item->paymentTypeName }}</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ $item->origin }}</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ $item->info }}</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ number_format($item->amount, 2, ',', '.') }} €</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display">{{ number_format($item->receiptsTotal, 2, ',', '.') }} €</div>
                            </td>

                            <td class="align-middle text-start">
                              <div class="date-display"><a href="{{ route('knjiga-prometa.show', $item->id) }}" class="btn btn-success">Uredi</a></div>
                            </td>

                            <td>
                              <button class="btn btn-danger delete-btn-x" data-id="{{ $item->id }}" data-model="knjiga-prometa"><i class="bi bi-x-lg"></i></button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                      {{ $kprs->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



                    


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nova uplata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/knjiga-prometa" id="paymentSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                <div class="mb-3">
                  <label for="payer">Platitelj:</label>
                  <input type="text" class="form-control" placeholder="Unesi platitelja..." id="payer" name="payer">
                </div>

                <div class="mb-3">
                  <label for="date">Datum uplate:</label>
                  <input type="date" class="form-control" id="date" name="date">
                </div>

                <div class="mb-3">
                  <label for="kpr_payment_type_id">Mjesto uplate:</label><br>
                  <select class="form-select searchable-select-modal" id="kpr_payment_type_id" name="kpr_payment_type_id">
                      <option selected>Upišite mjesto uplate...</option>
                      @foreach ($paymentMethods as $method)
                          <option value="{{ $method->id }}">{{ $method->name }}</option>                                    
                      @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="amount">Iznos:</label>
                  <input type="number" class="form-control" placeholder="Unesi iznos uplate..." id="amount" name="amount" step=".01">
                </div>

                <div class="mb-3">
                  <label for="source">Referenca naloga:</label>
                  <input type="text" class="form-control" placeholder="Unesi referencu naloga..." id="origin" name="origin">
                </div>

                <div class="mb-3">
                  <label for="source">Opis:</label>
                  <input type="text" class="form-control" placeholder="Unesi opis uplate..." id="info" name="info">
                </div>

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="paymentSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('includes.deleteconfirmation')

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>