@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('Dashboard')}}</h1>
        @if(auth()->user()->isAdmin())
            <div class="no-arrow d-flex flex-row flex-wrap">
                <label class="mr-2">
                    <input class="flatpickr flatpickr-input form-control form-control-sm"
                           type="text" placeholder="Select Date..">
                </label>
            </div>
        @endif
    </div>
    @if(auth()->user()->isAdmin())
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
        <div id="orders" class="row">
            <div class="col-xl-4 col-md-6 mb-4">
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
        <ul class="list-group mb-3">
            @php
                $files = [];
                if (isset($invoices)) $files = $invoices->pluck('invoice_file')->map(function ($file) {
                    $file = trim($file, '/');
                    return public_path("storage/$file");
                })->toArray()
            @endphp
            @foreach($invoices as $invoice)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{$invoice->number ?? $invoice->invoice_file}}
                    <span class="badge badge-primary badge-pill">
                    <a class='btn btn-sm text-white'
                       href='{{route('manager.downloadFile', ['path' => $invoice->invoice_file])}}'><i
                            class="fas fa-download"></i></a>
                </span>
                </li>
            @endforeach
            @if(count($files))
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class='btn btn-block btn-primary'
                       href='{{route('manager.downloadFilesZip', ['files' => $files])}}'><i
                            class="fas fa-download"></i></a>
                </li>
            @endif
        </ul>
        @push('js')
            <script src="{{asset('adminPanel/js/flatpickr.js')}}"></script>
            <script src="{{asset('adminPanel/js/statistics.js')}}"></script>
        @endpush
    @endif
    <a class="btn btn-lg btn-success btn-block" href="{{ route('proposal.create') }}"
       target="_blank">{{ __('Create') }}</a>
    <div class="py-6">
        @includeWhen(auth()->user()->isAdmin(), "admin.includes.proposal_table")
        @includeWhen(auth()->user()->isManager(), "manager.includes.proposal_table")
    </div>
@endsection
