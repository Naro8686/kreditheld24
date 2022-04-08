@props(['proposal'])
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <x-proposal.form :form-data="$formData" :action="route('proposal.update',[$proposal->id])"
                             :method="'PUT'">
                <h1 class="font-bold text-lg text-danger text-center"
                    x-show="formData.status === '{{\App\Constants\Status::REVISION}}' && formData.notice"
                    x-text="formData.notice"></h1>
            </x-proposal.form>
        </div>
    </div>
</div>
