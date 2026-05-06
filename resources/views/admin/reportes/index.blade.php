@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Reportes')
@section('tab_title', 'Reportes | ' . config('app.name'))
@section('description', 'Lista de reportes.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Reportes
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $damagereports->count() }} reportes registrados.
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
                Lista de reportes
            </h3>

            @if (! $damagereports->count())
                <p class="text-center py-1">
                    Por el momento no hay equipo o material registrado.
                </p>
            @else
            
                <resource-table :breakpoint="800" :model="{{ $damagereportsItems }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Folio</th>
                                <th>Nombre</th>
                                <th>Área</th>
                                <th>Tipo</th>
                                <th>Nombre del producto</th>
                                <th>Prioridad</th>
                                <th>Problema</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="reportItem in resourceList" class="table-resource__row" :key="reportItem.id">
                                <td data-label="Folio:">
                                    @{{ reportItem.folio }}
                                </td>
                                <td data-label="Nombre:">
                                    @{{ reportItem.name }}
                                </td>
                                <td data-label="Área:">
                                    @{{ reportItem.area }}
                                </td>
                                <td data-label="Tipo:">
                                    @{{ reportItem.type }}
                                </td>
                                <td data-label="Nombre del producto:">
                                    @{{ reportItem.item_name }}
                                </td>
                                <td data-label="Prioridad:">
                                    @{{ reportItem.priority }}
                                </td>
                                <td data-label="Problema:">
                                    @{{ reportItem.problem }}
                                </td>
                                <td data-label="Descripción:">
                                    @{{ reportItem.description }}
                                </td>
                                <td data-label="Estado:">
                                    <span class="badge-status" :style="{backgroundColor: reportItem.color}">
                                        @{{ reportItem.status }}
                                    </span>
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/reportes/' + reportItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/reportes/eliminar/' + reportItem.id"
                                        :resource-id="reportItem.id"
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
                
                {{ $damagereports->links('layout.pagination') }}

            @endif

        </section>
    </div>
@endsection
