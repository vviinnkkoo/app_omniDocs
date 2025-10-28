@extends('layouts.app')

@section('title', 'Omnius Art | Radne godine')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Nova radna godina</button>

          <x-search-form/>

            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Godina</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($workYears as $year)
                  <tr>
                    <td class="align-middle text-right">
                      {{ $workYears->firstItem() + $loop->index }}
                    </td>

                    <td class="align-middle text-right">
                      <span class="editable" data-id="{{ $year->id }}" data-field="year" data-model="radne-godine">{{ $year->year }}</span>
                    </td>

                    <td>
                      <x-delete-button :id="$color->id" model="radne-godine" />
                    </td>
                  <tr>
                @endforeach
              </tbody>
            </table>

            <x-table-pagination :items="$workYears" />
            
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
        <form method="POST" action="/radne-godine" id="sourceSubmission">
          {{ csrf_field() }}
          <div class="form-group">

              <label for="name">Radna godina:</label>
              <input type="text" class="form-control" placeholder="Unesi godinu, npr 2023, 2024, itd..." id="year" name="year">

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

@include('includes.shared.modals.delete-confirmation')

@endsection