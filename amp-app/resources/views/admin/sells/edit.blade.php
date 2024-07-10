@extends('layouts/admin')
@section('meta-title', 'Update Sell - Assets Management Pro')
@section('page-title', __('Update Sell'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Sell') }} - {{ $sell->name }}</p>
        </div>
        <x-alert />
    </div>
    <x-milk-sell-edit
        :name="__('Sell')"
        :data="$sell"
        :locations="$locations"
        :updateRoute="route('sells.update', $sell->id)"
        :deleteRoute="route('sells.destroy', $sell->id)"
    />
@endsection
