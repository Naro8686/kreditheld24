@extends('layouts.admin')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{__('Contacts')}}</h2>
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
                                        {{__('N')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Name')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Surname')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Email')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Phone Number')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        <span class="sr-only">{{__('Action')}}</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contacts as $contact)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{$loop->iteration}}
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap">
                                            {{$contact->lastName}}
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap">
                                            {{$contact->lastName}}
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                            <a class="background-transparent font-bold outline-none focus:outline-none ease-linear transition-all duration-150"
                                               href="mailto:{{$contact->email}}">{{$contact->email}}</a>
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                            @if($contact->phone)
                                                <a class="background-transparent font-bold outline-none focus:outline-none ease-linear transition-all duration-150"
                                                   href="tel:{{$contact->phone}}">{{$contact->phone}}</a>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                            <a class="btn btn-sm btn-info mr-1"
                                               href="{{route('admin.contacts.edit',[$contact->id])}}"><i
                                                    class="fas fa-edit"></i></a>
                                            <button type='button' class='btn btn-sm btn-danger'
                                                    data-toggle='modal'
                                                    data-target='#confirmModal'
                                                    data-url='{{route('admin.contacts.destroy',[$contact->id])}}'>
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
                {!! $contacts->links() !!}
            </div>
        </div>
    </div>

@endsection

