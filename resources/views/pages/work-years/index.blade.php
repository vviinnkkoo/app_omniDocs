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

@include('pages.work-years.includes.index.modals.add-work-year')
@include('includes.shared.modals.delete-confirmation')

@endsection