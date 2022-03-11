@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Dashboard')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{__('You are logged in!')}}
                </div>
            </div>
        </div>
    </div>
@endsection
