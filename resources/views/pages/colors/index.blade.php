@extends('layouts.app')

@section('title', 'Omnius Art | Boje / Opisi proizvoda')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-12">          
      <div class="card">
        <div class="card-body">
          <x-buttons.open-modal target="#addColorModal" text="Dodaj opis/boju"/>
          <x-misc.search-form/>

          <table class="table table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Boja / Opis proizvoda</th>
                <th class="delete-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($colors as $color)
                <tr>

                  {{-- Index --}}
                  <td class="align-middle">
                    {{ $colors->firstItem() + $loop->index }}
                  </td>

                  {{-- Color name --}}
                  <td class="align-middle">
                    <x-editable.text :model="$color" field="name" modelName="opis" :value="$color->name" simple="true"/>
                  </td>

                  {{-- Delete button --}}
                  <td class="align-middle text-center px-4">
                    <x-buttons.delete-item :id="$color->id" model="opis" />
                  </td>
                  
                </tr>
              @endforeach
            </tbody>
          </table>

          <x-misc.table-pagination :items="$colors" />
          
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.colors.includes.index.modals.add-color')
@include('includes.shared.modals.delete-confirmation')

@endsection