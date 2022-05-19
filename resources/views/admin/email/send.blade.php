@extends('layouts.admin')

@section('content')
    @include('admin.includes.email_message')
    @auth
        @include('email-templates.templates-list', ['templates' => auth()->user()->emailTemplates])
    @endauth
@endsection
