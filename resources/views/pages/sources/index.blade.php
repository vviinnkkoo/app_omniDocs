@extends('layouts.app')

@section('title', 'Omnius Art | Kanali prodaje')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                  <button id="popupButton" class="btn btn-primary" style="margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-file-earmark-plus"></i> Novi kanal prodaje</button>

                  <x-search-form/>

                  <table class="table table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kanal prodaje</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sources as $source)
                        <tr>
                          <td class="align-middle text-right">
                            {{ $sources->firstItem() + $loop->index }}
                          </td>

                          <td class="align-middle text-right">
                            <span class="editable" data-id="{{ $source->id }}" data-field="name" data-model="kanali-prodaje">{{ $source->name }}</span>
                          </td>

                          <td>
                            <x-delete-button :id="$source->id" model="kanali-prodaje" />
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

@include('pages.sources.includes.index.modals.add-source')
@include('includes.shared.modals.delete-confirmation')

@endsection