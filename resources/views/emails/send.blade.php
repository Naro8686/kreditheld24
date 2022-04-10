@component('mail::message')
    @if(isset($data) && !empty($data) && isset($data['fullName']))
        {{__('Hello').' '.$data['fullName']}}
        <hr/>
    @endif
    {!! $message !!}
@endcomponent
