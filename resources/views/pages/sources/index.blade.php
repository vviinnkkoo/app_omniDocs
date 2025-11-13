@extends('layouts.app')

@section('title', 'Omnius Art | Kanali prodaje')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                  
                  <x-buttons.modal-button target="#addSourceModal" text="Novi kanal prodaje"/>
                  <x-search-form/>

                  <table class="table table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kanal prodaje</th>
                        <th class="delete-column"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sources as $source)
                        <tr>

                          {{-- Index number --}}
                          <td class="align-middle text-right">
                            {{ $sources->firstItem() + $loop->index }}
                          </td>

                          {{-- Source name --}}
                          <td class="align-middle text-right">
                            <span class="editable" data-id="{{ $source->id }}" data-field="name" data-model="kanali-prodaje">{{ $source->name }}</span>
                          </td>

                          {{-- Delete button --}}
                          <td class="align-middle text-center px-4">
                            <x-buttons.delete-button :id="$source->id" model="kanali-prodaje" />
                          </td>

                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  <x-table-pagination :items="$sources" />

                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.sources.includes.index.modals.add-source')
@include('includes.shared.modals.delete-confirmation')

@endsection