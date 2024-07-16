@extends('layouts.app')
@section('content')
<p>Hi {{ Auth::user()->name; }},</p>
<p>We are happy you register. To start exploring the app, please confirm your email address.</p>
<a href="/">Resend verification email</a>
@endsection
