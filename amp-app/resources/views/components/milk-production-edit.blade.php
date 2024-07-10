@props([
    'name',
    'data',
    'locations',
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
                            <label class="form-label" for="date">{{ __('Date') }}</label>
                            <input class="form-control form-control-lg" type="date" name="date" value="{{old('date', $data->date->format('Y-m-d'))}}" id="date" required>
                            @error('date')
                                <code>{{ $message }}</code>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label class="form-label" for="quantity">{{ __('Quantity (ltr)') }}</label>
                            <input class="form-control form-control-lg" type="text" name="quantity" value="{{$data->quantity}}" id="quantity" >
                            @error('quantity')
                                <code>{{ $message }}</code>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="form-label" for="comments">{{ __('Comments') }}</label>
                                <input class="form-control form-control-lg" type="text" name="comments" value="{{$data->comments}}" id="comments">
                                @error('comments')
                                    <code>{{ $message }}</code>
                                @enderror
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
