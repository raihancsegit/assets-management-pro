@extends('layouts/admin')
@section('meta-title', 'All Categories - Assets Management Pro')
@section('page-title', __('messages.All Categories'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Categories represent your assets item.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Categories') }}</h4>
                </div>
                <div class="card-body">
                    @if($parent_categories->count())
                        <div class="table-responsive category-table">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.Name') }}</th>
                                        <th>{{ __('messages.Sub Category') }}</th>
                                        <th>{{ __('messages.Deposits') }}</th>
                                        <th>{{ __('messages.Expanses') }}</th>
                                        <th>{{ __('messages.Incomes') }}</th>
                                        <th>{{ __('messages.Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parent_categories as $key => $category)
                                        @php
                                            $route = auth()->user()->hasRole('staff|admin') ? route('categories.show', $category->id) : '#';
                                            $hasChildren = $category->children && $category->children->count() > 0;
                                            $categoryClass = $hasChildren ? 'subcategory' : 'cat';
                                            $subcategories = [];
                                            if ($hasChildren) {
                                                $subcategories = $category->children()
                                                    ->with([
                                                        'deposits' => function ($q) {
                                                            $q->where('status', 1);
                                                        },
                                                        'expanses' => function ($q) {
                                                            $q->where('status', 1);
                                                        },
                                                        'incomes' => function ($q) {
                                                            $q->where('status', 1);
                                                        }
                                                    ])->get();
                                            }
                                        @endphp
                                        <tr>
                                            <th scope="row">{{++$key}}</th>
                                            <td class="cateName">
                                                 <a href="{{$route}}">{{ $category->name }}</a>
                                            </td>
                                            <td class="{{$categoryClass}}">
                                                @if ($hasChildren)
                                                    <ul class="child-category">
                                                        @foreach ($category->children as $subcategory)
                                                            @php
                                                                $childroute = auth()->user()->hasRole('staff|admin') ? route('categories.show', $subcategory->id) : '#';
                                                            @endphp
                                                            <li><a href="{{$childroute}}">{{ $subcategory->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>

                                            <td class="{{$categoryClass}}">
                                                @if ($hasChildren)
                                                    <ul class="child-category">
                                                        @php $sum = 0; @endphp
                                                        @foreach ($subcategories as $subcategory)
                                                            @php
                                                                $childroute = auth()->user()->hasRole('staff|admin') ? route('categories.show', $subcategory->id) : '#';
                                                                $sum += $subcategory->deposits->sum('amount');
                                                            @endphp
                                                            <li>{{toCurrency($subcategory->deposits->sum('amount'))}}</li>
                                                        @endforeach

                                                        <li class="total" >{{toCurrency($sum)}}</li>
                                                    </ul>
                                                @else
                                                    {{toCurrency($category->deposits->sum('amount'))}}
                                                @endif
                                            </td>
                                            <td class="{{$categoryClass}}">
                                                @if ($hasChildren)
                                                    <ul class="child-category">
                                                        @php $sum = 0; @endphp
                                                        @foreach ($subcategories as $subcategory)
                                                            @php
                                                                $childroute = auth()->user()->hasRole('staff|admin') ? route('categories.show', $subcategory->id) : '#';
                                                                $sum += $subcategory->expanses->sum('amount');
                                                            @endphp
                                                            <li>{{toCurrency($subcategory->expanses->sum('amount'))}}</li>
                                                        @endforeach
                                                        <li class="total">{{toCurrency($sum)}}</li>
                                                    </ul>
                                                @else
                                                    {{toCurrency($category->expanses->sum('amount'))}}
                                                @endif
                                            </td>

                                            <td class="{{$categoryClass}}">
                                                @if ($hasChildren)
                                                <ul class="child-category">
                                                    @php $sum = 0; @endphp
                                                    @foreach ($subcategories as $subcategory)
                                                        @php
                                                            $childroute = auth()->user()->hasRole('staff|admin') ? route('categories.show', $subcategory->id) : '#';
                                                            $sum += $subcategory->incomes->sum('amount');
                                                        @endphp
                                                        <li>{{toCurrency($subcategory->incomes->sum('amount'))}}</li>
                                                    @endforeach
                                                    <li class="total">{{toCurrency($sum)}}</li>
                                                </ul>
                                                @else
                                                    {{toCurrency($category->incomes->sum('amount'))}}
                                                @endif

                                            </td>

                                            <td class="{{$categoryClass}}">
                                                @if ($hasChildren)
                                                <ul class="child-category">
                                                    @php $totalBalance = 0; @endphp
                                                    @foreach ($subcategories as $subcategory)
                                                        @php
                                                            $childroute = auth()->user()->hasRole('staff|admin') ? route('categories.show', $subcategory->id) : '#';
                                                            $subcategoryBalance = ($subcategory->deposits->sum('amount') + $subcategory->incomes->sum('amount')) - $subcategory->expanses->sum('amount');
                                                            $totalBalance += $subcategoryBalance;
                                                        @endphp
                                                        <li>
                                                            <span>{{ toCurrency($subcategoryBalance) }}</span>
                                                        </li>
                                                    @endforeach
                                                    <li class="total">{{ toCurrency($totalBalance) }}</li>
                                                </ul>
                                            @else
                                                {{ toCurrency($category->deposits->sum('amount') + $category->incomes->sum('amount') - $category->expanses->sum('amount')) }}

                                            @endif

                                            </td>



                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-container mt-4 mb-0">
                                {{ $parent_categories->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @hasanyrole('staff|admin')
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('messages.Add New Category') }}</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label" for="cat_name">{{ __('messages.Category Name') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="name" value="{{old('name')}}" id="cat_name" placeholder="{{ __('messages.Agriculture') }}" required>
                                    @error('name')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="form-label" for="cat_details">{{ __('messages.Details') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="details" value="{{old('details')}}" id="cat_details" placeholder="{{ __('messages.Notes') }}">
                                    @error('details')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label class="form-label" for="parent_id">{{ __('Parent') }}</label>
                                    <select class="form-select" name="parent_id" id="parent_id">
                                        <option value="" selected>Select</option>
                                        @foreach($parent_categories as $parent)
                                            <option value="{{$parent->id}}">{{$parent->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Add Category') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endhasanyrole
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Summary') }}</h4>
                    </div>
                    <div class="card-body">
                        @if(
                            $total_deposits
                            || $total_expanses
                            || $total_incomes
                        )
                            <div id="donut-charts" data-colors='["#000","#28b765", "#57c9eb"]'></div>
                        @else
                            <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $series = [
        ['name' => 'Deposits', 'data' => $total_deposits],
        ['name' => 'Expanses', 'data' => $total_expanses],
        ['name' => 'Incomes', 'data' => $total_incomes]
    ];
@endphp

<x-donut-chart :series="json_encode($series)" />

