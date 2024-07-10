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
    <x-scheme-edit
        :data="$deposit"
        :name="__('messages.Deposit')"
        :categories="$categories"
        :types="$types"
        :units="$units"
        :deleteRoute="route('deposits.destroy', [$deposit->id, 'route' => $route])"
        :updateRoute="route('deposits.update', [$deposit->id, 'route' => $route])"
    />
@endsection
