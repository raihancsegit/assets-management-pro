@extends('layouts/admin')
@section('meta-title', 'All Deposits - Assets Management Pro')
@section('page-title', __('messages.All Deposits'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Deposits represent your investment.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center card-header">
                    <div>
                        <h4 class="card-title">{{ __('messages.Deposits') }}</h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#commonFilterModal">
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
                    :custom_action="'singleDepositFilter'"
                    :route="'deposits.index'"
                    :data_toggle="'commonFilterModal'"
                />
            </div>
        </div>
    </div>
@endsection
