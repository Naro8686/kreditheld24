<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('profile.update')}}" id="profile"
                          method="POST" enctype="multipart/form-data"
                          x-data="{user:{{ Illuminate\Support\Js::from(auth()->user()) }}}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-label class="font-bold text-lg " for="card_number" :value="__('Card Number')"/>
                            <x-input id="card_number" class="block mt-1 w-full"
                                     type="number" name="card_number"
                                     :value="old('card_number',auth()->user()->card_number)"
{{--                                     x-bind:value="user.card_number || ''"--}}
                                     x-model="user.card_number"
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
</x-app-layout>
