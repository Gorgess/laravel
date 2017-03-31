@extends('layouts.admin')

@section('contents')

    <div class="panel-heading">新增权限</div>
    <div class="panel-body">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>新增失败</strong> 输入不符合要求<br><br>
                {!! implode('<br>', $errors->all()) !!}
            </div>
        @endif


        <form action="{{ url('admin/permission/'.$permsData->id) }}" method="POST">
                {!! csrf_field() !!}
                {{ method_field('PUT') }}
            <input type="text" value="{{ $permsData->name }}" name="name" class="form-control" required="required" placeholder="权限名称">
            <br>

            <div id="firstpane" class="menu_list lf">
                <h3 class="menu_head current">父级权限</h3>
                <div style="display:block" class="menu_body">
                    @foreach($perms as $perm)
                        @if($perm->id == $permsData->parent_id)
                            <a permid="{{ $perm->id }}" style="background:#8E8E8E;color:red " class="menu_role" href="#">{{ $perm->name }}
                                <input type="hidden" name="parent_id" value="{{ $perm->id }}">

                            </a>
                        @else
                            <a permid="{{ $perm->id }}"  class="menu_role" href="#">{{ $perm->name }}</a>
                        @endif
                    @endforeach
                </div><br>
            </div>

            <input type="text" name="display_name" value="{{ $permsData->display_name }}" class="form-control" required="required" placeholder="路由">
            <br>
            <input type="text" name="sort" value="{{ $permsData->sort }}" class="form-control" style="width: 30%" required="required" placeholder="排序">
            <br>

            <textarea name="description" rows="10" class="form-control" required="required" placeholder="描述">{{ $permsData->description }}</textarea>
            <br>
            <div>
            <button class="btn btn-lg btn-info">保存</button>

        </form>
            <form action="{{ url('admin/permission/'.$permsData->id) }}" method="POST" style="display: inline;">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button type="submit" class="btn btn-lg btn-danger" onclick="return confirm('确认要删除？')">删除</button>
            </form>
    </div>
    </div>
    <script>
        $('.menu_role').click(function(){
            if($(this).css("background-color") != 'rgb(142, 142, 142)'){
                $(this).css({'background':'#8E8E8E','color':'red'});
                var roleid = $(this).attr('permid');
                var html = "<input type='hidden' name='parent_id' value=" +roleid+ ">";
                $(this).append(html)
            } else {
                $(this).css({'background':'','color':''});
                $(this).find("input").remove();
            }

        })
    </script>
@endsection