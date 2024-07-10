@props([
    'name',
    'data',
    'categories',
    'schemes',
    'updateRoute',
    'deleteRoute'
])

<div class="row">
    <div class="col-lg-8">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Update ') . $name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $updateRoute }}">
                        @csrf
                        @method('put')
                        <input type="hidden" name="_id" value="{{ $data->id }}">
                        <div class="mb-4">
                            <label class="form-label" for="attr_name">{{ __('messages.Name') }}</label>
                            <input class="form-control form-control-lg" type="text" name="name" value="{{old('name', $data->name)}}" id="attr_name" placeholder="{{ $name }}" required>
                            @error('name')
                                <code>{{ $message }}</code>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="form-label" for="category_id">{{ __('messages.Category') }}</label>
                            <select class="form-select" name="category_id">
                                @foreach($categories as $cat)
                                    <option @if($data->category_id == old('category_id', $cat->id)) selected @endif value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label class="form-label" for="scheme_id">{{ __('messages.Scheme') }}</label>
                            <select class="form-select" name="scheme_id">
                                @foreach($schemes as $scheme)
                                    <option @if($data->scheme_id == old('scheme_id', $scheme->id)) selected @endif value="{{$scheme->id}}">{{$scheme->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Update') }}</button>
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
                    <h4 class="card-title">{{ __('messages.Details') }}</h4>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $data->name }}</p>
                    <p>{{ $data->details }}</p>
                    <p class="text-muted font-size-13 mt-4 mb-0"><i>{{ __('messages.Last updated ') }}{{ $data->updated_at->diffForHumans() }}</i></p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $name }}</h4>
                    <p class="card-title-desc">
                        <mark>{{ __('Item will be permanently delete from database') }}</mark>
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ $deleteRoute }}" method="POST">
                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?');">
                            <i class="bx bx-block font-size-16 align-middle me-2"></i> {{ __('messages.Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
