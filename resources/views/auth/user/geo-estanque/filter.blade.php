@extends('layouts.master')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Tarjeta principal -->
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #007bff;">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-dark">
                            <i class="fas fa-table mr-2"></i> Lista de Geomembranas
                        </h3>
                        <a href="{{ route('geo.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar módulo
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <!-- Tabla de geomembranas -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Identificador</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($filtros2 as $item2)
                                    <tr>
                                        <td>{{ $item2['pond_name'] }}</td>
                                        <td>
                                            @if(!empty($item2['identificadores']))
                                                {{ implode(', ', $item2['identificadores']) }}
                                            @else
                                                <span class="text-muted">Sin identificadores</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje si no hay datos -->
                        @if(empty($filtros2) || count($filtros2) == 0)
                            <div class="alert alert-info mt-3" role="alert">
                                <i class="fas fa-info-circle mr-2"></i> No hay geomembranas registradas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Estilos personalizados para la tabla */
    .table {
        border-radius: 6px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody tr {
        transition: background-color 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: #f1f3f5;
    }
    .table td, .table th {
        vertical-align: middle;
        padding: 12px;
    }
    .text-muted {
        font-style: italic;
    }
    .card {
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection
