@props([
    'name',
    'data',
    'locations',
    'viewRoute',
    'storeRoute'
])

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $name }} </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Quantity(ltr)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $d)
                            @php
                            $route = auth()->user()->hasRole('staff|admin') ? route($viewRoute, $d->id) : '#';
                            @endphp
                                <tr>
                                    <th scope="row">{{++$key}}</th>
                                    <td><a href="{{ $route }}">{{dateFormat($d->date,'d M, Y')}}</a></td>
                                    <td>{{ $d->quantity }}</td>
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
                                <label class="form-label" for="attr_name">{{ __('Date') }}</label>
                                <input class="form-control form-control-lg" type="date" name="date" value="{{old('date', (new DateTime())->format('Y-m-d'))}}" id="date" required>
                                @error('date')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="quantity">{{ __('Quantity (ltr)') }}</label>
                                <input class="form-control form-control-lg" type="text" name="quantity" value="{{old('quantity')}}" id="quantity" placeholder="{{ __('1234') }}">
                                @error('quantity')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="comments">{{ __('Comments') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="comments" value="{{old('comments')}}" id="comments">
                                    @error('comments')
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

</div>
