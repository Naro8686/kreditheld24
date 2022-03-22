@props(['action' => '#','method'=>'POST','formData'])

<form {!! $attributes !!}
      action="{{$action}}" id="proposal" method="{{Str::lower($method) === 'put' ? 'POST': $method}}"
      enctype="multipart/form-data"
      x-data="render({{$formData}})"
      @submit.prevent="submitData()"
>
    @csrf
    @if(Str::lower($method) === 'put')
        @method($method)
    @endif
    {{ $slot }}
    <h2 class="text-danger text-center" x-show="message" x-text="message"></h2>
    <div
        x-data="{showHide:showHideComment('{{trans("proposal.creditTypes.other")}}'), otherName:'{{trans("proposal.creditTypes.other")}}'}">
        <div>
            <x-label class="font-bold text-lg" for="parent_category" :value="__('Category')"/>
            <x-select id="parent_category" required x-on:change="showHide = showHideComment(otherName)"
                      class="block mt-1 w-full" name="parent_category_id"
                      x-model="formData.parent_category_id">
                <option value="">{{__('no selected')}}</option>
                <template x-for="parent_category in formData.parent_categories" :key="parent_category.id">
                    <option :selected="formData.parent_category_id === parent_category.id"
                            :value="parent_category.id" x-text="parent_category.name"/>
                </template>
            </x-select>
        </div>
        <div class="mt-3">
            <x-label class="font-bold text-lg" for="category" :value="__('Credit Type')"/>
            <x-select id="category" required
                      x-on:change="showHide = showHideComment(otherName)"
                      class="block mt-1 w-full" name="category_id"
                      x-model="formData.category_id">
                <option value="">{{__('no selected')}}</option>
                <template x-for="category in formData.categories[formData.parent_category_id]" :key="category.id">
                    <option :selected="formData.category_id === category.id"
                            :value="category.id" x-text="category.name"/>
                </template>
            </x-select>
            <fieldset class="mt-3" x-show="showHide"
                      x-transition.scale.origin.bottom
                      x-transition:leave.scale.origin.top>
                <legend>{{__('Comment')}}</legend>
                <x-input id="creditComment" class="block mt-1 w-full" type="text" name="creditComment"
                         :value="old('creditComment')"
                         x-model="formData.creditComment"/>
            </fieldset>
        </div>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="deadline" :value="__('For what time (month) ?')"/>
        <x-input id="deadline" class="block mt-1 w-full"
                 type="number" name="deadline" required
                 :value="old('deadline')" min="1" :placeholder="__('month')"
                 x-model.number="formData.deadline"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="monthlyPayment"
                 :value="__('Desired amount of payment per month ?')"/>
        <x-input id="monthlyPayment" class="block mt-1 w-full"
                 type="number" name="monthlyPayment" required step=".01"
                 :value="old('monthlyPayment')" min="1" x-bind:placeholder="currency"
                 x-model.number="formData.monthlyPayment"/>

    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="creditAmount" :value="__('Desired loan amount ?')"/>
        <x-input id="creditAmount" class="block mt-1 w-full"
                 type="number" name="creditAmount" required step=".01"
                 :value="old('creditAmount')" min="1" x-bind:placeholder="currency"
                 x-model.number="formData.creditAmount"/>

    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="firstName" :value="__('Name')"/>
        <x-input id="firstName" class="block mt-1 w-full"
                 type="text" name="firstName" required
                 :value="old('firstName')" x-model="formData.firstName"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="lastName" :value="__('Surname')"/>
        <x-input id="lastName" class="block mt-1 w-full"
                 type="text" name="lastName" required
                 :value="old('lastName')" x-model="formData.lastName"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="street" :value="__('Street')"/>
        <x-input id="street" class="block mt-1 w-full"
                 type="text" name="street" required
                 :value="old('street')" x-model="formData.street"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="house" :value="__('House')"/>
        <x-input id="house" class="block mt-1 w-full"
                 type="text" name="house" required
                 :value="old('house')" x-model="formData.house"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="postcode" :value="__('Postcode')"/>
        <x-input id="postcode" class="block mt-1 w-full"
                 type="text" name="postcode" required
                 :value="old('postcode')" x-model="formData.postcode"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="city" :value="__('City')"/>
        <x-input id="city" class="block mt-1 w-full"
                 type="text" name="city" required
                 :value="old('city')" x-model="formData.city"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="residenceDate" :value="__('residence Date')"/>
        <x-input id="residenceDate" class="block mt-1 w-full"
                 type="date" name="residenceDate" required
                 :value="old('residenceDate')" x-model="formData.residenceDate"/>
        <template x-if="lessTwoYears()">
            <fieldset class="mt-3"
                      x-transition.scale.origin.bottom
                      x-transition:leave.scale.origin.top>
                <legend>{{__('Old Address')}}</legend>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="old-street" :value="__('Street')"/>
                    <x-input id="old-street" class="block mt-1 w-full"
                             type="text" name="oldAddress[street]" required
                             :value="old('oldAddress.street')" x-model="formData.oldAddress.street"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="old-house" :value="__('House')"/>
                    <x-input id="old-house" class="block mt-1 w-full"
                             type="text" name="oldAddress[house]" required
                             :value="old('oldAddress.house')" x-model="formData.oldAddress.house"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="old-postcode" :value="__('Postcode')"/>
                    <x-input id="old-postcode" class="block mt-1 w-full"
                             type="text" name="oldAddress[postcode]" required
                             :value="old('oldAddress.postcode')"
                             x-model="formData.oldAddress.postcode"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="old-city" :value="__('City')"/>
                    <x-input id="old-city" class="block mt-1 w-full"
                             type="text" name="oldAddress[city]" required
                             :value="old('oldAddress.city')" x-model="formData.oldAddress.city"/>
                </div>
            </fieldset>
        </template>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="birthday" :value="__('Birthday')"/>
        <x-input id="birthday" class="block mt-1 w-full"
                 type="date" name="birthday" required
                 :value="old('birthday')" x-model="formData.birthday"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="phoneNumber" :value="__('Phone Number')"/>
        <x-input id="phoneNumber" class="block mt-1 w-full"
                 type="tel" name="phoneNumber" required
                 :value="old('phoneNumber')" x-model="formData.phoneNumber"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="email" :value="__('Email')"/>
        <x-input id="email" class="block mt-1 w-full"
                 type="email" name="email" required
                 :value="old('email')" x-model="formData.email"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="birthplace" :value="__('Birthplace')"/>
        <x-input id="birthplace" class="block mt-1 w-full"
                 type="text" name="birthplace" required
                 :value="old('birthplace')" x-model="formData.birthplace"/>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="residenceType" :value="__('residence Type')"/>
        <x-select id="residenceType" required
                  class="block mt-1 w-full" name="residenceType"
                  x-model="formData.residenceType">
            <option value="">{{__('no selected')}}</option>
            @foreach(\App\Models\Proposal::$residenceTypes as $key => $residenceType)
                <option :selected="formData.residenceType === '{{$residenceType}}'"
                        value="{{$residenceType}}">{{trans("proposal.residenceTypes.$residenceType")}}</option>
            @endforeach
        </x-select>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="familyStatus" :value="__('Family status')"/>
        <x-select id="familyStatus" required
                  class="block mt-1 w-full" name="familyStatus"
                  x-model="formData.familyStatus">
            <option value="">{{__('no selected')}}</option>
            @foreach(\App\Models\Proposal::$familyStatuses as $key => $familyStatus)
                <option :selected="formData.familyStatus === '{{$familyStatus}}'"
                        value="{{$familyStatus}}">{{trans("proposal.familyStatuses.$familyStatus")}}</option>
            @endforeach
        </x-select>
        <template x-if="formData.familyStatus === 'married'">
            <fieldset class="mt-3"
                      x-transition.scale.origin.bottom
                      x-transition:leave.scale.origin.top>
                <legend>{{__('Spouse details')}}</legend>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="spouse-firstName" :value="__('Name')"/>
                    <x-input id="spouse-firstName" class="block mt-1 w-full"
                             type="text" name="spouse[firstName]" required
                             :value="old('spouse.firstName')" x-model="formData.spouse.firstName"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="spouse-lastName" :value="__('Surname')"/>
                    <x-input id="spouse-lastName" class="block mt-1 w-full"
                             type="text" name="spouse[lastName]" required
                             :value="old('spouse.lastName')" x-model="formData.spouse.lastName"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="spouse-birthday" :value="__('Birthday')"/>
                    <x-input id="spouse-birthday" class="block mt-1 w-full"
                             type="date" name="spouse[birthday]" required
                             :value="old('spouse.birthday')" x-model="formData.spouse.birthday"/>
                </div>
                <div class="mt-3">
                    <x-label class="font-bold text-lg" for="spouse-birthplace"
                             :value="__('Birthplace')"/>
                    <x-input id="spouse-birthplace" class="block mt-1 w-full"
                             type="text" name="spouse[birthplace]" required
                             :value="old('spouse.birthplace')" x-model="formData.spouse.birthplace"/>
                </div>
            </fieldset>
        </template>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" :value="__('Insurance')"/>
        <div class="flex items-center mt-1">
            <x-input class="mr-2" id="unemployment" name="insurance[unemployment]"
                     x-model="formData.insurance.unemployment"
                     type="checkbox"/>
            <x-label class="mr-2" for="unemployment" :value="__('Unemployment')"/>

            <x-input class="mr-2" id="disease" name="insurance[disease]"
                     x-model="formData.insurance.disease"
                     type="checkbox"/>
            <x-label class="mr-2" for="disease" :value="__('Disease')"/>

            <x-input class="mr-2" id="death" name="insurance[death]" x-model="formData.insurance.death"
                     type="checkbox"/>
            <x-label class="mr-2" for="death" :value="__('Death')"/>
        </div>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" for="otherCreditCount"
                 :value="__('Number of existing loans')"/>
        <x-input id="otherCreditCount" class="block mt-1 w-full" placeholder="{{__('quantity')}}"
                 type="number" required min="0" max="4"
                 name="otherCreditCount"
                 x-model.number="otherCreditCount"
                 x-on:keyup="createdOtherCreditField(otherCreditCount)"/>
        <template x-if="otherCreditCount > 0">
            <fieldset class="mt-3">
                <legend>{{__('Other credits')}}</legend>
                <template x-for="(otherCredit,i) in formData.otherCredit" :key="i">
                    <div class="mt-3">
                        <x-label class="font-bold text-base block mb-3"
                                 x-text="(i + 1) + '. {{__('Credit')}}'"/>
                        <div class="flex flex-wrap">
                            <div class="flex-auto sm:mr-3">
                                <x-label :value="__('Monthly Payment')"/>
                                <x-input class="block mt-1 w-full" x-bind:placeholder="currency"
                                         type="text" required
                                         x-bind:name="'otherCredit['+i+'][monthlyPayment]'"
                                         x-model="otherCredit.monthlyPayment"
                                />
                            </div>
                            <div class="flex-auto">
                                <x-label :value="__('Credit balance')"/>
                                <x-input class="block mt-1 w-full" x-bind:placeholder="currency"
                                         type="text" required
                                         x-bind:name="'otherCredit['+i+'][creditBalance]'"
                                         x-model="otherCredit.creditBalance"
                                />
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-label class="font-bold text-base" :value="__('Repay a credit ?')"/>
                            <div class="flex flex-wrap">
                                <div
                                    class="flex basis-full sm:basis-1/2 items-center justify-center sm:justify-start mt-3 mb-3">
                                    <div class="mr-8">
                                        <x-label class="mr-2" x-bind:for="`repay_yes_${i}`"
                                                 :value="__('Yes')"/>
                                        <x-input class="block mt-1 mr-3"
                                                 type="radio"
                                                 x-bind:name="'otherCredit['+i+'][repay]'"
                                                 value="yes"
                                                 x-bind:id="`repay_yes_${i}`"
                                                 x-model="otherCredit.repay"
                                        />

                                    </div>
                                    <div>
                                        <x-label class="mr-2" x-bind:for="`repay_no_${i}`"
                                                 :value="__('No')"/>
                                        <x-input class="block mt-1"
                                                 type="radio"
                                                 x-bind:name="'otherCredit['+i+'][repay]'"
                                                 value="no"
                                                 @click="otherCredit.bankNumber = ''"
                                                 x-bind:id="`repay_no_${i}`"
                                                 x-model="otherCredit.repay"
                                        />
                                    </div>
                                </div>
                                <div class="basis-full sm:basis-1/2 md:pl-2"
                                     x-show="otherCredit.repay === 'yes'"
                                     x-transition>
                                    <x-label :value="__('Bank number')"/>
                                    <x-input class="block mt-1 w-full"
                                             type="text" x-bind:required="otherCredit.repay === 'yes'"
                                             x-bind:name="'otherCredit['+i+'][bankNumber]'"
                                             x-model="otherCredit.bankNumber"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </fieldset>
        </template>
    </div>
    <div class="mt-3">
        <x-label class="font-bold text-lg" :value="__('Upload file')"/>
        <template x-for="(name,i) in allFilesName" :key="i">
            <label
                x-bind:class="dropFile ? 'bg-gray-400' : ''"
                x-on:drop="dropFile = false"
                x-on:drop.prevent="handleFileDrop($event,i)"
                x-on:dragover.prevent="dropFile = true"
                x-on:dragleave.prevent="dropFile = false"
                class="overflow-x-hidden relative border-2 border-gray-300 p-3 w-full block rounded cursor-pointer my-2">
                <input type="file" class="sr-only file"
                       x-bind:required="!name"
                       x-on:change="uploadFile($event.target.files[0],i)"
                />
                <button type="button" :disabled="allFilesName.length <= 1"
                        class="absolute right-0 top-0 mt-2 mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                        @click="deleteFile(i)">
                    -
                </button>
                <span x-text="name || '{{__("Choose File")}}'"></span>
            </label>
        </template>

        <button type="button"
                @click="addFileField(allFilesName.length)"
                class="mt-2 mr-2 bg-gray-500
                                hover:bg-gray-700 text-white
                                font-bold py-1 px-3 rounded
                                float-right"
        >
            +
        </button>
    </div>
    @isset($footer)
        {{ $footer }}
    @endisset
    <button type="submit" :disabled="loading"
            x-text="btnText || '{{__("Send")}}'"
            class="mt-6 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full"/>
</form>
@push('js')
    <script src="{{ asset('js/proposal.js') }}"></script>
@endpush
