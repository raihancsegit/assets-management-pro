@extends('layouts/admin')
@section('meta-title', 'Update Production - Assets Management Pro')
@section('page-title', __('Update Production'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Production') }} - {{ $production->name }}</p>
        </div>
        <x-alert />
    </div>
    <x-milk-production-edit
        :name="__('Production')"
        :data="$production"
        :locations="$locations"
        :updateRoute="route('productions.update', $production->id)"
        :deleteRoute="route('productions.destroy', $production->id)"
    />
@endsection
