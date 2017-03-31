@extends('layouts.admin')

@section('contents')
    <style type="text/css">
       .newbtn-info{margin-top: 50px}
    </style>
                    <div class="panel-heading">用户添加角色</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>添加失败</strong> 输入不符合要求<br><br>
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <form action="{{ url('admin/admin/'.$admin_id) }}" method="POST">
                            {!! csrf_field() !!}
                            {{ method_field('PUT') }}
                            <div  class="menu_list newmenu_list">
                                <h3 class="menu_head current">选择角色</h3>
                                <div style="display:block" class="menu_body">
                                    @foreach($Roles as $role)
                                        @if(in_array($role->id,$Userroles))
                                            <a roleid="{{ $role->id }}" style="background:#8E8E8E;color:red " class="menu_role" href="#">{{ $role->name }}
                                                <input type="hidden" name="role_id[]" value="{{ $role->id }}">
                                            </a>
                                        @else
                                            <a roleid="{{ $role->id }}"  class="menu_role" href="#">{{ $role->name }}</a>
                                        @endif
                                    @endforeach
                                </div>
                            <button class="btn btn-lg btn-info newbtn-info">保存</button>
                        </form>

                    </div>
    <script>
        $('.menu_role').click(function(){
            if($(this).css("background-color") != 'rgb(142, 142, 142)'){
                $(this).css({'background':'#8E8E8E','color':'red'});
                var roleid = $(this).attr('roleid');
                var html = "<input type='hidden' name='role_id[]' value=" +roleid+ ">";
                $(this).append(html)
            } else {
                $(this).css({'background':'','color':''});
                $(this).find("input").remove();
            }

        })


    </script>
@endsection
