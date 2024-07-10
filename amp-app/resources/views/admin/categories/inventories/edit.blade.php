@extends('layouts/admin')
@section('meta-title', 'Update Inventory - Assets Management Pro')
@section('page-title', 'Update Inventory')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Inventory') }} - #{{ $inventory->id }}</p>
        </div>
        <x-alert />
    </div>
    <x-inventory-edit
        :data="$inventory"
        :name="__('Inventory')"
        :categories="$categories"
        :types="$types"
        :parentinventories="$parent_inventories"
        :deleteRoute="route('categories.inventories.destroy', [$category->id, $inventory->id, 'route' => $route])"
        :updateRoute="route('categories.inventories.update', [$category->id, $inventory->id, 'route' => $route])"
    />
@endsection
