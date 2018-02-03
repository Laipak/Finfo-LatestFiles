<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Confirmation</title>
<style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Droid+Sans);
</style>
</head>

<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
    <p>Hi {{$userData['user']['first_name']}} {{$userData['user']['last_name']}},</p>
    <p>This is a payment receipt for Invoice #{{$userData['invoice']['invoice_number']}} sent on {{ date('m/d/Y', strtotime($userData['invoice']['invoice_date']))}}.</p>
    <p>Your transaction by: {{ ($userData['invoice']['payment_method_id'] == 1)? 'eWay' : "securePay" }}</p>
    <p>Your payment method is: Credit Card (Single Invoice Payment)</p>
    <p>Invoice #{{$userData['invoice']['invoice_number']}}</p>
    <p>Amount Due: {{$userData['package']['package_price']}} {{$userData['currencyCode']}}</p>
    @if (isset($userData['invoice']['due_date']['date']))
        <p>Due Date: {{ date('m/d/Y', strtotime($userData['invoice']['due_date']['date']))}}</p>
    @else
        <p>Due Date: {{ date('m/d/Y', strtotime($userData['invoice']['due_date']))}}</p>
    @endif
    <p>Invoice Items:</p>
    <p>({{ucfirst($userData['package']['package_name'])}}) – <a href="https://{{Config::get('app.base_domain')}}">{{Config::get('app.base_domain')}}</a> 
    (Date: {{ date('m/d/Y', strtotime($userData['invoice']['invoice_date']))}} - {{ $userData['valid_until']}} Amount: {{$userData['package']['package_price']}} {{$userData['currencyCode']}}</p>
    <p>------------------------------------------------------</p>
    <p>Total: {{$userData['package']['package_price']}} {{$userData['currencyCode']}}</p>
    <p>------------------------------------------------------</p>
    <p>You may review your invoice history at any time by logging in to your client area.</p>
    <p>Note: This email will serve as an official receipt for this payment.</p><br/><br/>
    <p>Best Regards,</p>
    <p>FINFO Team</p>
</body>
</html>