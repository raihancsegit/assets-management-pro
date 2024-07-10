@extends('layouts/admin')
@section('meta-title', 'All Inventory - Assets Management Pro')
@section('page-title', 'All Inventory')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Inventory is a sum of money that you gain.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Inventory') }}</h4>
                </div>
                <div class="card-body">
                    <x-inventory
                        :data="$inventories"
                        :route="$route"
                        :viewRoute="'inventories.show'"
                        :categoryId="0"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
