@props([
    'categories',
    'modal_title',
    'custom_action',
    'data_toggle'
])
<form method="GET" action="{{ route('inReview') }}">
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
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="category">Select Category:</label>
                                    <select class="form-control" name="category" id="category">
                                        <option value="">All Categories</option>
                                        <!-- Populate the dropdown options with categories -->
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="example-date-input" class="col-md-2 col-form-label">From</label>
                            <input class="form-control" type="date"  id="date-input" name="start_date" value="{{request('start_date')}}">
                        </div>
                        <div class="col-md-2">
                            <label for="example-date-input" class="col-md-2 col-form-label">To</label>
                            <input class="form-control" type="date"  id="date-input2" name="end_date" value="{{request('end_date')}}">
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