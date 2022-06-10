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
    <h1 class="h3 mb-4 text-gray-800">{{__('Edit')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-proposal.form :form-data="$formData" :action="route('admin.proposals.update',[$proposal->id])"
                                 :method="'PUT'">
                    <fieldset class="mt-3">
                        <legend>{{__('Action')}}</legend>
                        <div>
                            <x-label class="font-bold text-lg" for="status" :value="__('Status')"/>
                            <x-select id="status" required
                                      class="block mt-1 w-full" name="status"
                                      x-model="formData.status">
                                @foreach([
                                    \App\Constants\Status::PENDING,
                                    \App\Constants\Status::REVISION,
                                    \App\Constants\Status::DENIED,
                                    \App\Constants\Status::APPROVED
                                    ] as $status)
                                    <option :selected="formData.status === '{{$status}}'"
                                            value="{{$status}}">{{trans("status.$status")}}</option>
                                @endforeach
                            </x-select>
                            <fieldset class="mt-3"
                                      x-show="formData.status === '{{\App\Constants\Status::REVISION}}'"
                                      x-transition.scale.origin.bottom
                                      x-transition:leave.scale.origin.top>
                                <legend>{{__('Notice')}}</legend>
                                <ul class="list-group mb-3 mt-1" id="notice-lists" x-show="formData.notices.length">
                                    <template x-for="notice in formData.notices">
                                        <li class="list-group-item"
                                            x-bind:class="(notice.status === '{{\App\Constants\Status::APPROVED}}'?'list-group-item-success':'list-group-item-danger')">
                                            <b x-text="notice.message"></b>: <span x-text="notice.created_at"></span>
                                        </li>
                                    </template>
                                </ul>
                                <textarea id="notice" rows="7"
                                          class="block mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                                          type="text" name="notice"></textarea>
                                <button @click="sendNotice" type="button"
                                        class="btn btn-outline-primary mt-3 float-right">{{__('Send')}}</button>

                            </fieldset>
                            <template x-if="formData.status === '{{\App\Constants\Status::APPROVED}}'">
                                <div class="smt-3">
                                    <x-label class="font-bold text-lg" for="commission"
                                             :value="__('Commission')"/>
                                    <x-input id="commission" class="block mt-1 w-full"
                                             type="number" name="commission" required
                                             x-bind:value="formData.commission"
                                             step=".01" min="0" max="100"
                                             x-model.number="formData.commission"/>
                                </div>
                            </template>
                            <div class="mt-3"
                                 x-show="formData.status === '{{\App\Constants\Status::APPROVED}}'">
                                <x-label class="font-bold text-lg" for="bonus"
                                         :value="__('Bonus')"/>
                                <x-input id="bonus" class="block mt-1 w-full"
                                         type="number" name="bonus"
                                         x-bind:value="formData.bonus"
                                         step=".01" min="0" max="100"
                                         x-model.number="formData.bonus"/>
                            </div>
                            <div class="mt-3">
                                <x-label class="font-bold text-lg" for="number"
                                         :value="__('Proposal number')"/>
                                <x-input id="number" class="block mt-1 w-full"
                                         type="text" name="number"
                                         x-bind:value="formData.number"
                                />
                            </div>
                            <div class="mt-3">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                       id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">
                                        {{__("Export")}}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item"
                                           href="{{route('admin.proposals.export', ['id'=>$proposal->id,'ext'=>'xlsx'])}}">Excel</a>
                                        <a class="dropdown-item"
                                           href="{{route('admin.proposals.export', ['id'=>$proposal->id,'ext'=>'pdf'])}}">Pdf</a>
                                        <a class="dropdown-item"
                                           href="{{route('admin.proposals.export', ['id'=>$proposal->id,'ext'=>'csv'])}}">Csv</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                </x-proposal.form>
            </div>
        </div>
    </div>
    @if(count($proposal->files ?? []))
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
                            <a class='btn btn-info'
                               href='{{route('admin.downloadFile', ['path' => $file])}}'><i class="fas fa-download"></i></a>
                        </div>
                    </div>
                @endforeach
                <div class="list-group-item list-group-item-action d-flex justify-content-between flex-column">
                    <a title="{{__('Download all files')}}" class='btn btn-sm btn-block btn-secondary'
                       href='{{route('admin.downloadZip', [$proposal->id])}}'><i
                            class="fas fa-file-archive"></i></a>
                </div>
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

