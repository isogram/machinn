@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{route("$route_name.index")}}">@lang('module.contactGroup')</a> <a href="#" class="current">@lang('web.edit') @lang('module.contactGroup')</a> </div>
        <h1>@lang('module.contactGroup')</h1>
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
                        <h5>@lang('web.edit') @lang('module.contactGroup')</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" action="{{route("$route_name.update", ['id' => $row->contact_group_id])}}" method="post">
                            {{csrf_field()}}
                            <div id="form-wizard-1" class="step">
                                <div class="control-group">
                                    <label class="control-label">@lang('master.contactGroupName')</label>
                                    <div class="controls">
                                        <input id="name" required type="text" value="{{$row->contact_group_name}}" name="contact_group_name" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('web.desc')</label>
                                    <div class="controls">
                                        <textarea name="contact_group_desc">{{$row->contact_group_desc}}</textarea>
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
