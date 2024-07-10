@extends('layouts/admin')
@section('meta-title', 'Update Unit - Assets Management Pro')
@section('page-title', __('messages.Update Unit'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('messages.Unit') }} - {{ $unit->name }}</p>
        </div>
        <x-alert />
    </div>
    <x-attr-edit
        :name="__('messages.Unit')"
        :data="$unit"
        :categories="$categories"
        :schemes="$schemes"
        :updateRoute="route('units.update', $unit->id)"
        :deleteRoute="route('units.destroy', $unit->id)"
    />
@endsection
