@extends('layouts.master')

@section('title', 'Grupos y Fichas')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Card para Grupos y Fichas -->
                <div class="card card-outline card-primary elevation-2" style="border-top: 3px solid #4b5e82;">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="card-title text-dark"><i class="fas fa-users mr-2"></i> Tecnologos y Fichas Existentes</h3>
                    </div>
                    <div class="card-body p-4">
                        @if($grupos->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i> No hay grupos registrados.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tecnologos</th>
                                            <th>Fichas Asociadas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupos as $grupo)
                                            <tr>
                                                <td>{{ $grupo->nombre }}</td>
                                                <td>
                                                    @if(isset($fichasPorGrupo[$grupo->id]['numeros']) && !empty($fichasPorGrupo[$grupo->id]['numeros']))
                                                        @foreach($fichasPorGrupo[$grupo->id]['numeros'] as $numero)
                                                            <span class="badge badge-secondary mr-1" style="background-color: #6c757d;">{{ $numero }}</span>
                                                        @endforeach
                                                    @else
                                                        Ninguna
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Resaltar el menú activo
        $('.nav-sidebar a[href="{{ route('grupos-fichas.index') }}"]').addClass('active').parents('.nav-item.has-treeview').addClass('menu-open');
    });
</script>
@endsection