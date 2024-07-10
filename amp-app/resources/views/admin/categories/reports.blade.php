@extends('layouts/admin')
@section('meta-title', 'Category Report - Assets Management Pro')
@section('page-title', __('Category Report'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Category report of ' . $category->name) }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Summary') }}</h4>
                </div>
                <div class="card-body">
                    @if($summary)
                        <div class="table-responsive category-table">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Deposits') }}</th>
                                        <th>{{ __('Expanses') }}</th>
                                        <th>{{ __('Incomes') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($summary as $s)
                                        <tr>
                                            <th>{{ $s->totalDeposits }}</th>
                                            <th>{{ $s->totalExpanses }}</th>
                                            <th>{{ $s->totalIncomes }}</th>
                                            <th>{{ $s->totalBalances }}</th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center card-header">
                    <div class="cardTitle">
                        <h4 class="card-title">{{ __('Date wise report') }}</h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#fullscreenFilterModal">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                        <a target="_blank" href="{{ route('categories.reports-download.index', array_merge(['category' => $category], request()->query())) }}">
                            <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px;">
                                <i class="bx bx-download"></i>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($dateWiseData->count())
                        <div class="table-responsive category-table">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Deposits') }}</th>
                                        <th>{{ __('Expanses') }}</th>
                                        <th>{{ __('Incomes') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dateWiseData as $data)
                                        @php
                                            $balance = ($data->deposits - $data->expanses) + $data->incomes;
                                        @endphp
                                        <tr>
                                            <td>{{dateFormat($data->date,'d M, Y')}}</td>
                                            <td>
                                                <a href="{{route('categories.deposits.index', [
                                                    'category' => $category->id,
                                                    'start_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'end_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'status' => 1
                                                    ])}}">
                                                    {{$data->deposits}}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{route('categories.expanses.index', [
                                                    'category' => $category->id,
                                                    'start_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'end_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'status' => 1
                                                    ])}}">
                                                    {{$data->expanses}}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{route('categories.incomes.index', [
                                                    'category' => $category->id,
                                                    'start_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'end_date' => dateFormat($data->date,'Y-m-d H:i:s'),
                                                    'status' => 1
                                                    ])}}">
                                                    {{$data->incomes}}
                                                </a>
                                            </td>
                                            <td>{{$balance}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-container mt-4 mb-0">
                                {{ $dateWiseData->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                    @endif
                </div>

                <x-date-filter
                  :categoryId="$category->id"
                />
            </div>
        </div>
    </div>
@endsection

