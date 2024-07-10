@extends('layouts/admin')
@section('meta-title', 'Dashboard - Assets Management Pro')
@section('page-title', 'Dashboard')
@section('content')
    <div class="row">
        <x-alert />
    </div>
    <div class="row">
        @if(!isManager())
            <div class="col-sm-6 col-xl-3">
                <x-card
                    :name="__('In Hand')"
                    :amount="$inHand"
                    :progress="''"
                    :icon="'bx-chart'"
                />
            </div>
        @endif
        @if(!isManager())
            <div class="col-sm-6 col-xl-3">
                <x-card
                    :name="__('messages.Deposits')"
                    :amount="$totalDeposits"
                    :progress="''"
                    :icon="'bx-store-alt'"
                />
            </div>
        @endif
        <div class="col-sm-6 col-xl-3">
            <x-card
                :name="__('messages.ExpansesName')"
                :amount="$totalExpanses"
                :progress="''"
                :icon="'bx-money'"
            />
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-card
                :name="__('messages.Incomes')"
                :amount="$totalIncomes"
                :progress="''"
                :icon="'bx-revision'"
            />
        </div>

    </div>

    @if(!empty($dailyOverview))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center card-header">
                        <h4 class="card-title">
                            {{ __('Last 7 days overview') }}
                        </h4>
                    </div>
                    <div class="card-body listing-card-body">
                        @foreach ($dailyOverview as $d)
                            <div class="listing-item">
                                <table class="listing-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="date-index">{{$d['date_name']}}</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($d['data'] as $i)
                                            <tr>
                                                <td class="category-cell">
                                                    {{$i->category_name}}
                                                    <span class="small-text">&rarr; {{ $i->type }}</span>
                                                    @if($i->status === 0)
                                                        <span class="small-text">&rarr; pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$i->details}}
                                                    <div class="small-text">
                                                        {{ !empty($i->receipt_no) ? '#'.$i->receipt_no : '' }}
                                                    </div>
                                                </td>
                                                <td class="amount-cell">
                                                    <a href="{{route('categories.' . $i->type . 's.show', [$i->category_id, $i->id, 'route' => 'dashboard'])}}">
                                                        {{ toCurrency($i->amount) }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center card-header">
                    <h4 class="card-title">
                        {{ __('Expanses') }}
                    </h4>
                    <div>
                        <a href="{{route('expanses.index')}}">{{ __('All Expanses') }}</a>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#commonFilterExpanse">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <x-scheme
                        :data="$expanses"
                        :route="$route"
                        :viewRoute="'expanses.show'"
                        :categoryId="0"
                    />
                </div>

                <x-common-filter
                    :categories="$categories"
                    :modal_title="'Expanse Filter'"
                    :custom_action="'expanseFilter'"
                    :route="'dashboard'"
                    :data_toggle="'commonFilterExpanse'"
                />
            </div>
        </div>
    </div>
    @if(!isManager())
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center card-header">
                        <h4 class="card-title">{{ __('Deposits') }}</h4>
                        <div>
                            <a href="{{route('deposits.index')}}">All Deposits</a>
                            <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#commonFilterDeposit">
                                <i class="bx bx-filter-alt"></i>
                            </button>
                        </div>

                    </div>
                    <div class="card-body">
                        <x-scheme
                            :data="$deposits"
                            :route="$route"
                            :viewRoute="'deposits.show'"
                            :categoryId="0"
                        />
                    </div>
                    <x-common-filter
                        :categories="$categories"
                        :modal_title="'Deposit Filter'"
                        :custom_action="'depositsFilter'"
                        :route="'dashboard'"
                        :data_toggle="'commonFilterDeposit'"
                    />
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center card-header">
                    <h4 class="card-title">{{ __('Incomes') }}</h4>
                    <div>
                        <a href="{{route('deposits.index')}}">All Incomes</a>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#commonFilterIncome">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                    </div>

                </div>
                <div class="card-body">
                    <x-scheme
                        :data="$incomes"
                        :route="$route"
                        :viewRoute="'incomes.show'"
                        :categoryId="0"
                    />
                </div>
                <x-common-filter
                    :categories="$categories"
                    :modal_title="'Income Filter'"
                    :custom_action="'incomeFilter'"
                    :route="'dashboard'"
                    :data_toggle="'commonFilterIncome'"
                />
            </div>
        </div>
    </div>
@endsection
