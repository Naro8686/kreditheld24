@push('css')
    <style>
        #statistics > div > div {
            min-height: 80px;
        }
    </style>
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposals') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="px-2 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="statistics" class="flex flex-wrap -mx-2">
                <div class="sm:w-1/3 w-full px-2 mb-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-1 font-bold tracking-tight text-gray-900 dark:text-white">
                            {{__('TOTAL VOLUME (MONTH)')}}
                        </h2>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{$monthSum.' '.\App\Models\Proposal::CURRENCY}}</p>
                    </div>
                </div>
                <div class="sm:w-1/3 w-full px-2 mb-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-1 font-bold tracking-tight text-gray-900 dark:text-white">
                        {{__('TOTAL VOLUME (YEAR)')}}</h2>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{$totalSum.' '.\App\Models\Proposal::CURRENCY}}</p>
                    </div>
                </div>
                <div class="sm:w-1/3 w-full px-2">
                    <div
                        class="p-3 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <div class="mb-4 font-bold tracking-tight text-gray-900 dark:text-white">
                            <div class="flex justify-between mb-1">
                                <h2 class="font-bold tracking-tight text-gray-900 dark:text-white">{{__('TARGET')}}</h2>
                                <span
                                    class="text-sm font-medium text-blue-700 dark:text-white">{{$targetPercent}}%</span>
                            </div>
                        </div>
                        <div class="font-normal text-gray-700 dark:text-gray-400">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{$targetPercent}}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="px-2 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        {{__('Id')}}
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
                                    <th scope="col" class="relative py-3 px-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($proposals as $proposal)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{$proposal->id}}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                            {{$proposal->creditAmount.' '.$proposal::CURRENCY}}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                            {{$proposal->created_at}}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                            {{$proposal->payoutAmount.' '.$proposal::CURRENCY}}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                            {{trans("status.$proposal->status")}}
                                        </td>
                                        <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap"
                                            x-data="{show:{{(int)($proposal->status === \App\Constants\Status::REVISION)}}}">
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
</x-app-layout>
