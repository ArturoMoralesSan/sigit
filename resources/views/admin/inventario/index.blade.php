@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Inventario')
@section('tab_title', 'Inventario | ' . config('app.name'))
@section('description', 'Lista de inventario.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Inventario
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $equipment->count() }} equipo / material registrados.
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
                Lista de inventario
            </h3>

            @if (! $equipment->count())
                <p class="text-center py-1">
                    Por el momento no hay equipo o material registrado.
                </p>
            @else
            
                <resource-table :breakpoint="800" :model="{{ $equipmentItems }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>N° de serie</th>
                                <th>Etiqueta de control</th>
                                <th>Equipo</th>
                                <th>Área</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>P.U</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="equipoItem in resourceList" class="table-resource__row" :key="equipoItem.id">
                                <td data-label="Num Serie:">
                                    @{{ equipoItem.num_serie }}
                                </td>
                                <td data-label="Etiqueta Control:">
                                    @{{ equipoItem.control_tag }}
                                </td>
                                <td data-label="Equipo:">
                                    @{{ equipoItem.product }}
                                </td>
                                <td data-label="Área:">
                                    @{{ equipoItem.area }}
                                </td>
                                <td data-label="Marca:">
                                    @{{ equipoItem.brand }}
                                </td>
                                <td data-label="Modelo:">
                                    @{{ equipoItem.model }}
                                </td>
                                <td data-label="P.U.:">
                                    @{{ equipoItem.pu }}
                                </td>
                                <td data-label="Cantidad:">
                                    @{{ equipoItem.quantity }}
                                </td>
                                <td data-label="Estado:">
                                    @{{ equipoItem.status }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/inventario/' + equipoItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/inventario/eliminar/' + equipoItem.id"
                                        :resource-id="equipoItem.id"
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
                
                {{ $equipment->links('layout.pagination') }}

            @endif

        </section>
    </div>
@endsection
