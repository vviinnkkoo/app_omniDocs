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
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($years as $year)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle text-right">
                    {{ $years->firstItem() + $loop->index }}
                  </td>

                  {{-- Year --}}
                  <td class="align-middle text-right">
                    <span class="editable" data-id="{{ $year->id }}" data-field="year" data-model="radne-godine">{{ $year->year }}</span>
                  </td>

                  {{-- Delete --}}
                  <td class="align-middle text-center px-4">
                    <x-delete-button :id="$year->id" model="radne-godine" />
                  </td>

                <tr>
              @endforeach
            </tbody>
          </table>

          <x-table-pagination :items="$years" />
            
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.work-years.includes.index.modals.add-work-year')
@include('includes.shared.modals.delete-confirmation')

@endsection