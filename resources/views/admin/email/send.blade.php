@extends('layouts.admin')
@push('css')
    <link href="{{asset('adminPanel/summernote-0.8.18-dist/summernote.min.css')}}" rel="stylesheet">
@endpush
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Send')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('admin.email.manager.send',[optional($manager)->id])}}" method="POST"
                          autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="message">{{__('Message for')}}
                                - @if($manager) {{$manager->email}} @else {{__('all')}} @endif</label>
                            <textarea rows="7" class="form-control" id="message"
                                      name="message">{!! old('message') !!}</textarea>
                            @error('message')
                            <small id="messageHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('adminPanel/summernote-0.8.18-dist/summernote.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#message').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true
            });
        });
    </script>
@endpush
