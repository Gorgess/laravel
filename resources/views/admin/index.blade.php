@extends('layouts.admin')

@section('contents')
    @if($errors->all())
    <div class="alert alert-danger">

            <strong>操作失败</strong> 可能是以下问题<br><br>
            {!! implode('<br>', $errors->all()) !!}

    </div>
    @endif
@endsection
