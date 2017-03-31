@extends('layouts.admin')

@section('contents')
    <div class="panel-heading">Dashboard <a href="{{ url('admin/role/create') }}" class="btn btn-lg btn-primary">新增</a></div>

    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif


                        <table class="table">
                            <thead>
                                <th>角色</th>
                                <th>显示名称</th>
                                <th>描述</th>
                                <th>最后修改时间</th>
                                <th>操作</th>
                            </thead>
                            @foreach($Roles as $role)
                            <tbody>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>{{ $role->updated_at }}</td>

                            <td><a href="{{ url('admin/role/'.$role->id.'/edit') }}" class="btn btn-success">添加权限</a>
                            <form action="{{ url('admin/role/'.$role->id) }}" method="POST" style="display: inline;">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger" onclick="return confirm('确认要删除？')">删除</button>
                            </form>
                            </td>
                            </tbody>
                            @endforeach
                    </div>
                    {!! $Roles->links() !!}

@endsection