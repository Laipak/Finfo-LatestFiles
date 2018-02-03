<p>

Hi {{$value['first_name']}},
<br>
Thanks for signing up to FINFO. It's great to have you on board!
</p>
<p>
We just wanted to drop you a line and welcome you to our global network of investors.
</p>
<p>
We set this site up to help organisations like you to automate your Investor Relations
communications.
</p>
<p>
    Please click on the link below to verify your account.<br>
    <strong>
    > <a href="{{$link}}">Verify Email Address</a>
    </strong>
</p>

@if(isset($link_payment))
<p>
	<strong><a href="{{$link_payment}}">Payment link</a></strong>
</p>
@endif

<p>
Give us a while to verify your company details and we’ll be in touch shortly.
</p>
<p>All the best!<br>
John, Muhd and Tim,<br>
Founders<br>
FINFO
</p>
