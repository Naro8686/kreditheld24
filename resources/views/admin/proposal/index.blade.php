@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Proposals')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-hidden shadow-md sm:rounded-lg">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Id')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Manager')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Sum')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Date')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Status')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Files')}}
                                            </th>
                                            <th scope="col" class="relative py-3 px-6">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($proposals as $proposal)
                                            <tr class="bg-white border-b">
                                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    {{$proposal->id}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$proposal->user->name ?? $proposal->user->email}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$proposal->creditAmount.' '.$proposal::CURRENCY}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$proposal->created_at}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{trans("status.$proposal->status")}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    <ul class="list-group btn-group btn-group-sm" role="group"
                                                        aria-label="Basic example">
                                                        @foreach($proposal->files as $file)
                                                            <li>
                                                                <a class="btn btn-sm btn-link" target="_blank"
                                                                   href="{{route('admin.readFile',['path'=>$file])}}">{{str_replace($proposal::UPLOAD_FILE_PATH.'/','',$file)}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                                    <a href="{{route('admin.proposals.edit',[$proposal->id])}}"
                                                       class="btn btn-sm btn-primary mr-1">
                                                        <i class='fa fa-eye'></i>
                                                    </a>
                                                    <button type='button' class='btn btn-sm btn-danger'
                                                            data-toggle='modal'
                                                            data-target='#confirmModal'
                                                            data-url='{{route('admin.proposals.delete',[$proposal->id])}}'>
                                                        <i class='fa fa-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        {!! $proposals->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
