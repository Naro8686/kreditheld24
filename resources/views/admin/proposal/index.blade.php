@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Proposals')}}</h1>
    <div class="py-6">
        @include("admin.includes.proposal_table")
    </div>
@endsection
