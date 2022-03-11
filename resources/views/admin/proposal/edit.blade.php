@extends('layouts.admin')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{__('Edit')}}</h1>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                                    <textarea id="notice" class="block mt-1 w-full" type="text" name="notice"
                                              x-bind:value="formData.notice"
                                              x-model="formData.notice"></textarea>
                                </fieldset>
                                <template x-if="formData.status === '{{\App\Constants\Status::APPROVED}}'">
                                    <div class="mt-3">
                                        <x-label class="font-bold text-lg" for="bonus"
                                                 :value="__('Commission')"/>
                                        <x-input id="bonus" class="block mt-1 w-full"
                                                 type="number" name="commission" required
                                                 x-bind:value="formData.commission" step=".01" min="1"
                                                 x-model.number="formData.commission"/>
                                    </div>
                                </template>
                                <div class="mt-3"
                                     x-show="formData.status === '{{\App\Constants\Status::APPROVED}}'">
                                    <x-label class="font-bold text-lg" for="bonus"
                                             :value="__('Bonus')"/>
                                    <x-input id="bonus" class="block mt-1 w-full"
                                             type="number" name="bonus" step=".01"
                                             x-bind:value="formData.bonus" min="0"
                                             x-model.number="formData.bonus"/>
                                </div>


                            </div>
                        </fieldset>

                        <hr>
                    </x-proposal.form>
                </div>
            </div>
        </div>
    </div>
@endsection

