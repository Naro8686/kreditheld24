@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Managers')}}</h1>
    <div>
        <a class="btn btn-outline-success" href="{{route('admin.managers.create')}}">{{__('Add')}}</a>
    </div>
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
                                                {{__('Email')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Card Number')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Date')}}
                                            </th>
                                            <th scope="col"
                                                class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                                {{__('Number of applications')}}
                                            </th>
                                            <th scope="col" class="relative py-3 px-6">
                                                <span class="sr-only">{{__('Action')}}</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($managers as $manager)
                                            <tr class="bg-white border-b">
                                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    {{$manager->id}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$manager->email}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$manager->card_number}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$manager->created_at}}
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    {{$manager->proposals_count}}
                                                </td>
                                                <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                                    <button type='button' class='btn btn-sm btn-danger mr-1' data-toggle='modal'
                                                            data-target='#confirmModal'
                                                            data-url='{{route('admin.managers.delete',[$manager->id])}}'><i class='fa fa-trash'></i>
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
                        {!! $managers->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
