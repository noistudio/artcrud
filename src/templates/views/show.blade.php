@extends('artadmin::auth_page')
@section('title', 'Просмотр')
@section('content')
    <div class="main_content_iner">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("[ROUTE_INDEX]") }}">[TABLE.TITLE]</a></li>
                <li class="breadcrumb-item active" aria-current="page">Просмотр</li>
            </ol>
        </nav>

    <div class="row">
         [FIELDS_SHOW]

    </div>
    </div>
@endsection
