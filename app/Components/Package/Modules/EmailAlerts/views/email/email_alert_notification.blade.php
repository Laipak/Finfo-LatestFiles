<h3>Hi {{(isset($name)? $name: '')}},</h3>

@if(isset($data['financialHighlights']))
	<h4>New Latest Financial Highlight Created</h4>
	<a href="{{$data['financialHighlights']}}">{{$data['financialHighlights']}}</a>
@endif

@if(isset($data['annual_report']))
	<h4>New Annual Reports Created</h4>
	<a href="{{$data['annual_report']}}">{{$data['annual_report']}}</a>
@endif

@if(isset($data['press_release']))
	<h4>New Press Release Created</h4>
	<a href="{{$data['press_release']}}">{{$data['press_release']}}</a>
@endif

@if(isset($data['finan_result']))
	<h4>New Financial Results Created</h4>
	<a href="{{$data['finan_result']}}">{{$data['finan_result']}}</a>
@endif

@if(isset($data['webcast']))
	<h4>New Webcast Created</h4>
	<a href="{{$data['webcast']}}">{{$data['webcast']}}</a>
@endif

@if(isset($data['presentation']))
	<h4>New Presentations Created</h4>
	<a href="{{$data['presentation']}}">{{$data['presentation']}}</a>
@endif

@if(isset($data['announment']))
	<h4>New Announment Created</h4>
	<a href="{{$data['announment']}}">{{$data['announment']}}</a>
@endif

@if(isset($data['calendar']))
	<h4>New Investor Relations Calendar Created</h4>
	<a href="{{$data['calendar']}}">{{$data['calendar']}}</a>
@endif


<br> <br>
Thanks,<br> <br>
{{(isset($company->company_name)? $company->company_name : "")}} Team
