@extends('layouts/admin')
@section('meta-title', 'Update Deposit - Assets Management Pro')
@section('page-title', __('messages.Update Deposit'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Deposit') }} - #{{ $deposit->id }}</p>
        </div>
        <x-alert />
    </div>
    <x-deposit-edit
        :data="$deposit"
        :name="__('messages.Deposit')"
        :categories="$categories"
        :types="$types"
        :units="$units"
        :deleteRoute="route('categories.deposits.destroy', [$category->id, $deposit->id, 'route' => $route])"
        :updateRoute="route('categories.deposits.update', [$category->id, $deposit->id, 'route' => $route])"
        :managers="$managers"
        :deposit="$depositId"
        :join="$depositId"
    />
@endsection
