@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card-row">
        <x-dg-small-box bg="success" title="Korisnici" text="{{ App\Models\User::count() }}" icon="fas fa-user"
            url="admin/users" id="userbox" />

        <x-dg-small-box bg="info" title="Artikli" text="{{ App\Models\Article::count() }}" icon="fas fa-hamburger"
            url="admin/menu" id="articlesbox" />
    </div>

    <div class="card-row">
        <x-dg-small-box bg="danger" title="Narudzbine na cekanju"
            text="{{ App\Models\Order::where('state', 'preparing')->count() }}" icon="fas fa-exclamation-triangle"
            url="admin/orders" id="ordersbox" />

        <x-dg-small-box bg="secondary" title="Izdate fakture" text="0" icon="fas fa-file" url="admin/reports"
            id="reportsbox" />
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .card-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 20px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
