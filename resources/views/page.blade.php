@extends('admin.layout')

@section('css')
    <style>
        .page {
            background: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-family: cairo
        }

        .page *{
            font-family: 'Cairo' !important;
            font-size: 16px 

        }
    </style>
@endsection

@section('content')
    <div class="page">
        <?= $page->body ?>
    </div>
@endsection
