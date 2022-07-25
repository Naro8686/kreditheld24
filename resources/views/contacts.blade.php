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
                                        {{__('Contact Vorname')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Contact Name')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Contact Email')}}
                                    </th>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Contact Telefonnummer')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contacts as $contact)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                            {{$contact->firstName}}
                                        </td>
                                        <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
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

