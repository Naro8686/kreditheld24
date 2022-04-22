@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Edit')}}</h1>
    <form action="{{route('admin.formulas.update',[$formula->id])}}" id="formula"
          method="POST" enctype="multipart/form-data"
          x-data="{user:{{ Illuminate\Support\Js::from($formula) }}}">
        @csrf
        @method('PUT')
        <div>
            <x-label class="font-bold text-lg " for="name" :value="__('Name')"/>
            <x-input id="name" class="block mt-1 w-full"
                     type="text" name="name" required
                     :value="old('name',$formula->name)"
            />
            @error('name')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mt-3">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="document">
                <label class="custom-file-label" for="customFile">{{str_replace($formula::UPLOAD_FILE_PATH . '/', '', $formula->file)}}</label>
            </div>
            @error('document')
            <p class="text-sm text-danger">{{$message}}</p>
            @enderror
        </div>
        <button type="submit"
                x-text="'{{__("Save")}}'"
                class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"/>
    </form>
@endsection
