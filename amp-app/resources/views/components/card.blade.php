@props([
    'name',
    'amount',
    'progress',
    'icon'
])
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h6 class="font-size-15">{{$name}}</h6>
                <h4 class="mt-3 pt-1 mb-0 font-size-22">
                    {{toCurrency($amount)}}
                    @if(!empty($progress))
                        <span class="text-success fw-medium font-size-14 align-middle">
                            <i class="mdi mdi-arrow-up"></i>
                            {{$progress}}
                        </span>
                    @endif
                </h4>
            </div>
            <div>
                <div class="avatar">
                    <div class="avatar-title rounded bg-primary-subtle ">
                        <i class="bx {{$icon}} font-size-24 mb-0 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
