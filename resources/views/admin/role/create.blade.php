@extends('layouts.admin')

@section('contents')

                    <div class="panel-heading">新增角色</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>新增失败</strong> 输入不符合要求<br><br>
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <form action="{{ url('admin/role') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="text" name="name" class="form-control" required="required" placeholder="角色">
                            <br>
                            <input type="text" name="display_name" class="form-control" required="required" placeholder="显示名称">
                            <br>
                            <textarea name="description" rows="10" class="form-control" required="required" placeholder="描述"></textarea>
                            <br>
                            <button class="btn btn-lg btn-info">新增</button>
                        </form>

                    </div>
@endsection