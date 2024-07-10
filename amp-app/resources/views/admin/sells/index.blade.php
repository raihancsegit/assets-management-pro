@extends('layouts/admin')
@section('meta-title', 'All Sells - Assets Management Pro')
@section('page-title', __('All Sells'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Sells represent you assets item.') }}</p>

        </div>
        <x-alert />
    </div>
    <x-milk-sell
        :name="__('Sells')"
        :data="$sells"
        :locations="$locations"
        :viewRoute="'sells.show'"
        :storeRoute="route('sells.store')"
    />
@endsection
