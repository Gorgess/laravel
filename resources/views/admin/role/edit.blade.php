@extends('layouts.admin')

@section('contents')
    <style type="text/css" xmlns="http://www.w3.org/1999/html">
        .newbtn-info{margin-top: 50px}
    </style>
    <div class="panel-heading">角色添加权限</div>
    <div class="panel-body">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>添加失败</strong> 输入不符合要求<br><br>
                {!! implode('<br>', $errors->all()) !!}
            </div>
        @endif

        <form action="{{ url('admin/role/'.$role_id) }}" method="POST">
            {!! csrf_field() !!}
            {{ method_field('PUT') }}
            <div  class="menu_list newmenu_list">
                @foreach($perms as $perm)
                <h3 class="menu_head current">{{ $perm['perm']->name }}</h3>
                <div style="display:block" class="menu_body">
                    @foreach($perm['permt'] as $per)
                        <div>

                            @if(in_array($per['ms']->id,$permRole))
                            <a permid="{{ $per['ms']->id }}" style="background:#8E8E8E;color:red " class="menu_role" href="#">{{ $per['ms']->name }}
                                <input type="hidden" class="parents" name="perm[]" value="{{ $per['ms']->id }}">
                            </a>
                            @else
                                <a permid="{{ $per['ms']->id }}"  class="menu_role" href="#">{{ $per['ms']->name }}</a>
                            @endif

                                    <td>
                                        @foreach($per['pe'] as $nPe)
                                        @if($nPe->insert)
                                            <input  class="Isbut" style="background:#8E8E8E"  prope="insert" values="{{ $nPe->insert }}" type="button" value="添加">
                                                <input type='hidden' name='rbac[]' value="{{ $per['ms']->id.','.$nPe->insert.','.'insert' }}">
                                        @else
                                            <input  class="Isbut"  prope="insert" values="{{ $nPe->insert }}" type="button" value="添加">
                                        @endif

                                        @if($nPe->update)
                                            <input class="Isbut" style="background:#8E8E8E"  class="Isbut" prope="update" values="{{ $nPe->update }}" type="button" value="修改">
                                                <input type='hidden' name='rbac[]' value="{{ $per['ms']->id.','.$nPe->update.','.'update' }}">
                                        @else
                                            <input   class="Isbut" prope="update" values="{{ $nPe->update }}" type="button" value="修改">
                                        @endif

                                        @if($nPe->delete)
                                            <input class="Isbut"  style="background:#8E8E8E"  class="Isbut" prope="delete" values="{{ $nPe->delete }}" type="button" value="删除">
                                                <input type='hidden' name='rbac[]' value="{{ $per['ms']->id.','.$nPe->delete.','.'delete' }}">
                                        @else
                                            <input class="Isbut" prope="delete"  values="{{ $nPe->delete }}" type="button" value="删除">
                                        @endif

                                        @endforeach
                                    </td>

                        </div>
                    @endforeach
                </div>
                @endforeach
                <button class="btn btn-lg btn-info newbtn-info">保存</button>
        </form>

    </div>
    <script>
        $('.menu_role').click(function(){
            if($(this).css("background-color") != 'rgb(142, 142, 142)'){
                $(this).css({'background':'#8E8E8E','color':'red'});
                var roleid = $(this).attr('permid');
                var html = "<input type='hidden' class='parents' name='perm[]' value=" +roleid+ ">";
                $(this).append(html)
            } else {
                $(this).css({'background':'','color':''});
                $(this).find("input").remove();
            }

        })
        $('.Isbut').click(function(){
            var values = $(this).attr('values');
            var prope = $(this).attr('prope');
            var parents = $(this).parent().find('a').attr('permid');
            if($(this).css("background-color") != 'rgb(142, 142, 142)'){
                $(this).css({'background':'#8E8E8E'});


                var html = "<input type='hidden' name='rbac[]' value=" +parents+","+1+","+prope+">";
                $(this).after(html)
            } else {
                $(this).css({'background':'','color':''});
                $(this).next().remove();
                var html = "<input type='hidden' name='rbac[]' value=" +parents+","+0+","+prope+">";
                $(this).after(html)
            }
        })


    </script>
@endsection
