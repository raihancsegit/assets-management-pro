@props([
    'data',
    'name',
    'category',
    'categories',
    'updateRoute',
    'deleteRoute',
    'types',
    'parentinventories'
])

<div class="row">
    <div class="col-lg-8">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Create New '). $name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $updateRoute }}">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 hidden_div">
                                    <label class="form-label" for="name">{{ __('Category') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="category_id">
                                        <option value="{{ $data->category->id }}">{{ $data->category->name }}</option>
                                    </select>
                                    @error('category_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="serial">{{ __('Name') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="name" value="{{old('name',$data->name)}}" id="serial" placeholder="{{ __('Name') }}">
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
                                            <option @if($type->id == old('inventorie_type', $data->inventorie_type) ) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('inventory_type')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="color">{{ __('Color') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="color" value="{{old('color',$data->color)}}" id="color" placeholder="{{ __('red') }}">
                                    @error('color')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="serial">{{ __('Serial No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="serial" value="{{old('serial',$data->serial)}}" id="serial" placeholder="{{ __('SEM35776') }}">
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
                                    <textarea class="form-control form-control-lg" name="details" id="details">{{old('details',$data->details)}}</textarea>
                                    @error('details')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="parent_id">{{ __('Parent') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="parent_id">
                                        <option value="">Select</option>
                                        @foreach($parentinventories as $parent)
                                            <option @if($parent->id == old('parent_id', $data->parent_id) ) selected @endif value="{{ $parent->id }}">{{ $parent->name }}</option>
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
                                    <input class="form-control form-control-lg" type="number" name="value_amount" value="{{old('value_amount',$data->value_amount)}}" id="value_amount" placeholder="{{ __('1.50') }}">
                                    @error('value_amount')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="shade_no">{{ __('Shade No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="shade_no" value="{{old('shade_no',$data->shade_no)}}" id="shade_no">
                                    @error('shade_no')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="mt-4">
                            <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('Update') }}</button>
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
                    <h4 class="card-title">{{ __('Delete ') . $name }}</h4>
                    <p class="card-title-desc">
                        <mark>{{ __('Item will be permanently delete from database') }}</mark>
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ $deleteRoute }}" method="POST">
                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?');">
                            <i class="bx bx-block font-size-16 align-middle me-2"></i> {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
