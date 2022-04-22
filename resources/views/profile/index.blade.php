@extends('layouts.admin')
@section('content')
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
                        <button type="submit"
                                x-text="'{{__("Save")}}'"
                                class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
