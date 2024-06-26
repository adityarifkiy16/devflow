<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-8 d-flex align-items-center">
                    <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ $title }}</p>
                        <h5 class="font-weight-bolder">
                            {{ $amount }}
                        </h5>
                    </div>
                </div>
                <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-warning shadow-primary text-center rounded-circle">
                        <i class="fa fa-{{$typeIcon}} text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>