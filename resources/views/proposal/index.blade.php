@extends('layouts.admin')
@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Proposals') }}
    </h2>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div id="statistics" class="flex flex-wrap -mx-2">
                <div class="sm:w-1/3 w-full px-2 mb-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-1 font-bold tracking-tight text-gray-900 dark:text-white">
                            {{__('TOTAL VOLUME (MONTH)')}}
                        </h2>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{$monthSum.' '.\App\Models\Proposal::CURRENCY}}</p>
                    </div>
                </div>
                <div class="sm:w-1/3 w-full px-2 mb-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-1 font-bold tracking-tight text-gray-900 dark:text-white">
                            {{__('TOTAL VOLUME (YEAR)')}}</h2>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{$totalSum.' '.\App\Models\Proposal::CURRENCY}}</p>
                    </div>
                </div>
                <div class="sm:w-1/3 w-full px-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <div class="mb-4 font-bold tracking-tight text-gray-900 dark:text-white">
                            <div class="flex justify-between mb-1">
                                <h2 class="font-bold tracking-tight text-gray-900 dark:text-white">{{__('Target')}}</h2>
                                <span
                                    class="text-sm font-medium text-blue-700 dark:text-white">{{$targetPercent}}%</span>
                            </div>
                        </div>
                        <div class="font-normal text-gray-700 dark:text-gray-400">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{$targetPercent}}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("manager.includes.proposal_table")
@endsection

@push('css')
    <style>
        #statistics > div > div {
            min-height: 80px;
        }

        .h2, h2 {
            font-size: inherit;
        }
    </style>
@endpush
