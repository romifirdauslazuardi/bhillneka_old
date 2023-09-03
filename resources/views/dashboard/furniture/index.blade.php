@extends('dashboard.layouts.main')

@section('title', $title)

@section('css')
    <style>
        .nav-tabs.nav-tabs-solid a.active {
            background-color: #FF9F43;
            border-color: #FF9F43;
            color: #fff;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .comp-section .nav-tabs.nav-tabs-solid a {
            padding: 10px 30px;
        }
    </style>
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">{{ $title }}</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                <li class="breadcrumb-item active">Setting</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item"><a class="nav-link active" href="#solid-tab1" data-bs-toggle="tab">Header
                                    Image</a>
                            </li>
                        </ul>
                        <div class="tab-content px-3 py-5">
                            <div class="tab-pane show active" id="solid-tab1">
                                <form action="{{ route($routeHeader) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row" id="header">
                                        {!! $formHeader !!}
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="submit" class="btn btn-submit btn-sm" disabled><i
                                                    class="fa fa-save"></i>
                                                Simpan</button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-cancel btn-sm" id="hideForm"
                                                onclick="hideShowForm('#header')">Hide Form</button>
                                            <button type="button" class="btn btn-cancel bg-danger btn-sm"
                                                onclick="hapus_field('{{ url(route($routeHeader)) }}','#header')">Reset
                                                Form</button>
                                        </div>
                                    </div>
                                </form>
                                <hr style="height: 0.9px;background: black;">
                                <div class="mt-5">
                                    <table class="table table-hover table-light table-bordered">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th scope="col" class="text-center">No</th>
                                                @foreach ($tableHeader as $tab)
                                                    <th class="text-center">{{ $tab['title'] }}</th>
                                                @endforeach
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($dataHeader) > 0)
                                                @foreach ($dataHeader as $item)
                                                    <tr>
                                                        <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                                        @foreach ($tableHeader as $tab)
                                                            @php
                                                                $nameTable = $tab['name'];
                                                                $typeTable = $tab['type'];
                                                            @endphp
                                                            @if ($typeTable == 'datetime')
                                                                <td class="text-center">
                                                                    {{ Carbon\Carbon::parse($item->$nameTable)->isoFormat('DD MMMM YYYY') }}
                                                                </td>
                                                            @elseif($nameTable == 'image' || $nameTable == 'thumbnail' || $nameTable == 'logo')
                                                                <td class="text-center"><img
                                                                        src="{{ asset($item->$nameTable) }}"
                                                                        alt="{{ $item->$nameTable }}" width="100"
                                                                        class="img-fluid"></td>
                                                            @else
                                                                <td class="text-center">{{ $item->$nameTable }}</td>
                                                            @endif
                                                        @endforeach
                                                        <td>
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <button class="btn btn-primary mx-1" type="button"
                                                                    onclick="edit('{{ url(route($routeHeader)) . '/' . $item->id }}', '#header');">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </button>
                                                                <form method="POST"
                                                                    action="{{ url(route($routeHeader)) . '/' . $item->id }}"
                                                                    class="d-inline delete-confirm">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <button class="btn btn-danger mx-1">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">Belum ada data</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.components.loader')
@endsection

@section('script')
    <script>
        $(function() {
            $('button[type="submit"]').attr("disabled", false);
        });

        function hapus_field(url, id) {
            var inputElement = document.querySelectorAll(id + " input");
            for (var ii = 0; ii < inputElement.length; ii++) {
                const name = inputElement[ii].name
                const value = inputElement[ii].value
                const type = inputElement[ii].type
                if (name == "_token") {
                    inputElement[ii].value = value
                } else if (name == "_method") {
                    inputElement[ii].value = value
                } else if (name.includes('_id')) {
                    inputElement[ii].value = value
                } else {
                    inputElement[ii].value = "";
                }
                if (type == "checkbox") {
                    inputElement[ii].value = 1
                }
            }

            var selectElement = document.querySelectorAll(id + " select");
            for (var ii = 0; ii < selectElement.length; ii++) {
                const name = selectElement[ii].name
                selectElement[ii].value = 0;
            }

            var textAreaElement = document.querySelectorAll(id + " textarea");
            for (var ii = 0; ii < textAreaElement.length; ii++) {
                const name = textAreaElement[ii].name
                textAreaElement[ii].value = ""
            }
            $(`${id} > div:nth-child(2) > div > img`).fadeIn("slow").attr('src', "");
            $(id).parent().attr('action', url);
            $('input[name=_method]').val('POST');
        }

        function edit(url, id) {
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    console.log(response);
                    $.each(response, function(key, val) {
                        if (key == "role") {
                            $(id).find('#role').val(val)
                        } else if (key == "photo" || key == "image" || key == "thumbnail") {
                            $(`${id} > div:nth-child(2) > div > img`).fadeIn("slow").attr('src', val);
                        } else if (key == "parent_id") {
                            $(id).find(`option[value=${id}]`).attr('hidden', true)
                        } else if (key == "status") {
                            val ? $(id).find('input[type=checkbox]').attr('checked', true) : $(id).find(
                                'input[type=checkbox]').removeAttr('checked');
                            $(id).find(`input[name=status]`).val(val)
                        } else if (key == 'start_at' || key == 'end_at') {
                            $(id).find(`input[name=${key}]`).val(getDate(val))
                        } else {
                            $(id).find(`input[name=${key}]`).val(val)
                            $(id).find(`select[name=${key}]`).val(val)
                            $(id).find(`textarea[name=${key}]`).html(val)
                            $(id).find(`textarea[name=${key}]`).val(val)
                        }
                    });
                    $(id).parent().attr('action', url);
                    $('input[name=_method]').val('PUT');
                    $('#hideForm').html('Hide Form')
                    $(id).attr('hidden', false);
                    $('.btn-submit').attr('disabled', false);
                }
            });
        }

        function hideShowForm(id) {
            const hide = $(id).parent().find(id).attr('hidden');
            if (hide) {
                $(id).parent().find('div.d-flex.justify-content-between > div:nth-child(2) > #hideForm').html('Hide Form')
                // $('#hideForm').html('Hide Form')
                $(id).parent().find(id).attr('hidden', false);
                $(id).parent().find('div.d-flex.justify-content-between > div:nth-child(1) > .btn-submit').attr('disabled', false);
            } else {
                $(id).parent().find('div.d-flex.justify-content-between > div:nth-child(2) > #hideForm').html('Show Form')
                // $('#hideForm').html('Show Form')
                $(id).parent().find(id).attr('hidden', true);
                $(id).parent().find('div.d-flex.justify-content-between > div:nth-child(1) > .btn-submit').attr('disabled', true);
            }
        }
    </script>
@endsection
