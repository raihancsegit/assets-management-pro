@props([
    'categories',
    'modal_title',
    'route',
    'custom_action',
    'data_toggle'
])
<form method="GET" action="{{ route($route) }}">
    <input type="hidden" name="custom_action" value="{{ $custom_action }}">
    <div id="{{ $data_toggle }}" class="modal fade" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFullscreenLabel">{{ __($modal_title)}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>{{__('Filter by following configurations')}}</h5>
                    <div class="mb-3"></div>
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label" for="status">{{ __('messages.Status') }}</label>
                                <select id="formrow-inputState" class="form-select" name="status">
                                    <option value="">All</option>
                                    <option @if(request('status') === '1' ) selected @endif value="1">Approved</option>
                                    <option @if(request('status') === '0' ) selected @endif value="0">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="category" class="form-label">Select Category:</label>
                                <select class="form-control" name="category" id="category">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">From</label>
                                <input class="form-control" type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">To</label>
                                <input class="form-control" type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Filter')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form>
