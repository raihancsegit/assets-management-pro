@extends('layouts/admin')
@section('meta-title', 'All Types - Assets Management Pro')
@section('page-title', __('messages.All Types'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Types represent you assets item.') }}</p>
        </div>
        <x-alert />
    </div>
    <x-attr
        :name="__('messages.Types')"
        :data="$types"
        :categories="$categories"
        :schemes="$schemes"
        :viewRoute="'types.show'"
        :storeRoute="route('types.store')"
    />
@endsection
