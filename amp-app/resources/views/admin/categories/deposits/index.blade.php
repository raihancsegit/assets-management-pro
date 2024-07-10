@extends('layouts/admin')
@section('meta-title', 'All Deposits - Assets Management Pro')
@section('page-title', __('messages.Deposits of ') . $category->name)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Deposits is a sum of money that you share for investment.') }}</p>
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
                        <span class="text-muted font-size-12 mb-0"><i>{{ toCurrency($deposits->sum('amount'), true) }}</i></span>
                    </h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('categories.deposits.create', [$category->id, 'route' => $route]) }} ">
                            <button type="button" class="btn btn-outline-primary btn-sm">{{ __('messages.Add Deposit') }}</button>
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#fullscreenFilterModal">
                            <i class="bx bx-filter-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <x-scheme
                        :data="$deposits"
                        :route="$route"
                        :viewRoute="'categories.deposits.show'"
                        :categoryId="$category->id"
                    />
                </div>
                <x-full-screen-filter
                    :name="'Deposit'"
                    :route="$route"
                    :categoryId="$category->id"
                />
            </div>
        </div>
    </div>
@endsection