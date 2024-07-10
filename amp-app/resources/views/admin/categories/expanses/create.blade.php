@extends('layouts/admin')
@section('meta-title', 'Add Expanses - Assets Management Pro')
@section('page-title', $category->name . __(' Expanses Widget'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Expanses') }}</p>
        </div>
        <x-alert />
    </div>
    <x-scheme-create
        :name="__('Expanses')"
        :category="$category"
        :types="$types"
        :units="$units"
        :createRoute="route('categories.expanses.store', [$category->id, 'route' => $route])"
    />
@endsection
