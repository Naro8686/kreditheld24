<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{__('Proposal').' N'.$proposal->id}}</title>
    <style>
        body {
            margin: 5px 20px 5px;
            padding: 0;
            position: relative;
        }

        h3 {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
<div>
    <h3>{{ __('Proposal') }}</h3>
    <ul>
        <li>{{ __('Status').': '.__("status.{$proposal->status}") }}</li>
        <li>{{ __('Proposal number').': '.$proposal->number }}</li>
        <li>{{ __('Commission').': '.$proposal->commission }}</li>
        <li>{{ __('Bonus').': '.$proposal->bonus }}</li>
    </ul>
</div>
<div>
    <h3>{{ __('Category') }}</h3>
    <ul>
        <li>{{ optional($proposal->category)->parent->name ?? '' }}</li>
    </ul>
</div>
<div>
    <h3>{{ __('personal data') }}</h3>
    <ul>
        <li>{{ __('Gender').': '.__(Str::ucfirst($proposal->gender)) }}</li>
        <li>{{ __('Name').': '.$proposal->firstName }}</li>
        <li>{{ __('Surname').': '.$proposal->lastName }}</li>
        <li>{{ __('Phone Number').': '.$proposal->phoneNumber }}</li>
        <li>{{ __('Email').': '.$proposal->email }}</li>
        <li>{{ __('Birthday').': '.$proposal->birthday }}</li>
        <li>{{ __('Birthplace').': '.$proposal->birthplace }}</li>
        <li>{{ __('Family status').': '.$proposal->familyStatus }}</li>
        <li>
            {{ __('Spouse details') }}
            <ul>
                <li>{{ __('Name').': '. optional($proposal->spouse)['firstName'] }}</li>
                <li>{{ __('Surname').': '. optional($proposal->spouse)['lastName'] }}</li>
                <li>{{ __('Birthday').': '. optional($proposal->spouse)['birthday'] }}</li>
                <li>{{ __('Birthplace').': '. optional($proposal->spouse)['birthplace'] }}</li>
            </ul>
        </li>
        <li>{{ __('Count children').': '.$proposal->childrenCount }}</li>
    </ul>
</div>
<div>
    <h3>{{ __('Life situation') }}</h3>
    <ul>
        <li>{{ __('Street').': '.$proposal->street }}</li>
        <li>{{ __('House').': '.$proposal->house }}</li>
        <li>{{ __('Postcode').': '.$proposal->postcode }}</li>
        <li>{{ __('City').': '.$proposal->city }}</li>
        <li>
            {{ __('residence Type').': '.trans("proposal.residenceTypes.{$proposal->residenceType}")}}
            <ul>
                <li>{{ __('Rent').': '. $proposal->rentAmount }}</li>
                <li>{{ __('residence Date').': '. $proposal->residenceDate }}</li>
            </ul>
        </li>
        <li>
            {{ __('Old Address')}}
            <ul>
                <li>{{ __('Street').': '.optional($proposal->oldAddress)['street'] }}</li>
                <li>{{ __('House').': '.optional($proposal->oldAddress)['house'] }}</li>
                <li>{{ __('Postcode').': '.optional($proposal->oldAddress)['postcode'] }}</li>
                <li>{{ __('City').': '.optional($proposal->oldAddress)['city'] }}</li>
            </ul>
        </li>
    </ul>
</div>
<div>
    <h3>{{ __('Funding request') }}</h3>
    <ul>
        <li>
            {{ __('Credit Type').': '.optional($proposal->category)->name }}
            <ul>
                <li>{{ __('Comment').': '.$proposal->creditComment }}</li>
            </ul>
        </li>
        <li>
            {{ __('Desired loan amount ?').': '.$proposal->creditAmount }}
        </li>
        <li>
            {{ __('For what time (month) ?').': '.$proposal->deadline }}
        </li>
        <li>
            {{ __('Desired amount of payment per month ?').': '.$proposal->monthlyPayment }}
        </li>
        <li>
            {{ __('Number of existing loans').': '.count(($proposal->otherCredit ?? [])) }}
            <ul>
                @foreach(($proposal->otherCredit ?? []) as $credit)
                    <li>{{ __('Monthly Payment').': '.$credit['monthlyPayment'] }}</li>
                    <li>{{ __('Credit balance').': '.$credit['creditBalance'] }}</li>
                    <li>{{ __('Repay a credit ?').': '.__(Str::ucfirst($credit['repay'])) }}</li>
                    <li>{{ __('Bank number').': '.$credit['bankNumber'] }}</li>
                @endforeach
            </ul>
        </li>
        <li>
            {{ __('Insurance') }}
            <ul>
                <li>{{ __('Death').': '.(optional($proposal->insurance)['death'] ? __('Yes') : __('No')) }}</li>
                <li>{{ __('Disease').': '.(optional($proposal->insurance)['disease'] ? __('Yes') : __('No')) }}</li>
                <li>{{ __('Unemployment').': '.(optional($proposal->insurance)['unemployment'] ? __('Yes') : __('No')) }}</li>
            </ul>
        </li>
        <li>{{__('Type').': '.trans("proposal.applicantTypes.{$proposal->applicantType}")}}</li>
    </ul>
</div>
<div>
    <h3>{{ __('Object Data') }}</h3>
    <ul>
        <li>{{__('Street').': '.optional($proposal->objectData)['street']}}</li>
        <li>{{__('House').': '.optional($proposal->objectData)['house']}}</li>
        <li>{{__('Postcode').': '.optional($proposal->objectData)['postcode']}}</li>
        <li>{{__('City').': '.optional($proposal->objectData)['city']}}</li>
        <li>{{__('Object Type').': '.(optional($proposal->objectData)['objectType'] ? trans("proposal.objectTypes.".optional($proposal->objectData)['objectType']) : '')}}</li>
        <li>{{__('Year of construction').': '.optional($proposal->objectData)['yearConstruction']}}</li>
        <li>{{__('Year of repair').': '.optional($proposal->objectData)['yearRepair']}}</li>
        <li>{!! __('Plot size').' (m<sup>2</sup>):'.optional($proposal->objectData)['plotSize'] !!}</li>
        <li>{!! __('Living space').' (m<sup>2</sup>):'.optional($proposal->objectData)['livingSpace'] !!}</li>
        <li>{{__('Purchase or build price').': '.optional($proposal->objectData)['buildPrice']}}</li>
        <li>{{__('Own accumulation').': '.optional($proposal->objectData)['accumulation']}}</li>
        <li>{{__('Brokerage fees').'(%): '.optional($proposal->objectData)['brokerageFees']}}</li>
    </ul>
</div>
</body>
</html>
