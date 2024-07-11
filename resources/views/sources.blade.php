@extends('layouts.app')

@section('title', 'Omnius Art | Kanali prodaje')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">

                {{-- <div class="card-header">{{ __('Dostavne službe') }}</div> --}}

                <div class="card-body">

                  <!-- Button to trigger the pop-up -->
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi kanal prodaje</button>

                  @include('parts.tablesearch')

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Kanal prodaje</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @php ($count = 1)
                        @foreach ($sources as $source)
                                <tr>
                                    <td class="align-middle text-right">{{ $count++ }}</td>
                                    <td class="align-middle text-right">
                                      <span class="editable" data-id="{{ $source->id }}" data-field="name" data-model="source">{{ $source->name }}</span>
                                    </td>
                                    <td>
                                      <button class="btn btn-danger delete-btn-x" data-id="{{ $source->id }}" data-model="source"><i class="bi bi-x-lg"></i>
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
        <h5 class="modal-title" id="exampleModalLabel">Novi kanal prodaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- popup content -->
        <form method="POST" action="/kanali-prodaje" id="sourceSubmission">
          {{ csrf_field() }}
              <div class="form-group">

                  <label for="name">Kanal prodaje:</label>
                  <input type="text" class="form-control" placeholder="Unesi kanal prodaje..." id="name" name="name">

              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        <button type="submit" class="btn btn-primary" form="sourceSubmission">Spremi</button>
      </div>
    </div>
  </div>
</div>

@include('parts.deleteconfirmation')

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>