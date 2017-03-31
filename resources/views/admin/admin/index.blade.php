@extends('layouts.admin')

@section('contents')
    <div class="panel-heading">Dashboard <a href="{{ url('admin/admin/create') }}" class="btn btn-lg btn-primary">新增</a></div>

    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif


                        <table class="table">
                            <thead>
                                <th>用户ID</th>
                                <th>用户昵称</th>
                                <th>最后登录时间</th>
                                <th>最后登录IP</th>
                                <th>操作</th>
                            </thead>
                            @foreach($Admins as $Admin)
                            <tbody>
                                <td>{{ $Admin->id }}</td>
                                <td>{{ $Admin->name }}</td>
                                <td>{{ $Admin->updated_at }}</td>
                                <td>{{ $Admin->last_ip }}</td>

                            <td><a href="{{ url('admin/admin/'.$Admin->id.'/edit') }}" class="btn btn-success">添加角色</a>
                            <form action="{{ url('admin/admin/'.$Admin->id) }}" method="POST" style="display: inline;">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger" onclick="return confirm('确认要删除？')">删除</button>
                            </td>
                            </tbody>
                            </form>
                            @endforeach
                    </div>
                    {!! $Admins->links() !!}

@endsection