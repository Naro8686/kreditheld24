@extends('layouts.admin')
@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Draft') }}
    </h2>
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
