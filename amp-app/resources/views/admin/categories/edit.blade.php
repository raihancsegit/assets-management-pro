@extends('layouts/admin')
@section('meta-title', 'Update Category - Assets Management Pro')
@section('page-title', __('messages.Update Category'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Category') }} - {{ $category->name }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Update Category') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.update', $category->id) }}">
                            @csrf
                            @method('put')
                            <input type="hidden" name="_id" value="{{ $category->id }}">
                            <div class="mb-4">
                                <label class="form-label" for="cat_name">{{ __('messages.Category Name') }}</label>
                                <input class="form-control form-control-lg" type="text" name="name" value="{{old('name', $category->name)}}" id="cat_name" placeholder="{{ __('messages.Agriculture') }}" required>
                                @error('name')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="cat_details">{{ __('messages.Details') }}</label>
                                <input class="form-control form-control-lg" type="text" name="details" value="{{old('details', $category->details)}}" id="cat_details" placeholder="{{ __('messages.Notes') }}">
                                @error('details')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                                <div class="mt-4">
                                    <label class="form-label" for="parent_id">{{ __('Parent') }}</label>
                                    <select class="form-select" name="parent_id" id="parent_id">
                                        <option value="">Select</option>
                                            @foreach($parent_categories as $parent)
                                                    <option {{ $subcategory->parent_id == $parent->id ? 'selected' : '' }}  value="{{$parent->id}}">{{$parent->name}}</option>
                                            @endforeach
                                    </select>
                                    @error('parent_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="form-label" for="has_inventory">{{ __('Inventory') }}</label>
                                    <select id="has_inventory" class="form-select" name="has_inventory">
                                        <option @if($category->has_inventory == old('has_inventory', 1) ) selected @endif value="1">Yes</option>
                                        <option @if($category->has_inventory == old('has_inventory', 0) ) selected @endif value="0">No</option>
                                    </select>
                                    @error('has_inventory')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>

                            <div class="mt-4">
                                <label class="form-label" for="icon">{{ __('messages.FontAwesome Icon Name') }}</label>
                                <input class="form-control form-control-lg" type="text" name="icon" value="{{old('icon', $category->icon)}}" id="icon" placeholder="{{ __('messages.home') }}">
                                @error('icon')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Update Category') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ __('messages.Quick Deposit') }}</h4>
                        <a href="{{route('categories.deposits.index', $category)}}">All Deposits</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('deposits.store') }}">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <input type="hidden" name="category_page" value="true">
                            <div class="mb-4">
                                <label class="form-label" for="date">{{ __('Date') }}</label>
                                <input class="form-control form-control-lg" type="date" name="date" value="{{old('date', $category->date)}}" id="date" required>
                                @error('date')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="amount">{{ __('messages.Amount') }}</label>
                                <input class="form-control form-control-lg" type="text" name="amount" value="{{old('amount')}}" id="amount" placeholder="{{ __('messages.Enter amount') }}" required>
                                @error('amount')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="notes">{{ __('messages.Notes') }}</label>
                                <input class="form-control form-control-lg" type="text" name="notes" value="{{old('notes')}}" id="notes" placeholder="{{ __('messages.Notes') }}">
                                @error('notes')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Add Deposit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Category Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ $category->name }}</p>
                        <p>{{ $category->details }}</p>
                        @if($category->types->count())
                            <p class="mb-0">{{ __('messages.Variations') }}:</p>
                            @foreach($category->types as $type)
                                <span>
                                    <span class="badge badge-pill bg-primary-subtle text-primary font-size-12">
                                        {{ $type->name }}
                                    </span>
                                </span>
                            @endforeach
                        @endif

                        @if($category->units->count())
                            <p class="mt-4 mb-0">{{ __('messages.Units') }}:</p>
                            @foreach($category->units as $unit)
                                <span>
                                    <span class="badge badge-pill bg-success-subtle text-success font-size-12">
                                        {{ $unit->name }}
                                    </span>
                                </span>
                            @endforeach
                        @endif

                        <p class="text-muted font-size-13 mt-4 mb-0"><i>{{ __('messages.Last updated ') }}{{ $category->updated_at->diffForHumans() }}</i></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Summary') }}</h4>
                    </div>
                    <div class="card-body">
                        @if(
                            $category->deposits->count()
                            || $category->expanses->count()
                            || $category->incomes->count()
                        )
                            <div id="donut-charts" data-colors='["#000","#28b765", "#57c9eb"]'></div>
                        @else
                            <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Delete Category') }}</h4>
                        <p class="card-title-desc">
                            <mark>{{ __('Item can be deletable if there is no relation between deposits/expanses/incomes') }}</mark>
                        </p>
                    </div>
                    <div class="card-body">
                        @if(
                            $category->deposits->count()
                            || $category->expanses->count()
                            || $category->incomes->count()
                        )
                            <div class="alert alert-warning alert-dismissible alert-outline fade show" role="alert">
                                <strong>{{ __('Warning') }}</strong> - {{ $category->name }} {{ __('has relation with deposits/expanses/incomes') }}
                            </div>
                        @else
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                @csrf

                                @method('DELETE')

                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?');">
                                    <i class="bx bx-block font-size-16 align-middle me-2"></i> {{ __('messages.Delete Category') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $series = [
        ['name' => 'Deposits', 'data' => $category->deposits->sum('amount')],
        ['name' => 'Expanses', 'data' => $category->expanses->sum('amount')],
        ['name' => 'Incomes', 'data' => $category->incomes->sum('amount')]
    ];
@endphp

<x-donut-chart :series="json_encode($series)" />
