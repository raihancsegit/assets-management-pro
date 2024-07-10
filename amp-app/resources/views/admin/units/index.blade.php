@extends('layouts/admin')
@section('meta-title', 'All Units - Assets Management Pro')
@section('page-title', __('messages.All Units'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Add Units item.') }}</p>
        </div>
        <x-alert />
    </div>
    <x-attr
        :name="__('messages.Units')"
        :data="$units"
        :categories="$categories"
        :schemes="$schemes"
        :viewRoute="'units.show'"
        :storeRoute="route('units.store')"
    />
@endsection
