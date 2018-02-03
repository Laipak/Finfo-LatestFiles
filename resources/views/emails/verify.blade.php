<h1>Account Verification</h1>
<p>
Dear, {{$data['first_name']}}
<br>
Welcome to FINFO, your account has been created. Click the link below to verify your email address. Email validation is required to access FINFO backend feature.
</p>
<p>
    <strong>
    > <a href="{{$link}}">Verify Email Address</a>
    </strong>
</p>
<p>
    <strong>Below are your login information:</strong><br>
    <strong>Address: </strong><a href="{{$login}}">{{$login}}</a><br>
    <strong>User: </strong> {{$value['email_address']}}<br>
    <strong>Password: </strong> {{$value['password']}}
</p>
