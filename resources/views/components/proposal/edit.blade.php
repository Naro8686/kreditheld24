@props(['proposal'])
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <x-proposal.form :form-data="$formData" :action="route('proposal.update',[$proposal->id])"
                             :method="'PUT'">
                <x-slot name="card">
                    <div class="card"
                         x-data="{ collapse_id: $id('collapseProposal'),heading_id:$id('headingProposal') }">
                        <div class="card-header">
                            <a data-toggle="collapse" data-parent="#accordionProposal" x-bind:href="'#'+collapse_id"
                               class="card-link capitalize" aria-expanded="true">
                                {{__('Notice')}}
                            </a>
                        </div>

                        <div x-bind:id="collapse_id" class="collapse show">
                            <div class="card-body">
                                <div class="grid grid-cols-1 gap-1">
                                    <div class="col-span-1 md:col-span-1">
                                        <ul class="list-group mb-3 mt-1" id="notice-lists"
                                            x-show="formData.notices.length">
                                            <template x-for="notice in formData.notices">
                                                <li class="list-group-item"
                                                    x-bind:class="{'text-right':formData.auth_id === notice.user_id}">
                                                    <b x-text="notice.message"></b>: <span
                                                        x-text="notice.created_at"></span>
                                                </li>
                                            </template>
                                        </ul>
                                        <textarea id="notices-message" rows="7"
                                                  class="block mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                                                  type="text" name="notices-message"></textarea>
                                        <button @click="sendNotice" type="button"
                                                class="btn btn-sm btn-outline-primary mt-3 float-right">{{__('Send Revision')}}</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
                <p x-show="formData.revision_at" class="text-yellow-500"
                   x-text="'{{__("Date for revision")}}: ' + formData.revision_at"></p>
                <template x-if="formData.status === '{{\App\Constants\Status::DENIED}}'">
                    <p x-show="formData.denied_at" class="text-red-500"
                       x-text="'{{__("Application rejection date")}}: ' + formData.denied_at"></p>
                </template>
                <template x-if="formData.status === '{{\App\Constants\Status::APPROVED}}'">
                    <p x-show="formData.approved_at" class="text-green-500"
                       x-text="'{{__("Application acceptance date")}}: ' + formData.approved_at"></p>
                </template>
            </x-proposal.form>
        </div>
    </div>
</div>
