<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <x-proposal.form :form-data="$formData" :action="route('proposal.store')" :method="'POST'">
                <x-slot name="footer">
                    <hr class="mt-12"/>
                    <div class="mt-6" x-data="{agree:{privacy_policy:false,personal_data:false}}">
                        <x-label class="font-bold text-lg" :value="__('I agree')"/>
                        <div class="flex items-center mt-1">
                            <x-input class="mr-2" id="privacy_policy"
                                     x-model="agree.privacy_policy"
                                     type="checkbox" required
                                     name="agree[privacy_policy]"/>
                            <x-label class="mr-2" for="privacy_policy" :value="__('privacy policy')"/>

                            <x-input class="mr-2" id="personal_data"
                                     x-model="agree.personal_data"
                                     type="checkbox" required
                                     name="agree[personal_data]"/>
                            <x-label class="mr-2" for="personal_data" :value="__('personal data')"/>
                        </div>
                    </div>
                </x-slot>
            </x-proposal.form>
        </div>
    </div>
</div>
