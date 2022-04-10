<h1 class="h3 mb-4 text-gray-800">{{__('Send')}}</h1>
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{route('admin.email.send',['type' => $data['type']])}}" method="POST"
                      autocomplete="off">
                    @csrf
                    @isset($data['email'])
                        <input type="hidden" name="email" value="{{$data['email']}}">
                    @endisset
                    <div class="form-group">
                        <label for="message">{{__('Message for')}}
                            - @isset($data['email']) {{$data['email']}} @else {{__('all')}} @endisset</label>
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
@push('js')
    <script>
        $(document).ready(function () {
            $('#message').summernote({
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
