<div style="padding: 10px">
    <h6 class="font-weight-bold text-primary">{{__("Templates")}}</h6>
    <div id="templates-list" class="list-group" style="max-height: 100px;overflow-y: scroll">
        @foreach($templates as $template)
            <a href="{{route('email-templates.show',[$template->id])}}"
               class="list-group-item list-group-item-action">{{$template->name}}</a>
        @endforeach
    </div>
</div>
@push('css')

@endpush
@push('js')
    <script>
        $('#templates-list > a').on('click', function (e) {
            e.preventDefault();
            $.get($(this).attr('href'), function (content) {
                let summernote = $('textarea.summernote');
                summernote.summernote("code", "");
                summernote.summernote("pasteHTML", content);
            })
            return false;
        });
    </script>
@endpush
