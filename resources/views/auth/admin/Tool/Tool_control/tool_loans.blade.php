@extends('layouts.Master')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold text-primary mb-3">
            <i class="bi bi-box-arrow-up-right me-2"></i>Registrar Préstamo
        </h2>
        <p class="lead text-muted">Completa el formulario para registrar un nuevo préstamo de herramientas.</p>
    </div>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('loans.store') }}" method="POST">
                @csrf

                <!-- Herramienta -->
                <div class="mb-4">
                    <label for="tool_id" class="form-label fw-bold">Herramienta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-tools"></i></span>
                        <select name="tool_id" id="tool_id" class="form-select rounded-end" required>
                            <option value="">-- Selecciona una herramienta --</option>
                            @foreach ($items as $item)
                                @if ($item->amount > 0)
                                    <option value="{{ $item->id }}" {{ old('tool_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->product }} (Disponibles: {{ $item->amount }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @error('tool_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-bold">Cantidad</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                        <input type="number" name="quantity" id="quantity" class="form-control rounded-end"
                               min="1" required value="{{ old('quantity') }}" placeholder="Ingrese la cantidad">
                    </div>
                    @error('quantity')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Fecha de préstamo -->
                <div class="mb-4">
                    <label for="loan_date" class="form-label fw-bold">Fecha de Préstamo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-calendar3"></i></span>
                        <input type="date" name="loan_date" id="loan_date" class="form-control rounded-end" required
                               value="{{ old('loan_date', now()->format('Y-m-d')) }}">
                    </div>
                    @error('loan_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Nombre del solicitante -->
                <div class="mb-4">
                    <label for="requester_name" class="form-label fw-bold">Nombre del Solicitante</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-circle"></i></span>
                        <input type="text" name="requester_name" id="requester_name" class="form-control rounded-end"
                               required value="{{ old('requester_name') }}" placeholder="Ingrese el nombre del solicitante">
                    </div>
                    @error('requester_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Cédula del solicitante -->
                <div class="mb-4">
                    <label for="requester_id" class="form-label fw-bold">Cédula del Solicitante</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-vcard"></i></span>
                        <input type="text" name="requester_id" id="requester_id" class="form-control rounded-end"
                               required value="{{ old('requester_id') }}" placeholder="Ingrese la cédula">
                    </div>
                    @error('requester_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Estado del préstamo -->
                <div class="mb-4">
                    <label for="loan_status" class="form-label fw-bold">Estado o Descripción del Préstamo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-chat-left-text"></i></span>
                        <textarea name="loan_status" id="loan_status" class="form-control rounded-end" rows="4"
                                  placeholder="Describe el estado del préstamo (opcional)">{{ old('loan_status') }}</textarea>
                    </div>
                    @error('loan_status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Entregado por (oculto) -->
                <input type="hidden" name="delivered_by" value="{{ auth()->user()->name }}">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg px-4 py-2 shadow-sm">
                        <i class="bi bi-save me-2"></i>Guardar Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</div>
@endsection
