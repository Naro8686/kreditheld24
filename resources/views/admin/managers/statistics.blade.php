@push('css')
    <link href="{{asset('adminPanel/css/flatpickr.min.css')}}" rel="stylesheet">
    <style>
        .flatpickr-input {
            min-width: 195px;
        }

        @media (max-width: 375px) {
            .flatpickr-input {
                min-width: 100%;
            }
        }
    </style>
@endpush
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Manager statistics')}}</h1>
    <a class="btn btn-link"
       href="{{route('admin.email.index',['type' => 'manager', 'email' => $manager->email])}}">{{$manager->email}}</a>
    <a class="btn btn-sm btn-info mr-1" href="{{route('admin.managers.edit',[$manager->id])}}"><i
            class="fas fa-user-edit"></i></a>
    <div class="no-arrow d-flex flex-row flex-wrap">
        <label class="mr-2">
            <input class="flatpickr flatpickr-input form-control form-control-sm"
                   type="text" placeholder="Select Date..">
        </label>
    </div>
</div>
<!-- Content Row -->
<div id="orders" class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{__('Sum')}}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            {{__('Target')}}
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="rate">0%
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                         style="width: 0" aria-valuenow="0" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            {{__('Total')}}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="total">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{__('Approved')}}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="completed">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{__('Denied')}}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="denied">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="row">
            <h6 class="col-md-12 mb-3">{{__('Amount of approved applications')}}</h6>
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{__('For all time')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_approved_all">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{__('In a year')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_approved_year">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="row">
            <h6 class="col-md-12 mb-3">{{__('Sum of all applications')}}</h6>
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{__('For all time')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_all">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{__('In a year')}}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_year">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.managers.invoices')
@push('js')
    <script src="{{asset('adminPanel/js/flatpickr.js')}}"></script>
    <script id="statistics" src="{{asset('adminPanel/js/statistics.js?manager_id='.$manager->id)}}"></script>
@endpush
