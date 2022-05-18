@component('mail::message')
    @if(isset($data) && !empty($data) && isset($data['fullName']))
        {{__('Hello').' '.$data['fullName']}}
        <hr/>
    @endif
    {!! $message !!}
    @if(isset($data) && !empty($data) && isset($data['url']))
        @component('mail::button', ['url' => $data['url']])
            {{__('Link')}}
        @endcomponent
    @endif
@endcomponent
