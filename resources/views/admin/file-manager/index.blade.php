@extends('layouts.admin')

@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">
    @endpush
    <div class="row">
        <div class="col-md-12" id="fm-main-block">
            <div id="fm"></div>
        </div>
    </div>
    @push('js')
        <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // set fm height
                document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

                const FileBrowserDialogue = {
                    init: function () {
                        // Here goes your code for setting your custom things onLoad.
                    },
                    mySubmit: function (URL) {
                        // pass selected file path to TinyMCE
                        parent.postMessage({
                            mceAction: 'insert',
                            content: URL,
                            text: URL.split('/').pop()
                        })
                        // close popup window
                        parent.postMessage({mceAction: 'close'});
                    }
                };

                // Add callback to file manager
                fm.$store.commit('fm/setFileCallBack', function (fileUrl) {
                    FileBrowserDialogue.mySubmit(fileUrl);
                });
            });
        </script>
    @endpush
@endsection
