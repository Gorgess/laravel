@extends('layouts.admin')

@section('contents')

                    <div class="panel-heading">修改文章</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>修改失败</strong> 输入不符合要求<br><br>
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <form action="{{ url('admin/article/'.$article->id) }}" method="POST">
                            {!! csrf_field() !!}
                            {{ method_field('PUT') }}
                            <input type="text" name="title" value="{{ $article->title }}" class="form-control" required="required" placeholder="请输入标题">
                            <br>
                            <textarea name="body" rows="10"  class="form-control" required="required" placeholder="请输入内容">{{ $article->body }}</textarea>
                            <br>
                            <button class="btn btn-lg btn-info">保存</button>
                        </form>

                    </div>

@endsection