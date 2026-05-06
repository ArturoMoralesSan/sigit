@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Asignaciones')
@section('tab_title', 'Asignaciones | ' . config('app.name'))
@section('description', 'Lista de Asignaciones.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Equipo asignado
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $assignments->count() }} usuarios con equipo asignado.
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
                Lista de usuarios con equipo asignado
            </h3>

            @if (! $assignments->count())
                <p class="text-center py-1">
                    Por el momento no hay usuarios con equipo asignado.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $assignmentItems }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Nombre</th>
                                <th>Equipo</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="userItem in resourceList" class="table-resource__row" :key="userItem.id">
                                <td data-label="Nombre:">
                                    @{{ userItem.name }} @{{ userItem.last_name }}
                                </td>
                                
                                <td data-label="Equipo:">
                                    @{{ userItem.list_product }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/asignar-equipo/' + userItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/asignar-equipo/eliminar/' + userItem.id"
                                        :resource-id="userItem.id"
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

                {{ $assignments->links('layout.pagination') }}

            @endif

        </section>
    </div>
@endsection
