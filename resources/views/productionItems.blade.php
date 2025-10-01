@extends('layouts.app')

@section('title', 'Omnius Art | Prozvodi za izradu')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        <div class="col-xl-12">          
            <div class="card">                

                <div class="card-body"> 
                  
                  <span class="fs-3">{{ $title }}</span>

                  @include('includes.tablesearch')

                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Proizvod</th>
                          <th scope="col">Boja</th>
                          <th scope="col">Količina</th>
                          <th scope="col">Opis</th>
                          <th scope="col">Status izrade</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach ($items as $item)

                              <tr>
                                
                                    <td class="align-middle text-right">
                                      @if (isset($item->id))
                                          {{ $item->id }}
                                      @else
                                          {{ $count++ }}
                                      @endif
                                    </td>

                                    <td class="align-middle text-right">{{ $item->productName }}</td>

                                    <td class="align-middle text-right">
                                        @if (isset($item->color_id))
                                          {{ $item->colorName }}
                                        @else
                                          - - -
                                        @endif
                                    </td>

                                    <td class="align-middle text-right">
                                        @if ($item->unit == 'kom')
                                            {{ $item->formattedAmount }} {{ $item->unit }}
                                        @else
                                            {{ $item->amount }} {{ $item->unit }}
                                        @endif                                      
                                    </td>

                                    <td class="align-middle text-right">
                                        @if (isset($item->id))                                      
                                            {{ $item->note }}
                                        @else
                                            - - -
                                        @endif
                                    </td>

                                    <td class="align-middle text-right">
                                        @if (isset($item->id))
                                            <div class="form-check form-switch order-item" data-id="{{ $item->id }}" data-model="order-item-list">
                                              <input class="form-check-input edit-checkbox" type="checkbox" name="is_done" id="flexSwitchCheckDefault" {{ $item->is_done ? 'checked' : '' }}>
                                            </div>
                                        @else
                                            - - -
                                        @endif
                                    </td>

                                    <td class="align-middle text-right">
                                        @if (isset($item->id))                                   
                                            <a href="/narudzbe/{{ $item->order_id }}" class="btn btn-sm btn-primary">Narudžba {{ $item->order_id }} <i class="bi bi-arrow-right-circle-fill"></i></a>
                                        @endif
                                    </td>
                                    
                              </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.deleteconfirmation')

@endsection