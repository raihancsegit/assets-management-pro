@extends('layouts/admin')
@section('meta-title', 'Update Type - Assets Management Pro')
@section('page-title', __('messages.Update Type'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Type') }} - {{ $type->name }}</p>
        </div>
        <x-alert />
    </div>
    <x-attr-edit
        :name="__('messages.Type')"
        :data="$type"
        :categories="$categories"
        :schemes="$schemes"
        :updateRoute="route('types.update', $type->id)"
        :deleteRoute="route('types.destroy', $type->id)"
    />
@endsection
