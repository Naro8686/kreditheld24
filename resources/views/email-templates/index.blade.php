@extends('layouts.admin')

@section('content')
    <div class="py-2">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Name')}}
                                    </th>
                                    <th scope="col" class="relative py-3 px-6">
                                        <span class="sr-only">{{__('Edit')}}</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($templates as $template)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{$template->name}}
                                        </td>

                                        <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="d-flex justify-content-end" role="group">
                                                <a
                                                    href="{{route('email-templates.edit',[$template->id])}}"
                                                    type="button"
                                                    class="btn btn-sm btn-info mr-1 edit-link">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger mr-1"
                                                        data-toggle="modal" data-target="#confirmModal"
                                                        data-url="{{route('email-templates.destroy',[$template->id])}}">
                                                    <i
                                                        class="fa fa-trash"></i>
                                                </button>
                                            </div>
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
                {!! $templates->links() !!}
            </div>
        </div>
    </div>

@endsection
