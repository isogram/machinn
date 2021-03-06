@extends('layout.main')

@section('title', 'Home')

@section('content')

    <div id="content-header">
        <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{route("$route_name.index")}}">@lang('web.cashBankAccount')</a> <a href="#" class="current">@lang('web.edit') @lang('web.cashBankAccount')</a> </div>
        <h1>@lang('web.cashBankAccount')</h1>
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
                        <h5>@lang('web.edit') @lang('web.cashBankAccount')</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form id="form-wizard" class="form-horizontal" action="{{route("$route_name.update", ['id' => $row->cash_account_id])}}" method="post">
                            {{csrf_field()}}
                            <div id="form-wizard-1" class="step">
                                <div class="control-group">
                                    <label class="control-label">@lang('master.cashBankName')</label>
                                    <div class="controls">
                                        <input id="name" @if($row->type == 2) readonly @endif required value="{{$row->cash_account_name}}" type="text" style="width: 30em" name="cash_account_name" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('master.cashBankDesc')</label>
                                    <div class="controls">
                                        <textarea rows="5" @if($row->type == 2) readonly @endif name="cash_account_desc" id="desc">{{$row->cash_account_desc}}</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('master.cashBankOpenBalance')</label>
                                    <div class="controls">
                                        <input id="amount" value="{{$row->open_balance}}" required type="text" style="width: 30em" name="open_balance" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">@lang('master.cashBankOpenDate')</label>
                                    <div class="controls">
                                        <input value="{{$row->open_date}}" class="datepicker" data-date-format="yyyy-mm-dd" id="open_date" required type="text" style="width: 30em" name="open_date" />
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
