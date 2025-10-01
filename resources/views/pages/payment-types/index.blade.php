@extends('layouts.app')

@section('title', 'Omnius Art | Načini plaćanja')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi način plaćanja</button>

                  @include('includes.tablesearch')

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Način plaćanja</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @php ($count = 1)
                        @foreach ($paymentTypes as $paymentType)
                                <tr>
                                    <td class="align-middle text-right">{{ $count++ }}</td>
                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $paymentType->id }}" data-field="type_name" data-model="payment-type">{{ $paymentType->name }}</span>
                                    </td>
                                    <td>
                                      <button class="btn btn-danger delete-btn-x" data-id="{{ $paymentType->id }}" data-model="payment-type"><i class="bi bi-x-lg"></i>
                                      </button>
                                    </td>
                                <tr>
                        @endforeach
                      </tbody>
                    </table>
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
        <h5 class="modal-title" id="exampleModalLabel">Novi način plaćanja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/nacin-placanja" id="paymentSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                  <label for="type_name">Način plaćanja:</label>
                  <input type="text" class="form-control" placeholder="Unesi naziv..." id="name" name="name">

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