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

                        <form action="{{ url('admin/permission') }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="text" name="name" class="form-control" required="required" placeholder="权限名称">
                            <br>

                            <div id="firstpane" class="menu_list lf">
                                <h3 class="menu_head current">父级权限</h3>
                                <div style="display:block" class="menu_body">
                                    @foreach($perms as $perm)
                                            <a roleid="{{ $perm->id }}"  class="menu_role" href="#">{{ $perm->name }}</a>
                                    @endforeach
                                </div><br>
                            </div>

                            <input type="text" name="display_name" class="form-control" required="required" placeholder="路由">
                            <br>
                            <input type="text" name="sort" class="form-control" style="width: 30%" required="required" placeholder="排序">
                            <br>
                            <textarea name="description" rows="10" class="form-control" required="required" placeholder="描述"></textarea>
                            <br>

                            <button class="btn btn-lg btn-info">新增</button>
                        </form>

                    </div>
<script>
    $('.menu_role').click(function(){
        if($(this).css("background-color") != 'rgb(142, 142, 142)'){
            $(this).css({'background':'#8E8E8E','color':'red'});
            var roleid = $(this).attr('roleid');
            var html = "<input type='hidden' name='parent_id' value=" +roleid+ ">";
            $(this).append(html)
        } else {
            $(this).css({'background':'','color':''});
            $(this).find("input").remove();
        }

    })
</script>
@endsection