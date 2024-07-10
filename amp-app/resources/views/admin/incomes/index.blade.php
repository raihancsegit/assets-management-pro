@extends('layouts/admin')
@section('meta-title', 'All Incomes - Assets Management Pro')
@section('page-title', 'All Incomes')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Incomes is a sum of money that you gain.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Incomes') }}</h4>
                </div>
                <div class="card-body">
                    <x-scheme
                        :data="$incomes"
                        :route="$route"
                        :viewRoute="'incomes.show'"
                        :categoryId="0"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
