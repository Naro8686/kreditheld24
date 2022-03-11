@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Create')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('admin.managers.store')}}" method="POST" autocomplete="off" >
                        @csrf
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   aria-describedby="emailHelp" autofill="off"
                                   placeholder="Enter email" autocomplete="off">
                            @error('email')
                            <small id="emailHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password"
                                   id="password" placeholder="Password"
                                   autocomplete="off">
                            @error('password')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
