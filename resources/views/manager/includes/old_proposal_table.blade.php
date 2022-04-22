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
                                    {{__('Proposal number')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Sum')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Date')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Payout amount')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Status')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Full Name')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Phone Number')}}
                                </th>
                                <th scope="col"
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                    {{__('Email')}}
                                </th>
                                <th scope="col" class="relative py-3 px-6">
                                    <span class="sr-only">{{__('Edit')}}</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($proposals as $proposal)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$proposal->number}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        {{$proposal->creditAmount.' '.$proposal::CURRENCY}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        {{$proposal->created_at}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        {{$proposal->payoutAmount.' '.$proposal::CURRENCY}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400 {{$proposal->statusBgColor()}}">
                                        {{trans("status.$proposal->status")}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        {{"$proposal->firstName $proposal->lastName"}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        {{$proposal->phoneNumber}}
                                    </td>
                                    <td class="py-4 px-6 text-sm  whitespace-nowrap dark:text-gray-400">
                                        <a class="background-transparent font-bold outline-none focus:outline-none ease-linear transition-all duration-150"
                                           href="mailto:{{$proposal->email}}">{{$proposal->email}}</a>
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap"
                                        x-data="{show:parseInt('{{(int)($proposal->status === \App\Constants\Status::REVISION) || (int)$proposal->trashed()}}')}">
                                        <a x-show="show" href="{{route('proposal.edit',[$proposal->id])}}"
                                           class="text-blue-600 dark:text-blue-500 hover:underline disabled:opacity-50">{{__('Edit')}}</a>
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
