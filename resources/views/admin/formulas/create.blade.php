@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Create')}}</h1>
    <form action="{{route('admin.formulas.store')}}" id="formula"
          method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <x-label class="font-bold text-lg " for="name" :value="__('Name')"/>
            <x-input id="name" class="block mt-1 w-full"
                     type="text" name="name" required
                     :value="old('name')"
            />
            @error('name')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="document" required>
                <label class="custom-file-label" for="customFile">{{__("Choose File")}}</label>
            </div>
            @error('document')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit" class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full">
            {{__("Save")}}
        </button>
    </form>
@endsection
