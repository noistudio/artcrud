@extends('artadmin::auth_page')
@section('title', 'Редактирование')
@section('content')
    <div class="main_content_iner">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("[ROUTE_INDEX]") }}">[TABLE.TITLE]</a></li>
                <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
            </ol>
        </nav>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="dzenkit-basic-card dzenkit-fulltable-maxwid-lg">
            <div class="dzenkit-table-header hdr-box d-flex align-items-center justify-content-between">
                <div class="hdr">Редактирование</div>
            </div>
            <div class="dzenkit-selections-setting" style="display: block;">
                <div class="dzenkit-basic-card-body">
    <form action="javascript:void(0);" class="crud_form" data-action="{{ route('[ROUTE_UPDATE]',$[name_table_little]->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')



        <div class="row">
            <div class="row mb-2 mt-2">
                <div class="col-5">
                    Статус
                </div>
                <div class="form-check form-switch  col-6">
                    <input class="form-check-input row_is_enable" data-route="{{ route("[ROUTE_PREFIX].toogle",$[name_table_little]->id) }}" type="checkbox" @if($[name_table_little]->enable==1) checked @endif >
                </div>
            </div>
            [EDIT_FIELDS]


            <div class="crud_notify alert alert-danger" style="display:none">

            </div>
            <div class="row col-xs-12 col-sm-12 col-md-12 text-center">
                <div class="col-5">
                    <button type="submit" name="redirect" value="list" class="btn_create_list btn btn-primary">Сохранить и перейти в список</button>
                </div>
                <div class="col-5">
                    <button type="submit" name="redirect" value="edit" class="btn_create_edit btn btn-primary">Сохранить и перезагрузить страницу</button>

                </div>

            </div>
        </div>

    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_js')
    <script src="{{ asset("vendor/artcrud/crud.js") }}"></script>
@endsection
