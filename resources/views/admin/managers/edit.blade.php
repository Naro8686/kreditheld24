@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Edit')}}</h1>
    <form action="{{route('admin.managers.update',[$manager->id])}}" id="profile"
          method="POST" enctype="multipart/form-data"
          x-data="{user:{{ Illuminate\Support\Js::from($manager) }}}">
        @csrf
        @method('PUT')
        <div>
            <x-label class="font-bold text-lg " for="email" :value="__('Email')"/>
            <x-input id="email" class="block mt-1 w-full"
                     type="email" name="email" required
                     :value="old('email',$manager->email)"
            />
            @error('email')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="name" :value="__('Name')"/>
            <x-input id="name" class="block mt-1 w-full"
                     type="text" name="name"
                     :value="old('name',$manager->name)"
            />
            @error('name')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="surname" :value="__('Surname')"/>
            <x-input id="surname" class="block mt-1 w-full"
                     type="text" name="surname"
                     :value="old('surname',$manager->surname)"
            />
            @error('surname')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg" for="birthday" :value="__('Birthday')"/>
            <x-input id="birthday" class="block mt-1 w-full"
                     type="date" name="birthday"
                     :value="old('birthday',optional($manager->birthday)->format('Y-m-d'))"
            />
            @error('birthday')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="tel" :value="__('Phone Number')"/>
            <x-input id="phone" class="block mt-1 w-full"
                     type="tel" name="phone"
                     :value="old('phone',$manager->phone)"
            />
            @error('phone')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="city" :value="__('City')"/>
            <x-input id="city" class="block mt-1 w-full"
                     type="text" name="city"
                     :value="old('city',$manager->city)"
            />
            @error('city')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="street" :value="__('Street')"/>
            <x-input id="street" class="block mt-1 w-full"
                     type="text" name="street"
                     :value="old('street',$manager->street)"
            />
            @error('street')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="house" :value="__('House')"/>
            <x-input id="house" class="block mt-1 w-full"
                     type="text" name="house"
                     :value="old('house',$manager->house)"
            />
            @error('house')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="postcode" :value="__('Postcode')"/>
            <x-input id="postcode" class="block mt-1 w-full"
                     type="text" name="postcode"
                     :value="old('postcode',$manager->postcode)"
            />
            @error('postcode')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg " for="card_number" :value="__('Card Number')"/>
            <x-input id="card_number" class="block mt-1 w-full"
                     type="text" name="card_number"
                     :value="old('card_number',$manager->card_number)"
            />
            @error('card_number')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit"
                x-text="'{{__("Save")}}'"
                class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"/>
    </form>
@endsection
