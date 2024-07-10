@props([
    'name',
    'category',
    'types',
    'createRoute',
    'parentinventories'
])

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Create New '). $name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $createRoute }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 hidden_div">
                                    <label class="form-label" for="name">{{ __('Category') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="category_id">
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    </select>
                                    @error('category_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="serial">{{ __('Name') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="name" value="{{old('name')}}" id="serial" placeholder="{{ __('Name') }}">
                                    @error('name')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="type">{{ __('Type') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="inventorie_type">
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="color">{{ __('Color') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="color" value="{{old('color')}}" id="color" placeholder="{{ __('red') }}">
                                    @error('color')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="serial">{{ __('Serial No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="serial" value="{{old('serial')}}" id="serial" placeholder="{{ __('SEM35776') }}">
                                    @error('serial')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="details">{{ __('messages.Details') }}</label>
                                    <textarea class="form-control form-control-lg" name="details" id="details">{{old('details')}}</textarea>
                                    @error('details')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-4">
                                    <label class="form-label" for="parent_id">{{ __('Parent') }}</label>
                                    <select class="form-select" name="parent_id" id="parent_id">
                                        <option value="" selected>Select</option>
                                        @foreach($parentinventories as $parent)
                                            <option value="{{$parent->id}}">{{$parent->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="value_amount">{{ __('Value Amount') }}</label>
                                    <input class="form-control form-control-lg" type="number" name="value_amount" value="{{old('value_amount')}}" id="value_amount" placeholder="{{ __('1.50') }}">
                                    @error('value_amount')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="shade_no">{{ __('Shade No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="shade_no" value="{{old('shade_no')}}" id="shade_no">
                                    @error('shade_no')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="mt-4">
                            <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
