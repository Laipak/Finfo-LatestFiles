@if(isset($route_preview))
<div >
<span style="font-size: 11px; color: #575757; line-height: 200%; font-family: arial; text-decoration: none;">Having trouble viewing this email? <a style="font-size: 11px; color: #575757; line-height: 200%; font-family: Arial; text-decoration: none; font-weight: bold;" href="{{$route_preview}}">View it in your browser.</a></span>
</div>
@endif
{!!$data['content']!!}
