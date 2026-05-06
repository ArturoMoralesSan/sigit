@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar reservación')
@section('tab_title', 'Editar reservación | ' . config('app.name'))
@section('description', 'Editar reservación.')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Editar reservación
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/reservaciones/') }}">Ver todas las reservaciones</a>
            </p>

            <base-form action="{{ url('admin/reservaciones/' . $booking->id . '/actualizar') }}" method="put"
                enctype="multipart/form-data" inline-template v-cloak>
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Datos de la reservación
                        </h3>

                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="title">Actividad / Práctica</label>
                                    <text-field name="title" v-model="fields.title"
                                        initial="{{ $booking->title }}"></text-field>
                                    <field-errors name="title"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="name">Nombre completo del solicitante</label>
                                    <text-field name="name" v-model="fields.name"
                                        initial="{{ $booking->name }}"></text-field>
                                    <field-errors name="name"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="subject">Objetivo</label>
                                    <text-field name="subject" v-model="fields.subject" initial="{{ $booking->subject }}">
                                    </text-field>
                                    <field-errors name="area"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                
                                <div class="form-control" style="width: 100%;">
                                    <label for="subject">Estado</label>
                                    <select-field class="form-select" name="status" v-model="fields.status"
                                        :options="{ 'Pendiente': 'Pendiente', 'Aprobado': 'Aprobado', 'Rechazado': 'Rechazado'}" initial="{{ $booking->status }}">
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
                </user-form>
        </div>
    </section>

@endsection
