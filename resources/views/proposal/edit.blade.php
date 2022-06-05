@extends('layouts.admin')
@section('content')
    @push('css')
        <style>
            label {
                margin-bottom: 0;
            }

            .iframe-container {
                position: relative;
                overflow: hidden;
                width: 100%;
                padding-top: 56.25%;
            }

            .responsive-iframe {
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                width: 100%;
                height: 100%;
            }
        </style>
    @endpush
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit') }}
    </h2>
    <x-proposal.edit :proposal="$proposal"/>
    @if(count($proposal->files ?? []) && ($proposal->trashed() || $proposal->isRevision()))
        <h1 class="h4 mt-4 text-gray-800">{{__('Files')}}</h1>
        <div class="mt-3">
            <div class="list-group w-100 max-w-7xl mx-auto">
                @foreach ($proposal->files as $file)
                    <div class="list-group-item list-group-item-action d-flex justify-content-between flex-column">
                        <a class='btn btn-link overflow-x-hidden'
                           target='_blank'
                           href='{{route('readFile', ['path' => $file])}}'>{{str_replace($proposal::UPLOAD_FILE_PATH . '/', '', $file)}}</a>
                        <div class="btn-group btn-group-sm" role="group">
                            <a class='btn btn-primary'
                               target='file_view'
                               href='{{route('readFile', ['path' => $file])}}'><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                @endforeach
                <h2 class="h4 text-gray-800 mt-4">{{__("View file")}}</h2>
                <div class="iframe-container mt-1 border-2 border-gray-300 rounded">
                    <iframe class="responsive-iframe" name="file_view">
                        <p>iframes are not supported by your browser.</p>
                    </iframe>
                </div>
            </div>
        </div>
    @endif
@endsection
