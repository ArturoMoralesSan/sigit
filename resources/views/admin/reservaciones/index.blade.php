@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'reservaciones de áreas')
@section('tab_title', 'reservaciones de áreas | ' . config('app.name'))
@section('description', 'Lista de reservaciones de áreas.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Reservaciones de áreas
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $bookings->count() }} reservaciones de áreas.
        </p>
    </div>

    <div class="fluid-container mb-16">
        <form-search 
                selected="{{ app('request')->input('search') }}"
            >
            <template slot="svg-search">
                <img class="search-form_icon" src="{{ url('img/svg/search.svg') }}" alt="">
            </template>
        </form-search>
        @include('components.alert')
        <section class="db-panel">
            <h3 class="db-panel__title">
                Lista de reservaciones de áreas
            </h3>

            @if (! $bookings->count())
                <p class="text-center py-1">
                    Por el momento no hay reservaciones de áreas.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $bookingsItems }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Nombre</th>
                                <th>Laboratorio</th>
                                <th>Objectivo</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="bookingItem in resourceList" class="table-resource__row" :key="bookingItem.id">
                                <td data-label="Nombre:">
                                    @{{ bookingItem.name }}
                                </td>
                                
                                <td data-label="Laboratorio:">
                                    @{{ bookingItem.area.name }}
                                </td>
                                <td data-label="Objetivo:">
                                    @{{ bookingItem.subject }}
                                </td>
                                <td data-label="Estado:">
                                    <span class="badge-status" :style="{backgroundColor: bookingItem.color}">
                                        @{{ bookingItem.status }}
                                    </span>
                                </td>
                                <td data-label="fecha:">
                                    @{{ bookingItem.day_format }}
                                </td>
                                <td data-label="Hora:">
                                    @{{ bookingItem.hour_range }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/reservaciones/' + bookingItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/reservaciones/eliminar/' + bookingItem.id"
                                        :resource-id="bookingItem.id"
                                        :options="{ onDelete: onResourceDelete }"
                                    >
                                        <img class="svg-icon" src="{{ url('img/svg/trash.svg')}}">
                                        Eliminar
                                    </delete-button>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </resource-table>

                {{ $bookings->links('layout.pagination') }}

            @endif

        </section>
    </div>
@endsection
