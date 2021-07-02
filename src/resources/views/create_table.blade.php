@extends('artadmin::auth_page')
@section('title', 'Создание таблицы')
@section('content')
    <div class="main_content_iner">
    <div class="row">
        <h2>Создание таблицы</h2>
        <form action="{{ route("artcrud.docreate") }}" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Название таблицы</label>
                <input type="text" class="form-control" name="name_table" id="exampleInputEmail1">

            </div>
            <div class="mb-3">
                <label for="exampleInputEmail22" class="form-label">Заголовок таблицы</label>
                <input type="text" class="form-control" name="title_table" id="exampleInputEmail22">

            </div>

            <div class="mb-3">
                <label for="exampleInputEmail22" class="form-label">Запуск миграций</label>
                <input type="checkbox" checked   value="1" name="start_migrate" id="exampleInputEmail22">

            </div>


            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Поля</label>
                <select class="form-control field_add" name="fields">
                    <option></option>
                    @foreach ($fields as $key=>$field)
                        <option value="{{ $key }}">{{ $field->getTitleField() }}</option>

                    @endforeach
                </select>
                <button type="button" data-next_element="0" class="btn btn-success btn_add_field" data-url="{{ route("artcrud.get_field") }}">
                    Добавить поле
                </button>

            </div>
            <div class="row additional_fields">

            </div>
            @csrf
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </div>
@endsection
@section('footer_js')
    <script src="{{ asset("vendor/artcrud/crud.js") }}"></script>
@endsection


