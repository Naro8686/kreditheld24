@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Add new manager')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('admin.managers.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   aria-describedby="nameHelp" autofill="off"
                                   placeholder="Enter name" autocomplete="off" value="{{old('name')}}">
                            @error('name')
                            <small id="emailHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   aria-describedby="emailHelp" autofill="off" required
                                   placeholder="Enter email" autocomplete="off" value="{{old('email')}}">
                            @error('email')
                            <small id="emailHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password"
                                   id="password" placeholder="Password" required
                                   autocomplete="off">
                            @error('password')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!-- <div class="form-group">
                            <label for="target">{{__('Target')}}</label>
                            <input type="number" class="form-control" name="target"
                                   id="target" placeholder="{{__('Target')}}" required
                                   autocomplete="off" value="{{old('target',1000000)}}">
                            @error('target')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div> -->
                        <input type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
