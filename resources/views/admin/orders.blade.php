@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Meni</h1>
@stop

@section('content')
    {{-- Setup data for datatables --}}
    @php
        $heads = ['ID', 'User', 'Date', 'Comment', 'State', ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

        $data = App\Models\Order::all()->map(function ($order) {
            $btnEdit =
                '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" data-toggle="modal" data-target="#modalEdit" data-order-id="' .
                $order->id .
                '" data-order-state="' .
                $order->state .
                '">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';

            $actions = '<nobr>' . $btnEdit . '</nobr>';

            $orderState = '';
            switch ($order->state) {
                case 'preparing':
                    $orderState = 'Preparing';
                    break;
                case 'on_the_way':
                    $orderState = 'On the way';
                    break;
                case 'delivered':
                    $orderState = 'Delivered';
                    break;
                default:
                    $orderState = $order->state;
            }

            return [$order->id, App\Models\User::find($order->user_id)->first_name . ' ' . App\Models\User::find($order->user_id)->last_name, $order->date, $order->comment, $orderState, $actions];
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

    {{-- Compressed with style options / fill data using the plugin config --}}
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed />



    <x-adminlte-modal id="modalEdit" title="Edit order" size="lg" scrollable>
        <form method="POST" action="/admin/orders/edit" id="modalEditForm" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="modalEditId">

            <label for="modalEditState">New state</label>
            <select name="state" id="modalEditState">
                <option value="preparing" selected="selected">Preparing</option>
                <option value="on_the_way">On the way</option>
                <option value="delivered">Delivered</option>
            </select>
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
        $('#modalEdit').on('shown.bs.modal', function(e) {
            let id = e.relatedTarget.getAttribute("data-order-id");
            let state = e.relatedTarget.getAttribute("data-order-state");

            $("#modalEditId").val(id);

            $("#modalEditButtonOk").click(function() {
                e.preventDefault();

                let state = $("#modalEditState").val();

                $("#modalEditForm").submit();
            })
        })
    </script>
@stop
