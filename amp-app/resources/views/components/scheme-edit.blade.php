@props([
    'data',
    'name',
    'category',
    'categories',
    'types',
    'units',
    'updateRoute',
    'deleteRoute',
])

<div class="row">
    <div class="col-lg-8">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Update '). $name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $updateRoute }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="date">{{ __('messages.Date') }}</label>
                                    <input class="form-control form-control-lg" type="date" name="date" value="{{old('date', $data->date->format('Y-m-d'))}}" id="date" required>
                                    @error('date')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="amount">{{ __('messages.Amount') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="amount" value="{{old('amount', $data->amount)}}" id="amount" placeholder="{{ __('1234') }}" required>
                                    @error('amount')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="receipt_no">{{ __('messages.Receipt No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="receipt_no" value="{{old('receipt_no', $data->receipt_no)}}" id="receipt_no" placeholder="{{ __('SEM35776') }}">
                                    @error('receipt_no')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="category_id">{{ __('messages.Category') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="category_id">
                                        @foreach($categories as $cat)
                                            <option @if($cat->id == old('category_id', $data->category_id) ) selected @endif value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="type_id">{{ __('messages.Type') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="type_id">
                                        <option selected="">{{ __('Choose type...') }}</option>
                                        @foreach($types as $type)
                                            <option @if($type->id == old('type_id', $data->type_id) ) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="unit_id">{{ __('messages.Unit') }}</label>
                                    <select id="formrow-inputState" class="form-select" name="unit_id">
                                        <option selected="">{{ __('Choose unit...') }}</option>
                                        @foreach($units as $unit)
                                            <option @if($unit->id == old('unit_id', $data->unit_id) ) selected @endif value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="unit_value">{{ __('messages.Unit value') }}</label>
                                    <input class="form-control form-control-lg" type="number" name="unit_value" value="{{old('unit_value', $data->unit_value)}}" id="unit_value" placeholder="{{ __('1.50') }}">
                                    @error('unit_value')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">{{ __('messages.Attachment') }}</label>
                                    <input class="form-control form-control-md" id="attachment" type="file" name="attachment" value="{{ old('attachment') }}">
                                    @error('attachment')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="details">{{ __('messages.Details') }}</label>
                                    <textarea class="form-control form-control-lg" name="details" id="details">{{old('details', $data->details)}}</textarea>
                                    @error('details')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="notes">{{ __('messages.Notes') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="notes" value="{{old('notes', $data->notes)}}" id="notes">
                                    @error('notes')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(!isManager())
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="status">{{ __('messages.Status') }}</label>
                                        <select id="formrow-inputState" class="form-select" name="status">
                                            <option @if($data->status == old('status', 1) ) selected @endif value="1">Approved</option>
                                            <option @if($data->status == old('status', 0) ) selected @endif value="0">Pending</option>
                                        </select>
                                        @error('status')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

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
                    <h4 class="card-title">{{ $name . __(' Details') }}</h4>
                </div>
                <div class="card-body">
                    <p class="lead">{{ __('Amount') }} : {{ toCurrency($data->amount) }}</p>
                    <p>{{ __('Receipt No') }} : {{ $data->receipt_no }}</p>
                    <p>{{ __('Category') }} : {{ $data->category ? $data->category->name : __('empty') }}</p>
                    <p>{{ __('Type') }} : {{ $data->type ? $data->type->name : __('empty') }}</p>
                    <p>{{ __('Unit') }} : {{ $data->unit ? $data->unit->name : __('empty') }}</p>
                    <p>{{ __('Unit Value') }} : {{ $data->unit_value }}</p>
                    <p>{{ __('Notes') }} :</p>
                    <p>{{ $data->notes }}</p>
                    <p>{{ __('Details') }} :</p>
                    <p>{{ $data->details }}</p>
                    <p>{{ __('Attachment') }} :
                        @if(isset($data->attachment->url))
                            <a target="_blank" href="{{ asset('attachments/' . $data->attachment->url ) }}">View</a>
                        @else
                            <span class="text-muted font-size-13 mb-0"><i>{{ __('Empty') }}</i></span>
                        @endif
                    </p>

                    <p class="text-muted font-size-13 mt-4 mb-0">{{ __('Created by ') }} {{ $data->created_user ? $data->created_user->name : '' }} {{ $data->created_at->diffForHumans() }}</p>
                    <p class="text-muted font-size-13 mt-2 mb-0"><i>{{ __('Last updated ') }} {{ $data->updated_user ? $data->updated_user->name : '' }} {{ $data->updated_at->diffForHumans() }}</i></p>
                </div>
            </div>
        </div>
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
