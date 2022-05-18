@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Managers')}}</h1>
    <div>
        <a class="btn btn-outline-success" href="{{route('admin.managers.create')}}">{{__('Add')}}</a>
    </div>
    <div class="py-6">
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
                                    {{__('Name')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Surname')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Address')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Phone')}}
                                </th>

                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Card Number')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Tax number')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase">
                                    {{__('Birthday')}}
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
                                        <div>
                                            <a href="{{route('admin.email.index',['type' => 'manager', 'email' => $manager->email])}}">{{$manager->email}}</a>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->name}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->surname}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{"$manager->city, $manager->street, $manager->house, $manager->postcode"}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->phone}}
                                    </td>

                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->card_number}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->tax_number}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{optional($manager->birthday)->format('d.m.Y')}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{optional($manager->created_at)->format('d.m.Y H:i:s')}}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                        {{$manager->proposals_count}}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                        <a class="btn btn-sm btn-info mr-1" href="{{route('admin.managers.edit',[$manager->id])}}"><i class="fas fa-user-edit"></i></a>
                                        <button type='button' class='btn btn-sm btn-danger'
                                                data-toggle='modal'
                                                data-target='#confirmModal'
                                                data-url='{{route('admin.managers.delete',[$manager->id])}}'>
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
            {!! $managers->links() !!}
        </div>
    </div>
@endsection
