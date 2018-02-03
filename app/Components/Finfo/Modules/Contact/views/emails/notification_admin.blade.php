<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us</title>
<style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Droid+Sans);
</style>
</head>
<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
    <p>Dear Admin,</p>
    <p>Subject: {{$userData['name']}}</p>
    <p>Email: {{$userData['email']}}</p>
    <p>Message:</p>
    <p>{{$userData['message']}}</p>
    <p>Best Regards,</p>
    <p>{{$userData['name']}}</p>
</body>
</html>