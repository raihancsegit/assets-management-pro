@extends('layouts/admin')
@section('meta-title', 'All Expanses - Assets Management Pro')
@section('page-title', 'Expanses of ' . $category->name)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Expanses is a sum of money that are your liabilities.') }}</p>
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
                        <span class="text-muted font-size-12 mb-0"><i>{{ toCurrency($expanses->sum('amount'), true) }}</i></span>
                    </h4>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('categories.expanses.create', [$category->id, 'route' => $route]) }} ">
                            <button type="button" class="btn btn-outline-primary btn-sm">{{ __('Add Expanse') }}</button>
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#fullscreenFilterModal">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <x-scheme
                        :data="$expanses"
                        :route="$route"
                        :viewRoute="'categories.expanses.show'"
                        :categoryId="$category->id"
                    />
                </div>
                <x-full-screen-filter
                    :name="'Expanse'"
                    :route="$route"
                    :categoryId="$category->id"
                />
            </div>
        </div>
    </div>
@endsection
