@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="ir-calendar">
    <div class="col-md-12">
        Investor Relations Events
    </div>
</div>
<div class="row option-row" style="margin-bottom: 25px;">
	<div class="col-xs-12">		
        <div class="upcoming-event">
            <h3 class="title-evnt">Upcoming Event</h3>
            @if(count($upcoming) >= 1)
                @foreach($upcoming as $event)
                    <p class="date-time">- {{date("d F, Y", strtotime($event->event_datetime))}}</p>
                    <p class="ev-title">{{$event->event_title}}</p>
                @endforeach
            @else
                <p class="date-time">No Data</p>
            @endif
        </div>

        <div class="past-event">
            <h3 class="title-evnt">Past Event</h3>
            @if(count($past) >= 1)
                @foreach($past as $event)
                    <p class="date-time">- {{date("Y-m-d", strtotime($event->event_datetime))}}</p>
                    <p class="ev-title">{{$event->event_title}}</p>
                @endforeach
            @else
                <p class="date-time">No Data</p>
            @endif
        </div>
	</div>

</div>
</section>
@else 

    <h2>Calendar</h2>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <span style="font-size: 17px">View</span>
                <div class="btn-group">
                    <?php $value = ['calendar'=>"Events", 'listing'=> 'Listing'] ?>
                    {!! Form::open(array('url'=>route('package.investor-relations-calenda.get-filter-data-listing'),'method' => 'GET')) !!}
                        {!! Form::select('view', $value , (isset($_GET['view']))?$_GET['view']:'' , array('class'=>'form-control sort','onchange'=>'this.form.submit()')) !!}
                    {!! Form::close() !!}
                </div>
            </div>            
        </div>

        <div class="col-md-10">

            @if(isset($_GET['view']) && $_GET['view'] == 'listing')
                <div class="row title-row">
                    <div class="col-xs-12 col-sm-9 col-md-9 right-td-col">
                        New Event
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 left-td-col">
                        Date
                    </div>                   
                </div>
                @foreach($data as $even)    
                <div class="row event-row">
                    <div class="col-xs-12 col-sm-9 col-md-9 right-td-col">
                        <a href="/{{$even->upload}}" target="_brank">{{$even->event_title}}</a>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 left-td-col">
                        {{date("d F, Y", strtotime($even->event_datetime))}}
                    </div>                   
                </div>
                @endforeach
            @else
                <div class="row option-row" style="margin-bottom: 25px;">
                    <div class="col-md-12">     
                        <div class="box-body table-responsive">
                            <div id='calendar'></div>
                        </div>        
                    </div>
                </div>
            @endif
        </div>

    </div>

@endif
<input type="hidden" id="view_type" value="listing">
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Investor relations events</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
    @if($active_theme == "default")
        {!! Html::style('css/package/general-style.css') !!}
        <style>
            .title-row {
            margin-top: 15px;
            background: #414950;            
            }
            .title-row .left-td-col,
            .title-row .right-td-col {            
                color: #fff;
                font-weight: bold;
                padding: 8px 15px;
            }
            .event-row:nth-child(even) {
                background: #f0f0f0;
            }

            .left-td-col,
            .right-td-col {
                padding: 15px;
            }

            .left-td-col {
                color: #272c30;
            }

            .right-td-col a {
                color: #e53840;
                text-decoration: none;
            }

            @media (max-width: 1024px) {
                .left-td-col{
                    padding-bottom: 0px;
                }
                .right-td-col {
                    padding-top: 5px;
                } 
                .option-events{
                    margin-bottom: 20px;
                    margin-left: 0px !important;
                }
                .btn-events {
                    margin-top: 10px;
                }
            }
            .option-events {
                padding-left:5px !important;
                padding-right:5px !important;
            }
            .date-time{
                margin-left: 30px;
                font-weight: 600;
            }
            .ev-title{
                margin-left: 70px;
            }
            .title-evnt{
                font-weight: 600;
            }
            .past-event{
                margin-top: 40px;
            }
        </style>
    @endif
	{!! Html::style('plugins/fullcalendar/fullcalendar.css') !!}
	<style type="text/css">
        .btn_list{
            margin-bottom: 10px !important;
        }
        div.table-responsive {
            overflow-x: visible;
        }
        th{
            background-color: #414950;
            color: #ffffff;
        }
        .sort {
            height: 38px !important;
        }
        #calendar, #content .col-md-10 #calendar{
            background-color: #fff;
            min-width: 600px;
        }
		.fc-toolbar {
			border: 1px solid #ddd;
            background-color: #f0f0f0;
			margin: 0;
            padding: 10px 9px;
		}
		.fc-toolbar button {
		    border: none;
		}
		.fc table {
			background: transparent;
		}
		.fc th {
		    padding: 5px 0;
		    border: none;
		    font-size: 16px;
		    font-weight: normal;
		}
		.fc-ltr .fc-basic-view .fc-day-number {
		    text-align: left;
		    color: #75b600;
		    padding-left: 14px;
		    font-size: 14px;
		}
		.fc td {
			border-color: #fff;
			border-width: 0px;
		    padding: 1px 0;
		    border: 1px solid #fff;
		}
		.fc-event {
			border-radius: 0;
			padding: 4px;
			padding-left: 11px;
			background: #56cccb;
            border: 0px solid #56cccb;
		}
		.fc-other-month {
			background-color: #b2b2b2;
            opacity: 1 !important;
            color: #fff !important;
		}
        .fc-past{
            color: #fff;
        }
        .fc-day{
            border: 1px solid #f0f0f0 !important;
        }
        .fc-day-header{
            background-color: #fff !important;
            color: #6D6565;
        }
        .fc-left, .fc-right{
            margin-top: 0px;
        }
        .fc-state-default {
            background-color: #F0F0F0 !important; 
            border: 0;
            background-image: none !important;
            box-shadow: none !important;
            color: #b5b5b5 !important;
        }
        .fc-center{
            color: #b5b5b5 !important;
        }
        .fc-center h2{
            font-size: 24px;
        }
        .fc-other-event {
            background: red;
        }
        .fc-day-grid-event > .fc-content {
            white-space: normal;
        }
        tr:nth-child(odd) {
            background-color: transparent;
        }
        
        /*overide style*/
        .fc-toolbar{
            width: 100%;
            display: inline-table;
        }
        .button-more{
            width: 5%;
            display: inline-table;
            border: 1px solid #ddd;
            background-color: #f0f0f0;
            margin: -1px;
            padding: 12px 20px;
            font-size: 22px;
            height: 46px;
            padding-bottom: 8px;
            cursor: pointer;
        }
        #calendar .title-row, #calendar .event-row{
            margin: 0;
        }
        .fa-caret-right, .fa-caret-left, .button-more{
                color: #a9a9a9;
        }
     
	</style>
@stop
@section('script')
    <script type="application/javascript">
        $('.menu-active').removeClass('menu-active');
        $('#ir-calendar').addClass('menu-active');
    </script>
@stop