@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="text-center mb-4">
            <h1 class="h3 fw-semibold text-gray-800 mb-2">
                <i class="bi bi-clock-history me-2 text-primary"></i>Historial Completo de Préstamos y Devoluciones
            </h1>
            <p class="text-muted small">Registro detallado de todos los movimientos de herramientas</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div class="small">{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Estadísticas Resumen -->
        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-2">
                        <h3 class="h5 fw-bold text-primary mb-0">{{ $activeLoans->count() }}</h3>
                        <p class="text-muted small mb-0"><i class="bi bi-tools me-1"></i>Préstamos Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-2">
                        @php
                            use Carbon\Carbon;

                            // Filtrar devoluciones completadas solo del día actual (desde medianoche)
                            $todayCompleted = $completedReturns->filter(function ($return) {
                                return Carbon::parse($return->return_date)->isSameDay(Carbon::now());
                            });

                            // Contar usuarios únicos (requester_id) con devoluciones hoy
                            $countToday = $todayCompleted->groupBy(fn($r) => $r->loan->requester_id)->count();
                        @endphp

                        <h3 class="h5 fw-bold text-success mb-0">
                            {{ $countToday }}
                        </h3>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-check-circle me-1"></i>Personas con devolución completa (hoy)
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-2">
                        @php
                            $todayReturns = $returns->filter(function ($return) {
                                return \Carbon\Carbon::parse($return->return_date)->isSameDay(\Carbon\Carbon::now());
                            });

                            $countTodayReturns = $todayReturns->count();
                        @endphp

                        <h3 class="h5 fw-bold text-secondary mb-0">{{ $countTodayReturns }}</h3>
                        <p class="text-muted small mb-0"><i class="bi bi-archive me-1"></i>Total Devoluciones (hoy)</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pestañas de Navegación Mejoradas -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button"
                    role="tab" aria-controls="active" aria-selected="true">
                    <i class="bi bi-tools me-2"></i>Préstamos Activos
                    <span class="badge bg-primary-soft text-primary ms-2">{{ $activeLoans->count() }}</span>
                </button>
            </li>
            @php
                $todayCompletedCount = $completedReturns
                    ->filter(function ($return) {
                        return \Carbon\Carbon::parse($return->return_date)->isSameDay(\Carbon\Carbon::now());
                    })
                    ->groupBy('loan.requester_id')
                    ->count();
            @endphp

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button"
                    role="tab" aria-controls="completed" aria-selected="false">
                    <i class="bi bi-check2-all me-2"></i>Completas
                    <span class="badge bg-success-soft text-success ms-2">{{ $todayCompletedCount }}</span>
                </button>
            </li>

            @php
                $todayReturnsCount = $returns
                    ->filter(function ($return) {
                        return \Carbon\Carbon::parse($return->return_date)->isSameDay(\Carbon\Carbon::now());
                    })
                    ->count();
            @endphp

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
                    role="tab" aria-controls="history" aria-selected="false">
                    <i class="bi bi-archive me-2"></i>Historial
                    <span class="badge bg-secondary-soft text-secondary ms-2">{{ $todayReturnsCount }}</span>
                </button>
            </li>

        </ul>

        <!-- Contenido de las Pestañas -->
        <div class="tab-content" id="myTabContent">
            <!-- Pestaña 1: Préstamos Activos -->
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-header bg-gray-100 border-0 py-2 d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-semibold text-gray-800 mb-0">
                            <i class="bi bi-tools me-1 text-warning"></i>Préstamos Activos
                        </h2>
                        <div>
                            <span class="badge bg-primary rounded-pill me-1 small">{{ $activeLoans->count() }}
                                activos</span>
                            <span
                                class="badge bg-danger rounded-pill small">{{ $activeLoans->sum('quantity') - $activeLoans->sum('returned_quantity') }}
                                pendientes</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="small">Herramienta</th>
                                        <th scope="col" class="small">Prestado</th>
                                        <th scope="col" class="small">Devuelto</th>
                                        <th scope="col" class="small">Pendiente</th>
                                        <th scope="col" class="small">Fecha Préstamo</th>
                                        <th scope="col" class="small">Solicitante</th>
                                        <th scope="col" class="small">Cédula</th>
                                        <th scope="col" class="small">Entregado por</th>
                                        <th scope="col" class="small pe-3">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @forelse ($activeLoans->sortByDesc('created_at') as $loan)
                                        <tr class="border-bottom border-gray-200">
                                            <td class="small">{{ $loan->item }}</td>
                                            <td class="small">{{ $loan->quantity }}</td>
                                            <td class="small">{{ $loan->returned_quantity }}</td>
                                            <td class="small text-danger fw-semibold">
                                                {{ $loan->quantity - $loan->returned_quantity }}</td>
                                            <td class="small">
                                                {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') }}

                                            <td class="small">{{ $loan->requester_name }}</td>
                                            <td class="small">{{ $loan->requester_id }}</td>
                                            <td class="small">{{ $loan->deliveredBy?->name }}
                                                {{ $loan->deliveredBy?->last_name }}</td>

                                            <td class="pe-3">
                                                <span
                                                    class="badge bg-warning-soft text-warning rounded-pill px-2 py-1 small">
                                                    <i class="bi bi-hourglass-split me-1"></i>{{ $loan->loan_status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted small py-3">
                                                <i class="bi bi-inbox me-1"></i>No hay herramientas prestadas actualmente
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña 2: Devoluciones Completas -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-header bg-gray-100 border-0 py-2 d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-semibold text-gray-800 mb-0">
                            <i class="bi bi-check2-all me-1 text-success"></i>Personas con Devoluciones Completas
                        </h2>
                        <div>
                            <span
                                class="badge bg-success rounded-pill me-1 small">{{ $completedReturns->groupBy('loan.requester_id')->count() }}
                                personas</span>
                            <span
                                class="badge bg-info rounded-pill small">{{ $completedReturns->sum('quantity_returned') }}
                                unidades</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="custom-accordion" id="completedReturnsAccordion">
                            @php
                                $groupedReturns = $completedReturns
                                    ->sortByDesc('created_at')
                                    ->groupBy('loan.requester_id');
                            @endphp

                            @forelse ($groupedReturns as $requesterId => $groupReturns)
                                @php
                                    $firstReturn = $groupReturns->first();
                                    $personName = $firstReturn->loan->requester_name;
                                    $personId = $firstReturn->loan->requester_id;
                                    $totalItems = $groupReturns->count();
                                    $totalToolsReturned = $groupReturns->sum('quantity_returned');
                                @endphp

                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $requesterId }}">
                                        <button class="custom-accordion-button bg-gray-50 py-2 small" type="button"
                                            data-target="collapse{{ $requesterId }}">
                                            <div class="d-flex justify-content-between w-100 pe-2">
                                                <div>
                                                    <span class="fw-semibold">{{ $personName }}</span>
                                                    <span class="text-muted ms-1">({{ $personId }})</span>
                                                </div>
                                                <div>
                                                    <span
                                                        class="badge bg-primary rounded-pill me-1 small">{{ $totalItems }}
                                                        préstamos</span>
                                                    <span
                                                        class="badge bg-success rounded-pill small">{{ $totalToolsReturned }}
                                                        unidades</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $requesterId }}" class="custom-accordion-content">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover mb-0">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th scope="col" class="small">Herramienta</th>
                                                            <th scope="col" class="small">Prestado</th>
                                                            <th scope="col" class="small">Devuelto</th>
                                                            <th scope="col" class="small">Fechas de Prestamos final
                                                            </th>

                                                            <th scope="col" class="small">Duración</th>
                                                            <th scope="col" class="small">Responsables</th>
                                                            <th scope="col" class="small">Estado De la Prestamo </th>
                                                            <th scope="col" class="small">Estado De la Devolucion</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($groupReturns->sortByDesc('created_at') as $return)
                                                            @php
                                                                $loanDate = \Carbon\Carbon::parse(
                                                                    $return->loan->loan_date,
                                                                );
                                                                $returnDate = \Carbon\Carbon::parse(
                                                                    $return->return_date,
                                                                );
                                                                $duration = $loanDate->diff($returnDate);
                                                            @endphp
                                                            <tr class="border-bottom border-gray-200">
                                                                <td class="small">{{ $return->loan->item }}</td>
                                                                <td class="small">{{ $return->loan->quantity }}</td>
                                                                <td class="small">{{ $return->quantity_returned }}</td>
                                                                <td>
                                                                    <button type="button" class="bi bi-clock-fill"
                                                                        data-bs-toggle="modal" data-bs-target="#dateModal"
                                                                        data-loandate="{{ $loanDate->format('d/m/Y H:i') }}"
                                                                        data-returndate="{{ $returnDate->format('d/m/Y H:i') }}">

                                                                    </button>
                                                                </td>


                                                                <td class="small">
                                                                    @if ($duration->d > 0)
                                                                        {{ $duration->d }} días
                                                                    @else
                                                                        {{ $duration->h }} horas
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="bi bi-file-earmark-person-fill"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deliveryModal"
                                                                        data-delivered-name="{{ $return->loan->deliveredBy?->name }} {{ $return->loan->deliveredBy?->last_name }}"
                                                                        data-received-name="{{ $return->receivedBy?->name }} {{ $return->receivedBy?->last_name }}">

                                                                    </button>
                                                                </td>

                                                                <td class="small">


                                                                    <button type="button" class="bi bi-clipboard-fill"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#exampleModalCenter"
                                                                        data-loanstatus="{{ $return->loan->loan_status }}"
                                                                        data-loanid="{{ $return->loan->id }}">

                                                                    </button>

                                                                </td>




                                                                <td>
                                                                    <span class="bi bi-clipboard-fill" role="button"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#returnStatusModal"
                                                                        data-returnstatus="{{ $return->return_status }}"
                                                                        data-loanid="{{ $return->loan_id }}">

                                                                    </span>
                                                                    @if (!empty($return->imge_path))
                                                                        <button type="button" data-bs-toggle="modal"
                                                                            data-bs-target="#imageModal"
                                                                            data-img="{{ asset('storage/' . $return->imge_path) }}"
                                                                            data-title="Evidencia ">
                                                                            <i class="bi bi-card-image"></i>
                                                                        </button>
                                                                    @endif


                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-3">
                                    <i class="bi bi-inbox me-1"></i>No hay personas con devoluciones completas registradas
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal único -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Imagen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                      <div class="modal-body text-center">
    <img id="modalImage" src="" alt="Imagen devolución" class="img-fluid d-block mx-auto rounded" style="width: 200px;" />
