@extends('layouts/admin')
@section('meta-title', 'Add Incomes - Assets Management Pro')
@section('page-title', $category->name . __(' Incomes Widget'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Incomes') }}</p>
        </div>
        <x-alert />
    </div>
    <x-scheme-create
        :name="__('Incomes')"
        :category="$category"
        :types="$types"
        :units="$units"
        :createRoute="route('categories.incomes.store', [$category->id, 'route' => $route])"
    />
@endsection
