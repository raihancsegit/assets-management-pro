@props([
    'name',
    'category',
    'types',
    'units',
    'createRoute',
    'managers',
])

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.Create New '). $name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $createRoute }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="date">{{ __('messages.Date') }}</label>
                                    <input class="form-control form-control-lg" type="date" name="date" value="{{ old('date', (new DateTime())->format('Y-m-d')) }}" id="date" required>
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
                                    <input class="form-control form-control-lg" type="text" name="amount" value="{{ old('amount') }}" id="amount" placeholder="{{ __('1234') }}" required>
                                    @error('amount')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="receipt_no">{{ __('messages.Receipt No') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="receipt_no" value="{{ old('receipt_no') }}" id="receipt_no" placeholder="{{ __('SEM35776') }}">
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
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                            <option @if($type->id == old('type_id')) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
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
                                            <option @if($unit->id == old('unit_id')) selected @endif value="{{ $unit->id }}">{{ $unit->name }}</option>
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
                                    <input class="form-control form-control-lg" type="number" name="unit_value" value="{{ old('unit_value') }}" id="unit_value" placeholder="{{ __('1.50') }}">
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
                                    <textarea class="form-control form-control-lg" name="details" id="details">{{ old('details') }}</textarea>
                                    @error('details')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="notes">{{ __('messages.Notes') }}</label>
                                    <input class="form-control form-control-lg" type="text" name="notes" value="{{ old('notes') }}" id="notes">
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
                                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Approved</option>
                                            <option value="0" {{ old('status', 0) == 0 ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        @error('status')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input " type="checkbox" name="in_hand" id="in_hand" value="1" {{ old('in_hand') ? 'checked' : '' }}>
                                        <label class="form-check-label form-label" for="in_hand" style="margin-bottom: 0.5rem;">
                                            {{ __('Manager In Hand') }}
                                        </label>
                                        @error('in_hand')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                    <div class="mb-3" id="manager_dropdown" style="display: {{ old('in_hand') ? 'block' : 'none' }}">
                                        <select id="manager_id" class="form-select" name="manager_id">
                                            @foreach($managers as $manager)
                                                <option @if($manager->id == old('manager_id')) selected @endif value="{{ $manager->id }}">{{ $manager->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('manager_id')
                                            <code>{{ $message }}</code>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('messages.Add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('in_hand').addEventListener('change', function () {
        var managerDropdown = document.getElementById('manager_dropdown');
        if (this.checked) {
            managerDropdown.style.display = 'block';
        } else {
            managerDropdown.style.display = 'none';
        }
    });
</script>
