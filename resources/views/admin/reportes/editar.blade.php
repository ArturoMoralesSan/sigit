@extends('layout.dashboard-master')

@section('title', 'Actualizar estado del reporte')
@section('tab_title', 'Actualizar reporte | ' . config('app.name'))
@section('description', 'Actualizar estado del reporte')

@section('content')

<section class="mb-16">

    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Actualizar estado del reporte
        </h1>
    </div>

    <div class="fluid-container mb-16">

        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/reportes') }}">Ver todos los reportes</a>
        </p>

        <base-form action="{{ url('admin/reportes/' . $damagereport->id . '/actualizar') }}" method="put"
            inline-template v-cloak>

            <form>

                <section class="db-panel">

                    <h3 class="db-panel__title">
                        Información del reporte
                    </h3>

                    {{-- Info solo lectura --}}
                    <div class="md:row mb-4">

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Folio</label>
                                <input class="form-field" type="text" value="{{ $damagereport->folio }}" disabled>
                            </div>
                        </div>

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Nombre</label>
                                <input class="form-field" type="text" value="{{ $damagereport->name }}" disabled>
                            </div>
                        </div>

                    </div>

                    <div class="md:row mb-4">

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Área</label>
                                <input class="form-field" type="text" value="{{ $damagereport->area }}" disabled>
                            </div>
                        </div>

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Equipo / Material</label>
                                <input class="form-field" type="text" value="{{ $damagereport->item_name }}" disabled>
                            </div>
                        </div>

                    </div>

                    <div class="md:row mb-4">

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Problema</label>
                                <input class="form-field" type="text" value="{{ $damagereport->problem }}" disabled>
                            </div>
                        </div>

                        <div class="md:col-1/2">
                            <div class="form-control">
                                <label>Prioridad</label>
                                <input class="form-field" type="text" value="{{ $damagereport->priority }}" disabled>
                            </div>
                        </div>

                    </div>

                    {{-- SOLO EDITABLE --}}
                    <div class="md:row">

                        <div class="md:col-1/2">

                            <div class="form-control">
                                <label>Estado</label>

                                <select-field
                                    name="status"
                                    v-model="fields.status"
                                    :options="{
                                        'Pendiente': 'Pendiente',
                                        'En proceso': 'En proceso',
                                        'Resuelto': 'Resuelto',
                                        'Cancelado': 'Cancelado'
                                    }"
                                    initial="{{ $damagereport->status }}">
                                </select-field>

                                <field-errors name="status"></field-errors>

                            </div>

                        </div>

                    </div>

                </section>

                <div class="text-center">
                    <form-button class="btn--blue--dashboard btn--wide">
                        Actualizar
                    </form-button>
                </div>

            </form>

        </base-form>

    </div>

</section>

@endsection