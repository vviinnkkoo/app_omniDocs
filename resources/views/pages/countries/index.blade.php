@extends('layouts.app')

@section('title', 'Omnius Art | Države poslovanja')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          <x-buttons.open-modal target="#addCountryModal" text="Nova država"/>
          <x-misc.search-form/>

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Naziv države</th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($countries as $country)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle text-right">
                    {{ $countries->firstItem() + $loop->index }}
                  </td>

                  {{-- Country name --}}
                  <td class="align-middle text-right">
                    <x-editable.text :model="$country" field="name" modelName="drzave-poslovanja" :value="$country->name" simple="true"/>
                  </td>

                  {{-- Delete button --}}
                  <td class="align-middle text-center px-4">
                    <x-buttons.delete-item :id="$country->id" model="drzave-poslovanja" />
                  </td>

                </tr>
              @endforeach
            </tbody>
          </table>

          <x-misc.table-pagination :items="$countries" />

        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.countries.includes.index.modals.add-country')
@include('includes.shared.modals.delete-confirmation')

@endsection