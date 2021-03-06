@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{route("$route_name.index")}}">@lang('module.businessPartner')</a> <a href="#" class="current">@lang('web.add') @lang('module.businessPartner')</a> </div>
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
                        <form id="form-wizard" class="form-horizontal" action="{{route("$route_name.store")}}" method="post">
                            {{csrf_field()}}
                            <div id="form-wizard-1" class="step">
                                <div class="control-group">
                                    <label class="control-label">@lang('master.partnerName')</label>
                                    <div class="controls">
                                        <input value="{{old('partner_name')}}" id="name" required type="text" name="partner_name" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('master.partnerGroup')</label>
                                    <div class="controls">
                                        <select name="partner_group_id">
                                            <option disabled selected>@lang('web.choose')</option>
                                            @foreach($group as $key => $val)
                                                <option @if(old('partner_group_id') == $val['partner_group_id']) selected="selected" @endif value="{{$val['partner_group_id']}}">{{$val['partner_group_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Discount Weekend</label>
                                    <div class="controls">
                                        <input value="{{empty(old('discount_weekend')) ? 0 : old('discount_weekend')}}" id="weekend" required type="number" name="discount_weekend" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Discount Weekday</label>
                                    <div class="controls">
                                        <input value="{{empty(old('discount_weekday')) ? 0 : old('discount_weekend')}}" id="weekday" required type="number" name="discount_weekday" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Discount Special</label>
                                    <div class="controls">
                                        <input value="{{empty(old('discount_special')) ? 0 : old('discount_weekend')}}" id="special" required type="number" name="discount_special" />
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
