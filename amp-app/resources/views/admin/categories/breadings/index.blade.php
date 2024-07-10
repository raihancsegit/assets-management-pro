@extends('layouts/admin')
@section('meta-title', 'All Breading - Assets Management Pro')
@section('page-title', 'Breading of ' . $category->name)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Breadings is a sum of money that you gain.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        @if($category->details)
            <div class="alert alert-light" role="alert">
                {{ $category->details }}
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        @php
                            $icon = $category->icon ? $category->icon : 'fa-th-large';
                        @endphp
                        <i class="fas fa-home {{$icon}} nav-icon"></i> &nbsp;
                        <a href="{{ route('categories.show', $category->id) }} ">
                            {{ $category->name }}
                        </a>
                        <span class="text-muted font-size-12 mb-0"><i>{{toCurrency($inventories->sum('value_amount'), true) }}</i></span>
                    </h4>
                </div>
                <div class="card-body">
                    <x-breading
                        :data="$inventories"
                        :route="$route"
                        :viewRoute="'categories.inventories.show'"
                        :categoryId="$category->id"
                    />
                </div>
                <x-full-screen-filter
                    :name="'Breading'"
                    :route="$route"
                    :categoryId="$category->id"
                />
            </div>
        </div>
    </div>
@endsection
