<!-- 这是 admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <script type="text/javascript" charset="utf-8" src="{{ asset('org/ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('org/ueditor/ueditor.all.min.js') }}"> </script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('org/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>


    <style type="text/css">
        .Isbut{margin-left: 30px;}
        .menu_list{width:268px;margin:0 auto;}
        .menu_head{
            height: 47px;
            line-height: 47px;
            padding-left: 38px;
            font-size: 14px;
            color: #525252;
            cursor: pointer;
            border-left: 1px solid #e1e1e1;
            border-right: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
            border-top: 1px solid #F1F1F1;
            position: relative;
            margin: 0px;
            font-weight: bold;
            background: #f1f1f1 url(images/pro_left.png) center right no-repeat;
        }
        .menu_list .current{background:#f1f1f1 url(images/pro_down.png) center right no-repeat;}
        .menu_body{
            line-height: 38px;
            border-left: 1px solid #e1e1e1;
            backguound: #fff;
            border-right: 1px solid #e1e1e1;

        }
        .lf{float: left}
        .menu_body a{padding-left: 33px; display:block;height:38px;line-height:38px;color:#777777;background:#fff;text-decoration:none;border-bottom:1px solid #e1e1e1;}
        .menu_body a:hover{text-decoration:none;}
        .newdiv{min-width: 1400px}
        .container{width: 80%;}
        .panel-heading a{margin-left: 80%}
    </style>

    <script type="text/javascript">

        $(document).ready(function(){
            //权限点击样式

            //富文本编辑器
            var ue=UE.getEditor("ueditor");
            ue.ready(function(){
                //因为Laravel有防csrf防伪造攻击的处理所以加上此行
                ue.execCommand('serverparam','_token','{{ csrf_token() }}');
            });

            $("#firstpane .menu_body:eq(0)").show();
            $("#firstpane h3.menu_head").click(function(){
                $(this).addClass("current").next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
                $(this).siblings().removeClass("current");
            });

            $("#secondpane .menu_body:eq(0)").show();
            $("#secondpane h3.menu_head").mouseover(function(){
                $(this).addClass("current").next("div.menu_body").slideDown(500).siblings("div.menu_body").slideUp("slow");
                $(this).siblings().removeClass("current");
            });

        });
    </script>
</head>
<div class="newdiv">
<div id="app-layout">

<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/admin') }}">
                后台首页
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/home') }}">前台首页</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if(Auth::guard('admin')->user())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::guard('admin')->user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('admin/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>



                @else
                    <li><a href="{{ url('admin/login') }}">Login</a></li>
                    <li><a href="{{ url('admin/register') }}">Register</a></li>

                @endif
            </ul>
        </div>
    </div>
</nav>
</div>

@if(!Auth::guard('admin')->user())

@else
        <?php
        $functions = new functions();
        $muns = $functions->GetroleList(Auth::guard('admin')->user()->id);
        ?>
    <div class="newdiv">

        <div id="firstpane" class="menu_list lf">
            @if($muns)
            @foreach($muns as $newmun)
                @foreach($newmun as $nmenu)
                        @if($nmenu['par'])
                    <h3 class="menu_head current">{{ $nmenu['par']->name }}</h3>
                        @endif
            <div style="display:block" class="menu_body">
                    @foreach($nmenu['data'] as $newdata)
                        @if($newdata)
                    <a href="{{ $newdata->display_name }}">{{ $newdata->name }}</a>
                        @endif
                    @endforeach
            </div>
                    @endforeach
            @endforeach
            @endif
                {{--<h3 class="menu_head current">权限管理</h3>--}}
                {{--<div style="display:block" class="menu_body">--}}
                    {{--<a href="{{ url('/admin/admin') }}">用户中心</a>--}}
                    {{--<a href="{{url('/admin/role') }}">角色管理</a>--}}
                    {{--<a href="{{ url('/admin/permission') }}">操作权限</a>--}}
                {{--</div>--}}
        </div>
        <div class="container lf" >
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default" style="min-height: 650px">
                        @yield('contents')
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>

@endif
@yield('content')


{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>