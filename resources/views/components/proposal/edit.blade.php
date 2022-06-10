@props(['proposal'])
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <x-proposal.form :form-data="$formData" :action="route('proposal.update',[$proposal->id])"
                             :method="'PUT'">
                <template x-if="formData.status === '{{\App\Constants\Status::REVISION}}'">
                    <div>
                        <h1 class="font-bold text-lg text-danger text-center"
                            x-show="formData.notice" x-text="formData.notice"></h1>
                        <p x-show="formData.revision_at" class="text-yellow-500"
                           x-text="'{{__("Date for revision")}}: ' + formData.revision_at"></p>
                        <ul class="list-group mb-3 mt-1" id="notice-lists" x-show="formData.notices.length">
                            <template x-for="notice in formData.notices">
                                <li class="list-group-item" x-bind:class="(notice.status === '{{\App\Constants\Status::APPROVED}}'?'list-group-item-success':'list-group-item-danger')">
                                    <b x-text="notice.message"></b>: <span x-text="notice.created_at"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
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
