@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Send')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form
                        action="{{ request()->routeIs('email-templates.create')? route('email-templates.store'):route('email-templates.update',[$template->id])}}"
                        method="POST"
                        autocomplete="off">
                        @csrf
                        @if(request()->routeIs('email-templates.edit'))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input id="name" class="form-control" name="name" value="{{old('name',$template->name ?? null)}}">
                            @error('name')
                            <small id="messageHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject">{{__('Theme')}}</label>
                            <input id="subject" class="form-control" name="subject" value="{{old('subject',$template->subject ?? null)}}">
                            @error('subject')
                            <small id="messageHelp" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="template_content">{{__('Content')}}</label>
                            <textarea rows="7" class="form-control" id="template_content"
                                      name="content">{!! old('content',$template->content ?? null) !!}</textarea>
                            @error('content')
                            <small id="messageContent" class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function () {
                $('#template_content').summernote({
                    height: 300,
                    focus: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']],
                    ]
                });
            });
        </script>
    @endpush

@endsection
