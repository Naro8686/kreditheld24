@extends('layouts.admin')
@section('content')
    @push('css')
        <style>
            label {
                margin-bottom: 0;
            }
        </style>
    @endpush
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create') }}
    </h2>
    <x-proposal.create/>
@endsection
