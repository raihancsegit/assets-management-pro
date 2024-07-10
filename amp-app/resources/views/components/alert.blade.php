@if($errors->any())
    <div class="col-lg-12">
        <div class="alert alert-danger" role="alert">
            {{$errors->first()}}
        </div>
    </div>
@endif
@if (session('success'))
    <div class="col-lg-12">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    </div>
@endif
@if (session('error'))
    <div class="col-lg-12">
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    </div>
@endif
