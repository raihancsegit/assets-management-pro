@extends('layouts/admin')
@section('meta-title', 'Production Reports - Assets Management Pro')
@section('page-title', __('Production Reports'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Last 30 days summary data.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ __('Report') }}</h4>
                        </div>

                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#milkProductionReport">
                                <i class="bx bx-filter-alt"></i>
                            </button>
                        </div>

                    </div>

                </div>

                <x-milk-report />
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Old stock (ltr)') }}</th>
                                <th>{{ __('Production (ltr)') }}</th>
                                <th>{{ __('Sell (ltr)') }}</th>
                                <th>{{ __('Sell price') }}</th>
                                <th>{{ __('Sell amount') }}</th>
                                <th>{{ __('Due sell (ltr)') }}</th>
                                <th>{{ __('Total stock (ltr)') }}</th>
                                <th>{{ __('Locations') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $stock = 0;
                            $production = 0;
                            $sell = 0;
                            $sell_amount = 0;
                            @endphp
                            @foreach ($reports as $r)
                                @php
                                $dueSell = $r->production <= $r->sell ? 0 : $r->production - $r->sell;
                                $newStock = (($r->production + $stock) - $r->sell);
                                $link = route('sells.index', ['date' => $r->date]);

                                @endphp
                                @if(is_numeric($r->production))
                                    @php
                                        $production += $r->production;
                                        $sell += $r->sell;
                                        $sell_amount += $r->sell_amount;
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{$r->date}}</td>
                                    <td>{{$stock}}</td>
                                    <td>
                                        <a href="{{route('productions.index', ['date' => $r->date])}}">
                                            {{$r->production}}
                                        </a>
                                    </td>
                                    <td>{{$r->sell}}</td>
                                    <td>{!!processValues($r->sell_price, ' sales...', $link)!!}</td>
                                    <td>{{$r->sell_amount}}</td>
                                    <td>{{$dueSell}}</td>
                                    <td>{{$newStock}}</td>
                                    <td>{!!processValues($r->location, ' locations...', $link)!!}</td>
                                </tr>
                                @php
                                $stock = (($r->production + $stock) - $r->sell);
                                @endphp

                            @endforeach

                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total : {{$production}}</td>
                                <td>Total : {{$sell}}</td>
                                <td></td>
                                <td>Total : {{$sell_amount}}</td>
                            </tr>
                        </table>
                        <table>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
