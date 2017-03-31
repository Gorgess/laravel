@extends('layouts.admin')

@section('contents')
    <div class="panel-heading">Dashboard</div>

    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <a href="{{ url('admin/article/create') }}" class="btn btn-lg btn-primary">新增</a>

                        @foreach ($Articles as $article)
                            <hr>
                            <div class="article">
                                <h4>{{ $article->title }}</h4>
                                <div class="content">
                                    {!! $article->body !!}
                                </div>
                            </div>
                            <a href="{{ url('admin/article/'.$article->id.'/edit') }}" class="btn btn-success">编辑</a>
                            <form action="{{ url('admin/article/'.$article->id) }}" method="POST" style="display: inline;">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger" onclick="return confirm('确认要删除？')">删除</button>
                            </form>
                        @endforeach

                    </div>
                    {!! $Articles->links() !!}

@endsection