@extends('layouts/admin')
@section('meta-title', 'All Production - Assets Management Pro')
@section('page-title', __('All Production'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Production represent you assets item.') }}</p>
        </div>
        <x-alert />
    </div>
    <x-milk-production
        :name="__('Production')"
        :data="$productions"
        :locations="$locations"
        :viewRoute="'productions.show'"
        :storeRoute="route('productions.store')"
    />
@endsection
