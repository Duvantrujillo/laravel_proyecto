@extends('layouts.master')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f4f6f9] px-4 py-10">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-8 border border-gray-200">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-[#2c3e50]">Registrar Tipo de Especie</h2>
            <p class="text-sm text-[#7f8c8d] mt-1">Completa la información con claridad.</p>
        </div>

        <form action="{{ route('types.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="species_id" class="block text-sm font-medium text-[#2c3e50] mb-1">
                    Especie existente
                </label>
                <select name="species_id" id="species_id" required
                    class="w-full rounded-md border border-gray-300 bg-gray-50 text-gray-800 py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-[#3498db] shadow-sm transition">
                    <option value="">Selecciona una especie</option>
                    @foreach ($species as $specie)
                        <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                    @endforeach
                </select>
                @error('species_id')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-[#2c3e50] mb-1">
                    Nombre del tipo
                </label>
                <input type="text" name="name" id="name" required
                    placeholder="Ej: Tilapia Roja"
                    class="w-full rounded-md border border-gray-300 bg-gray-50 text-gray-800 py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-[#3498db] shadow-sm transition">
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3">
                <button type="submit"
                    class="w-full py-2.5 rounded-md bg-[#3498db] hover:bg-[#2980b9] text-white font-medium shadow-md transition-all duration-300">
                    <i class="fas fa-save mr-2"></i> Guardar tipo de especie
                </button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endsection
