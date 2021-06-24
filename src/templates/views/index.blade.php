@extends('artadmin::auth_page')
@section('title', 'Список')
@section('content')
    <div class="main_content_iner">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 8 CRUD Example </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('[ROUTE_CREATE]') }}" title="Create a product">Добавить</i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">

        @foreach ($[name_table_little] as $row)
            <tr>
                <td>{{ $row->id }}</td>
                [FIELD_LIST]




                <td>
                    <form action="{{ route('[ROUTE_DESTROY]', $row->id) }}" method="POST">

                        <a href="{{ route('[ROUTE_SHOW]', $row->id) }}" title="show">
                           Просмотр
                        </a>

                        <a href="{{ route('[ROUTE_EDIT]', $row->id) }}">
                            Редактирование
                        </a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            Удалить
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $[name_table_little]->links() !!}
    </div>
@endsection
