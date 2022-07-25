@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Create Contact')}}</h1>
    <form action="{{route('admin.contacts.store')}}" id="contact"
          method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <x-label class="font-bold text-lg " for="firstName" :value="__('Contact Vorname')"/>
            <x-input id="firstName" class="block mt-1 w-full"
                     type="text" name="firstName" required
                     :value="old('firstName')"
            />
            @error('firstName')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="lastName" :value="__('Contact Name')"/>
            <x-input id="lastName" class="block mt-1 w-full"
                     type="text" name="lastName" required
                     :value="old('lastName')"
            />
            @error('lastName')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="email" :value="__('Contact Email')"/>
            <x-input id="email" class="block mt-1 w-full"
                     type="email" name="email" required
                     :value="old('email')"
            />
            @error('email')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="tel" :value="__('Contact Telefonnummer')"/>
            <x-input id="phone" class="block mt-1 w-full"
                     type="tel" name="phone"
                     :value="old('phone')"
            />
            @error('phone')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit" class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full">
            {{__("Save")}}
        </button>
    </form>
@endsection
