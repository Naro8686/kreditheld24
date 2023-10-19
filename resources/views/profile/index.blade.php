@extends('layouts.admin')
@section('content')
    @if(auth()->user()->isManager())
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
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{__('Statistics')}}</h1>
            <div class="no-arrow d-flex flex-row flex-wrap">
                <label class="mr-2">
                    <input class="flatpickr flatpickr-input form-control form-control-sm"
                           type="text" placeholder="Select Date..">
                </label>
            </div>
        </div>

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
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_approved_all">0
                                        </div>
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
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="sum_approved_year">0
                                        </div>
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
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile') }}
    </h2>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('profile.update')}}" id="profile"
                          method="POST" enctype="multipart/form-data"
                          x-data="{user:{{ Illuminate\Support\Js::from(auth()->user()) }}}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-label class="font-bold text-lg " for="email" :value="__('Email')"/>
                            <x-input id="email" class="block mt-1 w-full"
                                     type="email" name="email" required
                                     :value="old('email',auth()->user()->email)"
                            />
                            @error('email')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="name" :value="__('Name')"/>
                            <x-input id="name" class="block mt-1 w-full"
                                     type="text" name="name"
                                     :value="old('name',auth()->user()->name)"
                            />
                            @error('name')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="surname" :value="__('Surname')"/>
                            <x-input id="surname" class="block mt-1 w-full"
                                     type="text" name="surname"
                                     :value="old('surname',auth()->user()->surname)"
                            />
                            @error('surname')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg" for="birthday" :value="__('Birthday')"/>
                            <x-input id="birthday" class="block mt-1 w-full"
                                     type="date" name="birthday"
                                     :value="old('birthday',optional(auth()->user()->birthday)->format('Y-m-d'))"
                            />
                            @error('birthday')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="tel" :value="__('Phone Number')"/>
                            <x-input id="phone" class="block mt-1 w-full"
                                     type="tel" name="phone"
                                     :value="old('phone',auth()->user()->phone)"
                            />
                            @error('phone')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="city" :value="__('City')"/>
                            <x-input id="city" class="block mt-1 w-full"
                                     type="text" name="city"
                                     :value="old('city',auth()->user()->city)"
                            />
                            @error('city')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="street" :value="__('Street')"/>
                            <x-input id="street" class="block mt-1 w-full"
                                     type="text" name="street"
                                     :value="old('street',auth()->user()->street)"
                            />
                            @error('street')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="house" :value="__('House')"/>
                            <x-input id="house" class="block mt-1 w-full"
                                     type="text" name="house"
                                     :value="old('house',auth()->user()->house)"
                            />
                            @error('house')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="postcode" :value="__('Postcode')"/>
                            <x-input id="postcode" class="block mt-1 w-full"
                                     type="text" name="postcode"
                                     :value="old('postcode',auth()->user()->postcode)"
                            />
                            @error('postcode')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="card_number" :value="__('Card Number')"/>
                            <x-input id="card_number" class="block mt-1 w-full"
                                     type="text" name="card_number"
                                     :value="old('card_number',auth()->user()->card_number)"
                            />
                            @error('card_number')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-lg " for="tax_number" :value="__('Tax number')"/>
                            <x-input id="tax_number" class="block mt-1 w-full"
                                     type="text" name="tax_number"
                                     :value="old('tax_number',auth()->user()->tax_number)"
                            />
                            @error('tax_number')
                            <p class="text-sm text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <!-- <button type="submit"
                                x-text="'{{__("Save")}}'"
                                class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"/>-->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
