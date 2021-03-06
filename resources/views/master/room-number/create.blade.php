@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{route("$route_name.index")}}">@lang('module.roomNumber')</a> <a href="#" class="current">@lang('web.add') @lang('module.roomNumber')</a> </div>
        <h1>@lang('module.roomNumber')</h1>
    </div>
    <div class="container-fluid"><hr>
        <a class="btn btn-success" href="javascript:history.back()">@lang('web.view') Data</a>
        @foreach($errors->all() as $message)
            <div style="margin: 20px 0" class="alert alert-error">
                {{$message}}
            </div>
        @endforeach
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-pencil"></i> </span>
                        <h5>@lang('web.addButton') @lang('module.roomNumber')</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="form-wizard" class="form-horizontal" action="{{route("$route_name.store")}}" method="post">
                            {{csrf_field()}}
                            <div id="form-wizard-1" class="step">
                                <div class="control-group">
                                    <label class="control-label">@lang('module.roomNumber')</label>
                                    <div class="controls">
                                        <input value="{{old('room_number_code')}}" id="name" required type="text" name="room_number_code" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('web.roomType')</label>
                                    <div class="controls">
                                        <select name="room_type_id">
                                            <option disabled selected>@lang('web.choose')</option>
                                            @foreach($type as $key => $val)
                                                <option @if(old('room_type_id') == $val['room_type_id']) selected="selected" @endif value="{{$val['room_type_id']}}">{{$val['room_type_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('web.roomFloor')</label>
                                    <div class="controls">
                                        <select name="room_floor_id">
                                            <option disabled selected>@lang('web.choose')</option>
                                            @foreach($floor as $key => $val)
                                                <option @if(old('room_floor_id') == $val['property_floor_id']) selected="selected" @endif value="{{$val['property_floor_id']}}">{{$val['property_floor_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input id="next" class="btn btn-primary" type="submit" value="@lang('web.save')" />
                                <div id="status"></div>
                            </div>
                            <div id="submitted"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
