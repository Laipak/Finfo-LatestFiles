<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
        .container-fluid{
            width: 100%;
            margin:0 auto;
        }
        .table{
            color:#000;
            width:100%
	}
        .table th,
        .table td {
            min-height: 100px;
            width: 350px;
            text-align: center;
            padding: 10px;
        }
        tr:nth-child(even)  {
            background-color: #f0f0f0;
        }

        tr:nth-child(odd)  {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <h3>{{$titleQuarter}}</h3>
        </div>
        <div class="row">
            <table class="table">
                <tr>
                    <th>Title</th>    
                    <th>Values</th>
                </tr>
                @foreach($getArchiveData as $data)
                    <tr>
                        <td>{{$data->title}}</td>
                        <td>
                            @if (strstr($data->value, '-'))
                                {{ "(".str_replace("-","", $data->value.")")}}
                            @else
                                {{ $data->value }}
                            @endif
                        </td>
                    </tr>
                 @endforeach
            </table>
        </div>
    </div>
</body>
</html>
