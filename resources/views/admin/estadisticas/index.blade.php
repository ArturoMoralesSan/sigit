@extends('layout.dashboard-master')

@section('meta.title', 'Estadísticas')
@section('meta.tab_title', 'Estadísticas | ' . config('app.name'))

@section('content')

<div class="dashboard-heading">
    <div class="d-flex items-center justify-between">
        <h1 class="dashboard-heading__title">
            Panel de Estadísticas
        </h1>
        <form method="GET" class="filter-box">

            <div class="filter-row">

                <div class="filter-group">
                    <label>Fecha inicio</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ request('start_date') }}">
                </div>

                <div class="filter-group">
                    <label>Fecha fin</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ request('end_date') }}">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="fluid-container mb-16">
    <div class="row">
        <div class="col-1/4">
            <section class="db-panel">
                <h3 class="db-panel__title">Área crítica</h3>

                @if($criticalArea)
                    <div>
                        <p>
                            <strong style="color:#003865;">
                                {{ $criticalArea->build ?? '' }}
                                @if(isset($criticalArea->build)) - @endif
                                {{ $criticalArea->area ?? $criticalArea->area }}
                            </strong> Es el Aula / Área con <strong>{{ $criticalArea->total }} reportes</strong>
                        </p>
                        
                    </div>
                @else
                    <p>No hay datos suficientes</p>
                @endif

                <hr style="margin:15px 0;">

                <p style="margin:0;">
                    Reportes últimos 7 días:
                    <strong style="color:#003865;">
                        {{ $recentReports }}
                    </strong>
                </p>
            </section>
        </div>
        <div class="col-3/4">
            <section class="db-panel">
                <h3 class="db-panel__title">Reportes</h3>
                <div class="md:row text-center">

                    <div class="md:col-1/5">
                        <div class="kpi-box">
                            <h2>{{ $totalReports }}</h2>
                            <p>Total</p>
                        </div>
                    </div>

                    <div class="md:col-1/5">
                        <div class="kpi-box">
                            <h2>{{ $pending }}</h2>
                            <p>Pendientes</p>
                        </div>
                    </div>

                    <div class="md:col-1/5">
                        <div class="kpi-box">
                            <h2>{{ $inProgress }}</h2>
                            <p>En proceso</p>
                        </div>
                    </div>

                    <div class="md:col-1/5">
                        <div class="kpi-box">
                            <h2>{{ $resolved }}</h2>
                            <p>Resueltos</p>
                        </div>
                    </div>

                    <div class="md:col-1/5">
                        <div class="kpi-box">
                            <h2>{{ $cancelled }}</h2>
                            <p>Cancelados</p>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
    

    <div class="md:row">

        <div class="md:col-2/3">
            <section class="db-panel">
                <h3 class="db-panel__title">Reportes por día</h3>
                <canvas id="reportsChart"></canvas>
            </section>
        </div>

        <div class="md:col-1/3">
            <section class="db-panel">
                <h3 class="db-panel__title">Tipos de reporte</h3>
                <canvas id="typesChart"></canvas>
            </section>
        </div>

    </div>

    <div class="md:row">

        <div class="md:col-1/3">
            <section class="db-panel">
                <h3 class="db-panel__title">Equipos más dañados</h3>

                <table class="table size-caption">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDamaged as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>

        <div class="md:col-1/3">
            <section class="db-panel">
                <h3 class="db-panel__title">Laboratorios más usados</h3>

                <table class="table size-caption">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Laboratorio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topLabs as $i => $lab)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $lab->area->name ?? 'N/A' }}</td>
                            <td>{{ $lab->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>

        <div class="md:col-1/3">
            <section class="db-panel">
                <h3 class="db-panel__title">Materiales más solicitados</h3>

                @if($topMaterials->count())
                    <table class="table size-caption">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Material</th>
                                <th>Cantidad solicitada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topMaterials as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->product }}</td>
                                <td>
                                    <strong>{{ $item->total }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No hay datos en este rango de fechas</p>
                @endif
            </section>
        </div>

    </div>

</div>

@endsection
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    if (Chart.getChart("reportsChart")) {
        Chart.getChart("reportsChart").destroy();
    }

    if (Chart.getChart("typesChart")) {
        Chart.getChart("typesChart").destroy();
    }

    new Chart(document.getElementById('reportsChart'), {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: 'Reportes',
                data: @json($totals),
                borderColor: '#003865',
                backgroundColor: 'rgba(0,56,101,0.1)',
                tension: 0.3,
                fill: true
            }]
        }
    });

    new Chart(document.getElementById('typesChart'), {
        type: 'pie',
        data: {
            labels: @json($typesLabels),
            datasets: [{
                data: @json($typesData),
                backgroundColor: [
                    '#003865',
                    '#6C9A1F',
                    '#F59E0B',
                    '#EF4444'
                ]
            }]
        }
    });

});
</script>

@endsection