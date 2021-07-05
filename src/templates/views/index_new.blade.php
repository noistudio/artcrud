@extends('artadmin::auth_page')
@section('title', 'Список')
@section('content')
    <div class="main_content_iner">
        <!-- InstanceBeginEditable name="workspace" -->

        <!--<div class="breadcrumbs">
            <p>
                <a href="index.html">Сфера-СМ</a>
                <i class="icon-angle-right"></i>
                <a href="cat--diagnostika.html">Диагностика</a>
                <i class="icon-angle-right"></i>
                <span>МРТ обследование</span>
            </p>
        </div>-->







        <div class="dzenkit-basic-card dzenkit-fulltable-maxwid-lg">

            <div class="dzenkit-table-header hdr-box d-flex align-items-center justify-content-between">
                <div class="hdr">[TABLE.TITLE]</div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-secondary mr-2 icon-filter-1 js_show_hide_selections-setting"></button>
                    <div class="dropdown">
                        <a id="tableCog" href="#" class="btn btn-secondary mr-2 icon-cog-alt dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right dzenkit-dropdown-menu dzenkit-check-menu pt-0" aria-labelledby="tableCog">
                            <li class="hdr">Настройка выдачи</li>
                            <li class="d-flex"><a class="dropdown-item icon-ok dzen-check @if(isset($request_all['orderby']) and $request_all['orderby']=="desc") actv @endif" href="?orderby=desc">Новый сверху</a></li>
                            <li><a class="dropdown-item icon-ok dzen-check @if(isset($request_all['orderby']) and $request_all['orderby']=="asc") actv @endif" href="?orderby=asc">Новый снизу</a></li>

                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item icon-ok dzen-check js_show_hide_table_descripts" href="#">Раскрыть уточнения</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('[ROUTE_CREATE]') }}" class="btn btn-dzenkit-action"><i class="icon-plus"></i>Добавить</a>
                </div>
            </div>

            <!-- ++++++++++ dzenkit-selections-setting ++++++++++ -->
            <div class="dzenkit-selections-setting">
                <div class="dzenkit-basic-card-body">
                    <p class="hdr">Настройка выборки</p>

                    <form action="">




                        <!-- / form SELECT GROUP with CHAIN of selects -->



                        <div class="row mb-3">
                            <label for="selectForm_CategorSubGroup" class="col-md-4 col-form-label">Опубликовано</label>
                            <div class="col-md-4">
                                <select class="form-select" name="enable"  >
                                    <option  value="0"  ></option>
                                    <option @if(isset($request_all['enable']) and $request_all['enable']=="1") selected="selected" @endif value="1">Да</option>
                                    <option @if(isset($request_all['enable']) and $request_all['enable']=="2") selected="selected" @endif  value="2">Нет</option>
                                </select>
                            </div>
                        </div>











                        <div class="dzenkit-form-range-box row mb-3">
                            <label for="selectForm_CategorSubGroup" class="col-md-4 col-form-label">Дата</label>
                            <div class="col-md-2">
                                <select class="form-select" name="data_type">
                                    <option value="0"></option>
                                    <option @if(isset($request_all['data_type']) and $request_all['data_type']==1) selected @endif  value="1">=</option>

                                    <option @if(isset($request_all['data_type']) and $request_all['data_type']==3) selected @endif value="3">↔</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="dzenkit-range-input row">
                                    <div class="col-md-6"><input type="date" value="@if(isset($request_all['date_create_from'])){{ $request_all['date_create_from'] }}@endif"  name="date_create_from" class="form-control" id="" data-ph-double="От" placeholder="От"></div>
                                    <div class="col-md-6"><input type="date"  value="@if(isset($request_all['date_create_to'])){{ $request_all['date_create_to'] }}@endif" name="date_create_to" class="form-control" id="" placeholder="До"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row dzenkit-submit-box">
                            <div class="offset-md-4">
                                <button type="submit" class="btn btn-info">Найти</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
            <!-- ++++++++++ / dzenkit-selections-setting ++++++++++ -->



            <div class="dzenkit-basic-card-body rows_super_list">
                @foreach($[name_table_little] as $row)
                    <form style="display:none;" class="delete_{{ $row->id }}" action="{{ route('[ROUTE_NAME].destroy', $row->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            Удалить
                        </button>
                    </form>
                @endforeach
                <form class="delete_form_all" action="{{ route('[ROUTE_NAME].delete_all') }}" method="GET">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="navbar-col">
                                    <div class="d-flex">
                                        <div class="form-check">
                                            <input class="form-check-input form-check-all-numbers" type="checkbox" value="#">
                                            <b>#</b>
                                        </div>
                                    </div>
                                </th>

                                [TITLE_FIELDS]


                            </tr>
                            </thead>

                            <tbody >
                            @foreach($[name_table_little] as $row)
                                <tr data-url-change="{{ route("[ROUTE_NAME].change",$row->id) }}" data-row-id="{{ $row->id }}" data-sort="{{ $row->sort }}">
                                    <td>
                                        <div class="row-nav d-flex">
                                            <div class="form-check">
                                                <input class="form-check-input form-check-number" type="checkbox"  name="id[]" value="{{ $row->id }}">
                                                <strong class="js_row_number">{{ $row->sort }}</strong></div>

                                            <div class="nav-bar d-flex align-items-center">
                                                <div class="change d-flex">
                                                    <button class="icon-down-big js_put_row_down" data-row-id="{{ $row->id}}"></button><button class="icon-up-big js_put_row_up" data-row-id="{{ $row->id}}"></button>
                                                </div>
                                                <div class="status">
                                                    <div class="form-check form-switch d-flex align-items-center">
                                                        <input class="form-check-input row_is_enable" data-route="{{ route("[ROUTE_NAME].toogle",$row->id) }}" type="checkbox" @if($row->enable==1) checked @endif >
                                                    </div>
                                                </div>
                                                <!-- action menu -->
                                                <div class="action">
                                                    <div class="dropleft">
                                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="icon-menu"></i>
                                                        </button>








                                                        <ul class="dropdown-menu">
                                                            <li><a class="icon-pencil d-flex align-items-center" href="{{ route('[ROUTE_NAME].show', $row->id) }}" title="show">
                                                                    Просмотр
                                                                </a></li>
                                                            <li><a class="icon-pencil d-flex align-items-center" href="{{ route('[ROUTE_NAME].edit', $row->id) }}" title="show">
                                                                    Редактирование
                                                                </a></li>
                                                            <li><a class="icon-clone d-flex align-items-center" href="{{ route("[ROUTE_NAME].clone",$row->id) }}">Клонировать</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><p class="change-row-action icon-sort d-flex align-items-center" href="#"><input type="text" class="input_new_position" value="{{ $row->sort }}"><b class="btn btn-dzenkit-action btn_move">Перенести</b></p></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="icon-trash-empty del d-flex align-items-center delete_row" data-row-id="{{ $row->id }}" href="#" data-toggle="modal" data-target="#deleteModal">Удалить</a></li>

                                                        </ul>

                                                    </div>
                                                </div>
                                                <!-- / action menu -->
                                            </div>
                                        </div>
                                    </td>

                                    [FIELD_LIST]



                                </tr>

                            @endforeach



                            </tbody>
                        </table>

                        <div class="actions-for-table-rows">

                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="#">
                                </div>

                                <div class="d-flex mr-3"><b>Выбрано&nbsp;</b> <span class="js_total_table_rows_selected"> 0:</span></div>

                                <div>
                                    <button  class="btn btn-secondary my-1 mr-2 icon-toggle-on js_form_tablerow_on">Включить</button>
                                    <button   class="btn btn-secondary my-1 mr-2 icon-toggle-off js_form_tablerow_off">Выключить</button>
                                    <button class="btn btn-danger my-1 mr-2 icon-trash-empty js_form_tablerow_del" data-toggle="modal" data-target="#deleteModal2">Удалить</button>
                                </div>
                            </div>

                        </div>

                    </div>



                    <nav>
                        {!! $[name_table_little]->links("artcrud::paginate") !!}

                    </nav>



                </form>

            </div>
        </div>












        <!-- ==================== M O D A L S ==================== -->
        <!-- ++++++++++ DELETE MODAL ++++++++++ -->
        <div class="modal fade" id="deleteModal2" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Подтвердите УДАЛЕНИЕ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Данные будут удалены безвозвратно.</p>
                        <p>Вы уверены, что хотите удалить?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete_all" data-dismiss="modal">Удалить</button>
                        <button type="button" class="btn btn-info ml-auto">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Подтвердите УДАЛЕНИЕ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Данные будут удалены безвозвратно.</p>
                        <p>Вы уверены, что хотите удалить?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete_row_btn" data-dismiss="modal">Удалить</button>
                        <button type="button" class="btn btn-info ml-auto">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ++++++++++ / DELETE MODAL ++++++++++ -->
        <!-- ==================== / M O D A L S ==================== -->



        <!-- InstanceEndEditable -->
    </div>
@endsection
@section('footer_js')
    <script src="{{ asset("vendor/artcrud/crud.js") }}"></script>
@endsection
