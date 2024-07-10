@extends('layouts/admin')
@section('meta-title', 'Update Expanse - Assets Management Pro')
@section('page-title', 'Update Expanse')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Expanse') }} - #{{ $expanse->id }}</p>
        </div>
        <x-alert />
    </div>
    <x-scheme-edit
        :data="$expanse"
        :name="__('Expanse')"
        :categories="$categories"
        :types="$types"
        :units="$units"
        :deleteRoute="route('expanses.destroy', [$expanse->id, 'route' => $route])"
        :updateRoute="route('expanses.update', [$expanse->id, 'route' => $route])"
    />
@endsection
