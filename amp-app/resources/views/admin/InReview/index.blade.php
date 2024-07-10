@extends('layouts/admin')
@section('meta-title', 'InReview - Assets Management Pro')
@section('page-title', 'InReview')
@section('content')
    <div class="row">
        <x-alert />
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ __('Expanses') }}</h4>
                        </div>

                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#inReviewExpanse">
                                <i class="bx bx-filter-alt"></i>
                            </button>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <x-review
                        :data="$expanses"
                        :route="$route"
                        :viewRoute="'expanses.show'"
                        :categoryId="0"
                    />
                </div>
                <x-inReviewFilter
                    :categories="$categories"
                    :modal_title="'Review Expanse Filter'"
                    :custom_action="'inreviewExpanseFilter'"
                    :data_toggle="'inReviewExpanse'"
                />
            </div>
        </div>
    </div>
    @if(!isManager())
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">{{ __('Income') }}</h4>
                            </div>

                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#inReviewIncome">
                                    <i class="bx bx-filter-alt"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <x-review
                            :data="$income"
                            :route="$route"
                            :viewRoute="'incomes.show'"
                            :categoryId="0"
                        />

                    </div>
                    <x-inReviewFilter
                        :categories="$categories"
                        :modal_title="'Review Income Filter'"
                        :custom_action="'inreviewIncomeFilter'"
                        :data_toggle="'inReviewIncome'"
                    />
                </div>
            </div>
        </div>
    @endif
@endsection
