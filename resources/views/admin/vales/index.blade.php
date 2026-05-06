@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Vales de Material')
@section('tab_title', 'Vales de Material | ' . config('app.name'))
@section('description', 'Vales de Material.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Vales de Material
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $vouchers->count() }} vales de material registrados.
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
                Vales de Material
            </h3>

            @if (! $vouchers->count())
                <p class="text-center py-1">
                    Por el momento no hay vales de material registrado.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $vouchersItems }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>ID</th>
                                <th>Solicita</th>
                                <th>Profesor</th>
                                <th>Grupo</th>
                                <th>Asignatura</th>
                                <th>Laboratorio</th>
                                <th>Fecha de Devolución</th>
                                <th>Estado</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="voucherItem in resourceList" class="table-resource__row" :key="voucherItem.id">
                                <td data-label="id:">
                                    @{{ voucherItem.id }}
                                </td>
                                <td data-label="Soliita:">
                                    @{{ voucherItem.req }}
                                </td>
                                <td data-label="Profesor:">
                                    @{{ voucherItem.teacher }}
                                </td>    
                                <td data-label="Grupo:">
                                    @{{ voucherItem.group }}
                                </td>
                                <td data-label="Etiqueta Control:">
                                    @{{ voucherItem.subject }}
                                </td>
                                <td data-label="Laboratorio:">
                                    @{{ voucherItem.laboratory }}
                                </td>
                                <td data-label="Fecha de devolución:">
                                    @{{ voucherItem.return_date }}
                                </td>
                                <td data-label="Estado:">
                                    @{{ voucherItem.status }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--success table-resource__button mr-2" :href="$root.path + '/admin/pdf-vale-equipo/' + voucherItem.id">
                                        PDF
                                    </a>
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/vales-equipo/' + voucherItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/vales-equipo/eliminar/' + voucherItem.id"
                                        :resource-id="voucherItem.id"
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
                {{ $vouchers->links('layout.pagination') }}
            @endif

        </section>
    </div>
@endsection
