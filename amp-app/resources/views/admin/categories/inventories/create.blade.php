@extends('layouts/admin')
@section('meta-title', 'Add Inventory - Assets Management Pro')
@section('page-title', $category->name . __(' Inventory Widget'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Inventory') }}</p>
        </div>
        <x-alert />
    </div>
    <x-inventory-create
        :name="__('Inventory')"
        :category="$category"
        :types="$types"
        :parentinventories="$parent_inventories"
        :createRoute="route('categories.inventories.store', [$category->id, 'route' => $route])"
    />
@endsection
