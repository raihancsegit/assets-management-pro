@extends('layouts/admin')
@section('meta-title', 'Add Deposit - Assets Management Pro')
@section('page-title', $category->name . __('messages.Deposit Widget'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Deposit') }}</p>
        </div>
        <x-alert />
    </div>
    <x-deposit-create
        :name="__('messages.Deposit')"
        :category="$category"
        :types="$types"
        :units="$units"
        :createRoute="route('categories.deposits.store', [$category->id, 'route' => $route])"
        :managers="$managers"
    />
@endsection