</div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>
                </div>
            </div>

            <script>
                const imageModal = document.getElementById('imageModal');
                imageModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const imgSrc = button.getAttribute('data-img');
                    const title = button.getAttribute('data-title');

                    const image = imageModal.querySelector('#modalImage');
                    const modalTitle = imageModal.querySelector('.modal-title');

                    image.src = imgSrc;
                    modalTitle.textContent = title;
                });
            </script>




            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Detalle del Préstamo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Estado:</strong> <span id="modalLoanStatus"></span></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>
                </div>
            </div>
            <script>
                const modal = document.getElementById('exampleModalCenter');
                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    // Capturamos los valores de los data-atributos
                    const loanStatus = button.getAttribute('data-loanstatus');
                    const loanId = button.getAttribute('data-loanid');

                    // Insertamos los datos en el modal
                    document.getElementById('modalLoanStatus').textContent = loanStatus;
                });
            </script>


            <div class="modal fade" id="returnStatusModal" tabindex="-1" aria-labelledby="returnStatusModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="returnStatusModalLabel">Estado de Devolución</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Estado de devolución:</strong> <span id="modalReturnStatus"></span></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>
                </div>
            </div>




            <script>
                const returnModal = document.getElementById('returnStatusModal');
                returnModal.addEventListener('show.bs.modal', function(event) {
                    const trigger = event.relatedTarget;
                    const returnStatus = trigger.getAttribute('data-returnstatus');
                    const loanId = trigger.getAttribute('data-loanid');

                    document.getElementById('modalReturnStatus').textContent = returnStatus;
                });
            </script>



            <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="deliveryModalLabel">Información de Entrega y Recepción</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Entregado por:</strong> <span id="deliveredByName"></span></p>
                            <p><strong>Recibido por:</strong> <span id="receivedByName"></span></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>
                </div>
            </div>


            <script>
                const deliveryModal = document.getElementById('deliveryModal');
                deliveryModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const deliveredName = button.getAttribute('data-delivered-name');
                    const receivedName = button.getAttribute('data-received-name');

                    document.getElementById('deliveredByName').textContent = deliveredName || 'No disponible';
                    document.getElementById('receivedByName').textContent = receivedName || 'No disponible';
                });
            </script>



            <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="dateModalLabel">Fechas del Préstamo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Fecha del Préstamo:</strong> <span id="loanDateSpan"></span></p>
                            <p><strong>Fecha de Devolución:</strong> <span id="returnDateSpan"></span></p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>
                </div>
            </div>
            <script>
                const dateModal = document.getElementById('dateModal');
                dateModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const loanDate = button.getAttribute('data-loandate');
                    const returnDate = button.getAttribute('data-returndate');

                    document.getElementById('loanDateSpan').textContent = loanDate || 'No disponible';
                    document.getElementById('returnDateSpan').textContent = returnDate || 'No disponible';
                });
            </script>










            <!-- Pestaña 3: Historial Completo -->
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-header bg-gray-100 border-0 py-2 d-flex justify-content-between align-items-center">
                        <h2 class="h6 fw-semibold text-gray-800 mb-0">
                            <i class="bi bi-archive me-1 text-secondary"></i>Historial Completo de Devoluciones
                        </h2>
                        <div>
                            <span class="badge bg-secondary rounded-pill me-1 small">{{ $returns->count() }}
                                registros</span>
                            <span class="badge bg-primary rounded-pill small">{{ $returns->sum('quantity_returned') }}
                                devueltas</span>
                            <span
                                class="badge bg-danger rounded-pill small">{{ $returns->sum(function ($return) {return $return->loan->quantity - $return->quantity_returned;}) }}
                                pendientes</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="small">Herramienta</th>
                                        <th scope="col" class="small">Prestado</th>
                                        <th scope="col" class="small">Devuelto</th>
                                        <th scope="col" class="small">Pendiente</th>
                                        <th scope="col" class="small">Fecha Préstamo</th>
                                        <th scope="col" class="small">Fecha Devolución</th>
                                        <th scope="col" class="small">Solicitante</th>
                                        <th scope="col" class="small">Cédula</th>
                                        <th scope="col" class="small">Entregado por</th>
                                        <th scope="col" class="small">Recibido por</th>
                                        <th scope="col" class="small">Estado Préstamo</th>
                                        <th scope="col" class="small pe-3">Estado Devolución</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @forelse ($returns->sortByDesc('created_at') as $return)
                                        <tr class="border-bottom border-gray-200">
                                            <td class="small">{{ $return->loan->item }}</td>
                                            <td class="small">{{ $return->loan->quantity }}</td>
                                            <td class="small">{{ $return->quantity_returned }}</td>
                                            <td
                                                class="small {{ $return->loan->quantity - $return->quantity_returned > 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                                {{ $return->loan->quantity - $return->quantity_returned }}
                                            </td>
                                            <td class="small">
                                                {{ \Carbon\Carbon::parse($return->loan->loan_date)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="small">
                                                {{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y H:i') }}
                                            </td>

                                            <td class="small">{{ $return->loan->requester_name }}</td>
                                            <td class="small">{{ $return->loan->requester_id }}</td>
                                            <td class="small">{{ $return->loan->deliveredBy?->name }}
                                                {{ $return->loan->deliveredBy?->last_name }}</td>

                                            <td class="small">{{ $return->receivedBy?->name }}
                                                {{ $return->receivedBy?->last_name }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $return->loan->loan_status == 'Completado' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning' }} rounded-pill px-2 py-1 small">
                                                    {{ $return->loan->loan_status }}
                                                </span>
                                            </td>
                                            <td class="pe-3">
                                                @if ($return->loan->quantity - $return->quantity_returned === 0)
                                                    <span
                                                        class="badge bg-success-soft text-success rounded-pill px-2 py-1 small">
                                                        <i class="bi bi-check2-all me-1"></i>{{ $return->return_status }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-warning-soft text-warning rounded-pill px-2 py-1 small">
                                                        <i
                                                            class="bi bi-exclamation-triangle me-1"></i>{{ $return->return_status }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted small py-3">
                                                <i class="bi bi-inbox me-1"></i>No hay registros de devoluciones
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estilos personalizados mejorados -->
        <style>
            :root {
                --bs-primary: #4e73df;
                --bs-success: #1cc88a;
                --bs-warning: #f6c23e;
                --bs-secondary: #858796;
                --bs-gray-100: #f8f9fc;
                --bs-gray-200: #e3e6f0;
                --bs-gray-600: #6e707e;
                --bs-gray-800: #5a5c69;

                /* Colores suavizados */
                --bs-primary-soft: #e8f0fe;
                --bs-success-soft: #e6f7f0;
                --bs-warning-soft: #fef6e6;
                --bs-secondary-soft: #f0f1f3;
                --bs-danger-soft: #fee2e2;
            }

            body {
                background-color: #f8fafc;
                color: #4a5568;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 0.9rem;
            }

            .card {
                border: 1px solid #e2e8f0;
                transition: all 0.3s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .card:hover {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                transition: background-color 0.3s ease;
                background-color: #f8fafc;
            }

            .table {
                --bs-table-striped-bg: #f9fafb;
                font-size: 0.85rem;
            }

            .table th {
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                color: #4a5568;
            }

            .table-hover tbody tr:hover {
                background-color: #f0f4f8;
                transform: translateY(-1px);
                transition: all 0.2s ease;
            }

            .text-muted {
                color: #718096 !important;
            }

            .text-gray-800 {
                color: #2d3748;
            }

            .bg-gray-50 {
                background-color: #f9fafb;
            }

            .bg-gray-100 {
                background-color: #f3f4f6;
            }

            .badge {
                font-weight: 500;
                font-size: 0.75rem;
            }

            .rounded-lg {
                border-radius: 0.5rem;
            }

            .custom-accordion-button {
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
                width: 100%;
                text-align: left;
                background-color: #f9fafb;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .custom-accordion-button:hover {
                background-color: #f0f4f8;
            }

            .custom-accordion-button.active {
                background-color: #f3f4f6;
            }

            .custom-accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .custom-accordion-content.active {
                max-height: 1000px;
                /* Ajusta según el contenido esperado */
            }

            .small {
                font-size: 0.85rem;
            }

            .h6 {
                font-size: 1rem;
            }

            /* Estilos mejorados para las pestañas */
            .nav-tabs {
                border-bottom: 2px solid #e2e8f0;
            }

            .nav-tabs .nav-link {
                color: #6b7280;
                border: none;
                border-bottom: 3px solid transparent;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                background-color: transparent;
                margin-bottom: -2px;
                display: flex;
                align-items: center;
            }

            .nav-tabs .nav-link:hover {
                color: #4e73df;
                background-color: rgba(78, 115, 223, 0.05);
                border-bottom-color: #e2e8f0;
            }

            .nav-tabs .nav-link.active {
                color: #4e73df;
                background-color: transparent;
                border-bottom: 3px solid #4e73df;
            }

            .nav-tabs .nav-link.active i {
                color: #4e73df;
            }

            .nav-tabs .nav-link .badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
                font-weight: 600;
                margin-left: 0.5rem;
            }

            .nav-tabs .nav-link:not(.active):hover {
                transform: translateY(-2px);
            }

            /* Colores para los badges en pestañas */
            .bg-primary-soft {
                background-color: var(--bs-primary-soft);
            }

            .bg-success-soft {
                background-color: var(--bs-success-soft);
            }

            .bg-secondary-soft {
                background-color: var(--bs-secondary-soft);
            }

            .bg-warning-soft {
                background-color: var(--bs-warning-soft);
            }

            .bg-danger-soft {
                background-color: var(--bs-danger-soft);
            }

            .nav-tabs .nav-link.active {
                color: #4e73df !important;
                border-bottom: 3px solid #4e73df !important;
                background-color: rgba(78, 115, 223, 0.1) !important;
            }
        </style>

        <!-- Include Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Include Bootstrap JS for tabs and alerts (not accordion) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Script personalizado para el acordeón -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const accordionButtons = document.querySelectorAll('.custom-accordion-button');

                accordionButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.getAttribute('data-target');
                        const content = document.getElementById(targetId);
                        const isActive = content.classList.contains('active');

                        // Cerrar todos los paneles
                        document.querySelectorAll('.custom-accordion-content').forEach(content => {
                            content.classList.remove('active');
                            content.style.maxHeight = '0';
                        });
                        document.querySelectorAll('.custom-accordion-button').forEach(btn => {
                            btn.classList.remove('active');
                        });

                        // Abrir el panel seleccionado si no estaba activo
                        if (!isActive) {
                            content.classList.add('active');
                            content.style.maxHeight = content.scrollHeight + 'px';
                            button.classList.add('active');
                            console.log('Accordion opened:', targetId);
                        } else {
                            console.log('Accordion closed:', targetId);
                        }
                    });
                });

                // Depuración adicional
                console.log('Accordion initialized with', accordionButtons.length, 'items');
            });
        </script>
    </div>
@endsection
