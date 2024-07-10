@extends('layouts/admin')
@section('meta-title', 'Update Expanse - Assets Management Pro')
@section('page-title', 'Update Expanse')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Expanse') }} - #{{ $income->id }}</p>
        </div>
        <x-alert />
    </div>
    <x-scheme-edit
        :data="$income"
        :name="__('Expanse')"
        :categories="$categories"
        :types="$types"
        :units="$units"
        :deleteRoute="route('incomes.destroy', [$income->id, 'route' => $route])"
        :updateRoute="route('incomes.update', [$income->id, 'route' => $route])"
    />
@endsection
