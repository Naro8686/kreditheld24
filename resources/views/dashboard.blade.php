@extends('layouts.admin')

@section('content')
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
        <h1 class="h3 mb-0 text-gray-800">{{__('Dashboard')}}</h1>
        <div class="no-arrow d-flex flex-row flex-wrap">
            <label class="mr-2">
                <input class="flatpickr flatpickr-input form-control form-control-sm"
                       type="text" placeholder="Select Date..">
            </label>
            <label>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('proposal.create') }}">{{ __('Create') }}</a>
            </label>
        </div>
    </div>
    <!-- Content Row -->
    <div id="orders" class="row">

        <div class="col-xl-3 col-md-6 mb-4">
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
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
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
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
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
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
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
    </div>
    <!-- Content Row -->
{{--    <div class="row">--}}
{{--        <!-- Area Chart -->--}}
{{--        <div class="col-xl-12 col-lg-12">--}}
{{--            <div class="card shadow mb-4">--}}
{{--                <!-- Card Header - Dropdown -->--}}
{{--                <div class="card-header py-3 d-flex flex-row flex-wrap align-items-center justify-content-between">--}}
{{--                    <h6 class="m-0 font-weight-bold text-primary">{{__('Statistics')}}</h6>--}}
{{--                    <div class="no-arrow d-flex flex-row flex-wrap">--}}
{{--                        <label class="mr-2">--}}
{{--                            <input class="flatpickr flatpickr-input form-control form-control-sm"--}}
{{--                                   type="text" placeholder="Select Date..">--}}
{{--                        </label>--}}
{{--                        <label for="unit">--}}
{{--                            <select id="unit" name="unit" class="form-control form-control-sm">--}}
{{--                                <option selected value="hour">{{__('hour')}}</option>--}}
{{--                                <option value="day">{{__('day')}}</option>--}}
{{--                                <option value="week">{{__('week')}}</option>--}}
{{--                                <option value="month">{{__('month')}}</option>--}}
{{--                                <option value="year">{{__('year')}}</option>--}}
{{--                            </select>--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- Card Body -->--}}
{{--                <div class="card-body">--}}
{{--                    <div class="chart-area">--}}
{{--                        <canvas id="purchasesChart"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="py-6">
        @includeWhen(auth()->user()->isAdmin(), "admin.includes.proposal_table")
        @includeWhen(auth()->user()->isManager(), "manager.includes.proposal_table")
    </div>
    @push('js')
{{--        <script src="{{asset('adminPanel/vendor/chart.js/Chart.js')}}"></script>--}}
{{--        <script src="{{asset('adminPanel/js/chartjs-adapter-date-fns.bundle.min.js')}}"></script>--}}
        <script src="{{asset('adminPanel/js/flatpickr.js')}}"></script>
        <script src="{{asset('adminPanel/js/statistics.js')}}"></script>
    @endpush
@endsection

