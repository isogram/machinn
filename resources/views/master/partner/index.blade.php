@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">{{$master_module}}</a> </div>
        <h1>{{$master_module}}</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <a class="btn btn-primary" href="{{route("$route_name.create")}}">Add New {{$master_module}}</a>
        {!! session('displayMessage') !!}
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Group</th>
                                <th>Discount Weekend</th>
                                <th>Discount Weekday</th>
                                <th>Discount Special</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($rows) > 0)
                                @foreach($rows as $val)
                                    <tr class="odd gradeX">
                                        <td>{{$val->partner_name}}</td>
                                        <td>{{$model->getGroupName($val->partner_group_id)}}</td>
                                        <td>{{$val->discount_weekend}}%</td>
                                        <td>{{$val->discount_weekday}}%</td>
                                        <td>{{$val->discount_special}}%</td>
                                        <td>{!!\App\Helpers\GlobalHelper::setActivationStatus($val->partner_status)!!}</td>
                                        <td>
                                            <a style="margin-right: 20px" href="{{route("$route_name.edit", ['id' => $val->partner_id])}}" title="Edit"><i class="icon-pencil" aria-hidden="true"></i> Edit</a>
                                            @if($val->partner_status == 0)
                                                <a onclick="return confirm('You will activate {{$val->partner_name}}, continue? ')" href="{{route("$route_name.change-status", ['id' => $val->partner_id, 'status' => $val->partner_status])}}"><i class="icon-check" aria-hidden="true"></i> Set Active</a>
                                            @else
                                                <a onclick="return confirm('You will deactivate {{$val->partner_name}}, continue? ')" href="{{route("$route_name.change-status", ['id' => $val->partner_id, 'status' => $val->partner_status])}}"><i class="icon-remove" aria-hidden="true"></i> Set Not Active</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" style="text-align: center">No Data Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $rows->links() }}
            </div>
        </div>
    </div>

@endsection