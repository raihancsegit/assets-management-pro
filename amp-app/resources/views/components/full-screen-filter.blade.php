@props([
    'name',
    'route',
    'categoryId'
])
<form method="GET" action="{{ route($route, $categoryId) }}">
    <div id="fullscreenFilterModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFullscreenLabel">{{$name . ' ' . __('Filter')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>{{__('Filter by following configurations')}}</h5>
                    <div class="mb-3"></div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label" for="status">{{ __('messages.Status') }}</label>
                                <select id="formrow-inputState" class="form-select" name="status">
                                    <option value="">All</option>
                                    <option @if(request('status') === '1' ) selected @endif value="1">Approved</option>
                                    <option @if(request('status') === '0' ) selected @endif value="0">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label" for="receipt_no">{{ __('messages.Receipt No') }}</label>
                                <input class="form-control form-control-lg" type="text" name="receipt_no" value="{{request('receipt_no')}}" id="receipt_no" placeholder="{{ __('SEM35776') }}">
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
