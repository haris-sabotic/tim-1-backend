@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Meni</h1>
@stop

@section('content')
    {{-- Setup data for datatables --}}
    @php
        $heads = ['ID', 'Name', 'Description', 'Ingredients', 'Photo', 'Price', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

        $data = App\Models\Article::all()->map(function ($article) {
            $btnEdit =
                '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" data-toggle="modal" data-target="#modalEdit" data-article-id="' .
                $article->id .
                '" data-article-name="' .
                $article->name .
                '" data-article-price="' .
                $article->price .
                '" data-article-description="' .
                $article->description .
                '" data-article-ingredients="' .
                $article->ingredients .
                '">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
            $btnDelete =
                '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" data-toggle="modal" data-target="#modalDelete" data-article-id="' .
                $article->id .
                '" data-article-name="' .
                $article->name .
                '">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';

            $actions = '<nobr>' . $btnEdit . $btnDelete . '</nobr>';

            return [$article->id, $article->name, $article->description, $article->ingredients, '<img width="80" src="' . URL::asset('photos/' . $article->photo) . '">', $article->price, $actions];
        });

        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
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
        ARTICLE</button>

    {{-- Compressed with style options / fill data using the plugin config --}}
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed />


    <x-adminlte-modal id="modalDelete" title="Delete article">
        <p></p>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
            <x-adminlte-button id="modalDeleteButtonDelete" theme="danger" label="DELETE" />

            <form method="POST" action="/admin/menu/delete" id="modalDeleteForm">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="modalDeleteArticleId">
            </form>
        </x-slot>
    </x-adminlte-modal>

    <x-adminlte-modal id="modalCreate" title="Create new article" size="lg" scrollable>
        <form method="POST" action="/admin/menu/create" id="modalCreateForm" enctype="multipart/form-data">
            {{ csrf_field() }}

            <label for="modalCreateArticleName">Name</label>
            <input type="text" name="name" id="modalCreateArticleName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalCreateErrorName"></div>

            <label for="modalCreateArticlePrice">Price</label>
            <input type="number" name="price" id="modalCreateArticlePrice" min="0" step="0.1">
            <div class="error-message" id="modalCreateErrorPrice"></div>

            <label for="modalCreateArticleDescription">Description</label>
            <textarea name="description" id="modalCreateArticleDescription" cols="30" rows="10"></textarea>
            <div class="error-message" id="modalCreateErrorDescription"></div>

            <label for="modalCreateArticleIngredients">Ingredients</label>
            <textarea name="ingredients" id="modalCreateArticleIngredients" cols="30" rows="10"></textarea>
            <div class="error-message" id="modalCreateErrorDescription"></div>

            <label for="modalCreateArticlePhoto">Photo</label>
            <input type="file" name="photo" id="modalCreateArticlePhoto">
            <div class="error-message" id="modalCreateErrorPhoto"></div>
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancel" data-dismiss="modal" />
            <x-adminlte-button id="modalCreateButtonCreate" theme="success" label="CREATE" />
        </x-slot>
    </x-adminlte-modal>

    <x-adminlte-modal id="modalEdit" title="Edit article" size="lg" scrollable>
        <form method="POST" action="/admin/menu/edit" id="modalEditForm" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="modalEditArticleId">

            <label for="modalEditArticleName">New name</label>
            <input type="text" name="name" id="modalEditArticleName">
            <!-- invalid-message is empty by default and gets filled in with an error message if something went wrong on submit -->
            <div class="error-message" id="modalEditErrorName"></div>

            <label for="modalEditArticlePrice">New price</label>
            <input type="number" name="price" id="modalEditArticlePrice" min="0" step="0.1">
            <div class="error-message" id="modalEditErrorPrice"></div>

            <label for="modalEditArticleDescription">New description</label>
            <textarea name="description" id="modalEditArticleDescription" cols="30" rows="10"></textarea>
            <div class="error-message" id="modalEditErrorDescription"></div>

            <label for="modalEditArticleIngredients">New ingredients</label>
            <textarea name="ingredients" id="modalEditArticleIngredients" cols="30" rows="10"></textarea>
            <div class="error-message" id="modalEditErrorDescription"></div>

            <label for="modalEditArticlePhoto">New photo</label>
            <input type="file" name="photo" id="modalEditArticlePhoto">
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
            let id = e.relatedTarget.getAttribute("data-article-id");
            let name = e.relatedTarget.getAttribute("data-article-name");

            $("#modalDelete p").html(`Are you sure you want to delete "<em>${name}</em>"?`);

            $("#modalDeleteButtonDelete").click(function() {
                $("#modalDeleteArticleId").val(id);
                $("#modalDeleteForm").submit();
            })
        })

        $('#modalEdit').on('shown.bs.modal', function(e) {
            let id = e.relatedTarget.getAttribute("data-article-id");
            let name = e.relatedTarget.getAttribute("data-article-name");
            let price = e.relatedTarget.getAttribute("data-article-price");
            let description = e.relatedTarget.getAttribute("data-article-description");
            let ingredients = e.relatedTarget.getAttribute("data-article-ingredients");

            $("#modalEditArticleId").val(id);
            $("#modalEditArticleName").val(name);
            $("#modalEditArticlePrice").val(price);
            $("#modalEditArticleDescription").val(description);
            $("#modalEditArticleIngredients").val(ingredients);

            $("#modalEditButtonOk").click(function() {
                e.preventDefault();

                let name = $("#modalEditArticleName").val();
                let price = $("#modalEditArticlePrice").val();
                let description = $("#modalEditArticleDescription").val();
                let ingredients = $("#modalEditArticleIngredients").val();
                let photo = $("#modalEditArticlePhoto").prop('files')[0];

                let passed = true;

                if (!price) {
                    $("#modalEditErrorPrice").text("This field is required.")
                    passed = false;
                } else {
                    $("#modalEditErrorPrice").text("")
                }

                if (!name) {
                    $("#modalEditErrorName").text("This field is required.")
                    passed = false;
                } else {
                    $("#modalEditErrorName").text("")
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

            let name = $("#modalCreateArticleName").val();
            let price = $("#modalCreateArticlePrice").val();
            let description = $("#modalCreateArticleDescription").val();
            let ingredients = $("#modalCreateArticleIngredients").val();
            let photo = $("#modalCreateArticlePhoto").prop('files')[0];

            let passed = true;

            if (!price) {
                $("#modalCreateErrorPrice").text("This field is required.")
                passed = false;
            } else {
                $("#modalCreateErrorPrice").text("")
            }

            if (!name) {
                $("#modalCreateErrorName").text("This field is required.")
                passed = false;
            } else {
                $("#modalCreateErrorName").text("")
            }

            // don't allow uploading files which aren't images as profile pics (documents, zips, etc.)
            if (photo && photo.type && !photo.type.startsWith("image")) {
                $("#modalCreateErrorPhoto").text("Uploaded photo needs to be an image file.")
                passed = false;
            } else {
                $("#modalCreateErrorPhoto").text("")
            }

            if (passed) {
                $("#modalCreateForm").submit();
            }
        })
    </script>
@stop
