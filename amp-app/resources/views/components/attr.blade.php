@props([
    'name',
    'data',
    'categories',
    'schemes',
    'viewRoute',
    'storeRoute'
])

<div class="row">
    @php
        $className = auth()->user()->hasRole('manager') ? 'col-lg-12' : 'col-lg-8';
    @endphp
    <div class="{{$className}}">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $name }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.Category') }}</th>
                                <th>{{ __('messages.Scheme') }}</th>
                                @hasanyrole('staff|admin')
                                    <th>{{ __('messages.Action') }}</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $d)
                                <tr>
                                    <th scope="row">{{++$key}}</th>
                                    <td><a href="{{ route($viewRoute, $d->id) }}">{{$d->name}}</a></td>
                                    <td>{{$d->category->name}}</td>
                                    <td>{{$d->scheme->name}}</td>
                                    @hasanyrole('staff|admin')
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <li><a class="dropdown-item" href="{{ route($viewRoute, $d->id) }}">{{__('messages.Edit')}}</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    @endhasanyrole
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-container mt-4 mb-0">
                        {{ $data->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @hasanyrole('staff|admin')
        <div class="col-lg-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.Add New ') . $name }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ $storeRoute }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="attr_name">{{ __('messages.Name') }}</label>
                                <input class="form-control form-control-lg" type="text" name="name" value="{{old('name')}}" id="attr_name" placeholder="{{ __('Sales') }}" required>
                                @error('name')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="category_id">{{ __('messages.Category') }}</label>
                                <select class="form-select" name="category_id" id="category_id">
                                    @foreach($categories as $cat)
                                        <option @if($cat->id == old('category_id', $cat->id)) selected @endif value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="scheme_id">{{ __('messages.Scheme') }}</label>
                                <select class="form-select" name="scheme_id" id="scheme_id">
                                    @foreach($schemes as $scheme)
                                        <option @if($scheme->id == old('scheme_id', $scheme->id)) selected @endif value="{{$scheme->id}}">{{$scheme->name}}</option>
                                    @endforeach
                                </select>
                                @error('scheme_id')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole
</div>
