@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Meni</h1>
@stop

@section('content')
    {{-- Setup data for datatables --}}
    @php
        $heads = ['ID', 'First name', 'Last name', 'Email', 'Photo', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

        $data = App\Models\User::all()->map(function ($user) {
            $btnEdit =
                '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" data-toggle="modal" data-target="#modalEdit" data-user-id="' .
                $user->id .
                '" data-user-first-name="' .
                $user->first_name .
                '" data-user-last-name="' .
                $user->last_name .
                '" data-user-email="' .
                $user->email .
                '">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
            $btnDelete =
                '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" data-toggle="modal" data-target="#modalDelete" data-user-id="' .
                $user->id .
                '" data-user-name="' .
                $user->first_name .
                ' ' .
                $user->last_name .
                '">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';

            $actions = '<nobr>' . $btnEdit . $btnDelete . '</nobr>';

            return [$user->id, $user->first_name, $user->last_name, $user->email, '<img width="80" src="' . URL::asset('photos/' . $user->photo) . '">', $actions];
        });

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, ['orderable' => false]],
        ];
    @endphp

    @if (Session::has('message'))
        <div role="alert" class="alert alert-success alert-dismissible">
            <p>{{ Session::get('message') }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <button class="btn btn-primary" id="buttonCreateArticle" data-toggle="modal" data-target="#modalCreate">CREATE NEW
        USER</button>

    {{-- Compressed with style options / fill data using the plugin config --}}
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed />


    <x-adminlte-modal id="modalDelete" title="Delete user">
        <p></p>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
            <x-adminlte-button id="modalDeleteButtonDelete" theme="danger" label="DELETE" />

            <form method="POST" action="/admin/users/delete" id="modalDeleteForm">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="modalDeleteId">
            </form>
        </x-slot>
    </x-adminlte-modal>

    <x-adminlte-modal id="modalCreate" title="Create new user" size="lg" scrollable>
        <form method="POST" action="/admin/users/create" id="modalCreateForm" enctype="multipart/form-data">
            {{ csrf_field() }}

            <label for="modalCreateFirstName">First name</label>
            <input type="text" name="first_name" id="modalCreateFirstName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalCreateErrorFirstName"></div>

            <label for="modalCreateLastName">Last name</label>
            <input type="text" name="last_name" id="modalCreateLastName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalCreateErrorLastName"></div>

            <label for="modalCreateEmail">Email</label>
            <input type="email" name="email" id="modalCreateEmail">
            <div class="error-message" id="modalCreateErrorEmail"></div>

            <label for="modalCreate">Photo</label>
            <input type="file" name="photo" id="modalCreatePhoto">
            <div class="error-message" id="modalCreateErrorPhoto"></div>
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
            <x-adminlte-button id="modalCreateButtonCreate" theme="success" label="CREATE" />
        </x-slot>
    </x-adminlte-modal>

    <x-adminlte-modal id="modalEdit" title="Edit user" size="lg" scrollable>
        <form method="POST" action="/admin/users/edit" id="modalEditForm" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="modalEditId">

            <label for="modalEditFirstName">New first name</label>
            <input type="text" name="first_name" id="modalEditFirstName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalEditErrorFirstName"></div>

            <label for="modalEditLastName">New last name</label>
            <input type="text" name="last_name" id="modalEditLastName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalEditErrorLastName"></div>

            <label for="modalEditEmail">New email</label>
            <input type="email" name="email" id="modalEditEmail">
            <div class="error-message" id="modalEditErrorEmail"></div>

            <label for="modalEdit">New photo</label>
            <input type="file" name="photo" id="modalEditPhoto">
            <div class="error-message" id="modalEditErrorPhoto"></div>
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
            <x-adminlte-button id="modalEditButtonOk" theme="primary" label="OK" />
        </x-slot>
    </x-adminlte-modal>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/css/admin_custom.css">

    <style>
        #buttonCreateArticle {
            margin-bottom: 20px;
        }

        #modalCreateForm {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #modalCreateForm input,
        #modalCreateForm textarea {
            margin-bottom: 10px;
        }

        #modalEditForm {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #modalEditForm input,
        #modalEditForm textarea {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $('#modalDelete').on('shown.bs.modal', function(e) {
            let id = e.relatedTarget.getAttribute("data-user-id");
            let name = e.relatedTarget.getAttribute("data-user-name");

            $("#modalDelete p").html(`Are you sure you want to delete "<em>${name}</em>"?`);

            $("#modalDeleteButtonDelete").click(function() {
                $("#modalDeleteId").val(id);
                $("#modalDeleteForm").submit();
            })
        })

        $('#modalEdit').on('shown.bs.modal', function(e) {
            let id = e.relatedTarget.getAttribute("data-user-id");
            let firstName = e.relatedTarget.getAttribute("data-user-first-name");
            let lastName = e.relatedTarget.getAttribute("data-user-last-name");
            let email = e.relatedTarget.getAttribute("data-user-email");

            $("#modalEditId").val(id);
            $("#modalEditFirstName").val(firstName);
            $("#modalEditLastName").val(lastName);
            $("#modalEditEmail").val(email);

            $("#modalEditButtonOk").click(function() {
                e.preventDefault();

                let firstName = $("#modalEditFirstName").val();
                let lastName = $("#modalEditLastName").val();
                let email = $("#modalEditEmail").val();
                let photo = $("#modalEditPhoto").prop('files')[0];


                let passed = true;

                if (!firstName) {
                    $("#modalEditErrorFirstName").text("This field is required.")
                    passed = false;
                } else {
                    $("#modalEditErrorFirstName").text("")
                }

                if (!lastName) {
                    $("#modalEditErrorLastName").text("This field is required.")
                    passed = false;
                } else {
                    $("#modalEditErrorLastName").text("")
                }

                if (!email) {
                    $("#modalEditErrorEmail").text("This field is required.")
                    passed = false;
                } else {
                    $("#modalEditErrorEmail").text("")
                }


                // don't allow uploading files which aren't images as profile pics (documents, zips, etc.)
                if (photo && photo.type && !photo.type.startsWith("image")) {
                    $("#modalEditErrorPhoto").text("Uploaded photo needs to be an image file.")
                    passed = false;
                } else {
                    $("#modalEditErrorPhoto").text("")
                }

                if (passed) {
                    $("#modalEditForm").submit();
                }
            })
        })

        $("#modalCreateButtonCreate").click(function(e) {
            e.preventDefault();

            let firstName = $("#modalCreateFirstName").val();
            let lastName = $("#modalCreateLastName").val();
            let email = $("#modalCreateEmail").val();
            let photo = $("#modalCreatePhoto").prop('files')[0];

            let passed = true;

            if (!firstName) {
                $("#modalCreateErrorFirstName").text("This field is required.")
                passed = false;
            } else {
                $("#modalCreateErrorFirstName").text("")
            }

            if (!lastName) {
                $("#modalCreateErrorLastName").text("This field is required.")
                passed = false;
            } else {
                $("#modalCreateErrorLastName").text("")
            }

            if (!email) {
                $("#modalCreateErrorEmail").text("This field is required.")
                passed = false;
            } else {
                $("#modalCreateErrorEmail").text("")
            }


            // don't allow uploading files which aren't images as profile pics (documents, zips, etc.)
            if (photo && photo.type && !photo.type.startsWith("image")) {
                $("#modalEditErrorPhoto").text("Uploaded photo needs to be an image file.")
                passed = false;
            } else {
                $("#modalEditErrorPhoto").text("")
            }

            if (passed) {
                $("#modalCreateForm").submit();
            }
        })
    </script>
@stop
