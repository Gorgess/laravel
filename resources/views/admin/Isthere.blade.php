@extends('layouts.admin')

@section('contents')

    <div class="panel-heading">新增权限</div>
    <div class="panel-body">
        222
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