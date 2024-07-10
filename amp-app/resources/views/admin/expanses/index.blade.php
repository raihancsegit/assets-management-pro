@extends('layouts/admin')
@section('meta-title', 'All Expanses - Assets Management Pro')
@section('page-title', 'All Expanses')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Expanses is a sum of money that are your liabilities.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center card-header">
                    <div>
                        <h4 class="card-title">{{ __('Expanses') }}</h4>
                    </div>

                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#commonFilterModal">
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
                    :custom_action="'singleExpanseFilter'"
                    :route="'expanses.index'"
                    :data_toggle="'commonFilterModal'"
                />
            </div>
        </div>
    </div>
@endsection
