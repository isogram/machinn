@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{route("$route_name.index")}}">@lang('module.businessPartner')</a> <a href="#" class="current">@lang('web.edit') @lang('module.businessPartner')</a> </div>
        <h1>@lang('module.businessPartner')</h1>
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
                        <h5>@lang('web.addButton') @lang('module.businessPartner')</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="form-wizard" class="form-horizontal" action="{{route("$route_name.update", ['id' => $row->partner_id])}}" method="post">
                            {{csrf_field()}}
                            <div id="form-wizard-1" class="step">
                                <div class="control-group">
                                    <label class="control-label">@lang('master.partnerName')</label>
                                    <div class="controls">
                                        <input id="name" required type="text" value="{{$row->partner_name}}" name="partner_name" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('master.partnerGroup')</label>
                                    <div class="controls">
                                        <select name="partner_group_id">
                                            @foreach($group as $key => $val)
                                                <option @if($row->partner_group_id == $val['partner_group_id']) selected="selected" @endif value="{{$val['partner_group_id']}}">{{$val['partner_group_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">{{$master_module}} Discount Weekend</label>
                                    <div class="controls">
                                        <input value="{{$row->discount_weekend}}" id="weekend" required type="number" name="discount_weekend" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">{{$master_module}} Discount Weekday</label>
                                    <div class="controls">
                                        <input value="{{$row->discount_weekday}}" id="weekday" required type="number" name="discount_weekday" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">{{$master_module}} Discount Special</label>
                                    <div class="controls">
                                        <input value="{{$row->discount_special}}" id="special" required type="number" name="discount_special" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input id="next" class="btn btn-primary" type="submit" value="@lang('web.save')" />
                                <div id="status"></div>
                            </div>
                            <div id="submitted"></div>
                            {{ method_field('PUT') }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
