@extends('email')
@section('title')
{{$subject}}
@endsection
@section('content')
<p class="h2">Hello, {{$user->first_name}} {{$user->last_name}}</p>
<p>Thank you for opening your {{$companyName}} account with us.</p>
<p>Please verify your email address by clicking on the button below to start selling your products.</p>

<p>
    <a href="{{$activationUrl}}" 
       target="_blank"  
       style="font-size: 16px;
       color: #fff;
       padding: 10px 20px;
       border-radius: 3px;
       display: inline-block;
       text-decoration: none;
       background-color: #33adff;">
        Activate Your Account
    </a>
</p>

<p>Or copy the following URL and paste it in your browser.</p>
<p><a href="{{$activationUrl}}"  target="_blank">{{$activationUrl}}</a></p>
<p><b>Important:</b> If your e-mail is not verified within 7 days, your {{$companyName}} account will be deactivated automatically.</p>
<p>
    <span>Your current password is <b>manager</b>: Please login using the email below and change your password before continuing.</span><br />
    <span>E-mail:	{{$user->email}}</span>
</p>
<p>Warm Regards</p>
@endsection

