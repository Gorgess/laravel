@extends('layouts.admin')

@section('contents')
    <div class="panel-heading">Dashboard <a href="{{ url('admin/permission/create') }}" class="btn btn-lg btn-primary">新增</a></div>

    <div class="panel-body">
        <div id="firstpane" class="menu_list">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif
                        @foreach($Newperms as $per)
                            <h3 class="menu_head current">{{ $per['perm']->name }}</h3>
                            <div style="display:block" class="menu_body">
                                @foreach($per['perms'] as $Nper)
                                <a href="{{ url('admin/permission/'.$Nper->id.'/edit') }}">{{ $Nper->name }}</a>
                                @endforeach
                            </div>
                        @endforeach
    </div>
    </div>

@endsection