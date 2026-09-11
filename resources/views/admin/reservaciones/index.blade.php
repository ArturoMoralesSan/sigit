@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'reservaciones de áreas')
@section('tab_title', 'reservaciones de áreas | ' . config('app.name'))
@section('description', 'Lista de reservaciones de áreas.')
@section('class', 'dashboard')

@section('content')
<div class="dashboard-heading">

    <div class="md:row justify-between">

        <div class="md:col-1/2">

            <h1 class="dashboard-heading__title">
                reservaciones
            </h1>

            <p class="dashboard-heading__caption">
                Hay {{ $bookings->total() }} reservaciones registradas.
            </p>

        </div>


        <div class="md:col-1/2 d-flex items-center">

            <div class="row">

                {{-- ÁREA --}}
                <div class="md:col-1/4">

                    <label for="area_id">
                        Área
                    </label>

                    <select-filter
                        name="area_id"
                        selected="{{ request('area_id') ?: '1' }}"
                        :options="{{ $areas }}"
                    >
                    </select-filter>

                </div>


                {{-- MES --}}
                <div class="md:col-1/4">

                    <label for="month">
                        Mes
                    </label>

                    <select-filter
                        name="month"
                        selected="{{ request('month') ?: $actual_month }}"
                        :options="{{ $months }}"
                    >
                    </select-filter>

                </div>


                {{-- AÑO --}}
                <div class="md:col-1/4">

                    <label for="year">
                        Año
                    </label>

                    <select-filter
                        name="year"
                        selected="{{ request('year') ?: $actual_year }}"
                        :options="{{ $years }}"
                    >
                    </select-filter>

                </div>


                {{-- ESTADO --}}
                <div class="md:col-1/4">

                    <label for="status">
                        Estado
                    </label>

                    <select-filter
                        name="status"
                        selected="{{ request('status') ?: '' }}"
                        :options="{
                            '': 'Todos',
                            'Pendiente': 'Pendientes',
                            'Aprobado': 'Aprobados',
                            'Rechazado': 'Rechazados'
                        }"
                    >
                    </select-filter>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="fluid-container mb-16">

    {{-- ==========================================================
         BUSCADOR
         ========================================================== --}}

    <form-search
        selected="{{ request('search') }}"
    >

        <template slot="svg-search">

            <img
                class="search-form_icon"
                src="{{ url('img/svg/search.svg') }}"
                alt=""
            >

        </template>

    </form-search>


    {{-- ==========================================================
         ACCIONES
         ========================================================== --}}

    <div
        class="d-flex justify-between items-center mb-8 flex-wrap"
    >

        <div class="d-flex items-center flex-wrap">

            {{-- CONTADOR --}}
            <span
                id="selectedBookingsCounter"
                style="
                    display: none;
                    font-size: 14px;
                    font-weight: 700;
                    color: #003865;
                    padding: 8px 12px;
                    background: #f1f5f9;
                    border-radius: 8px;
                    margin-right: 10px;
                    margin-bottom: 5px;
                "
            >
                0 seleccionadas
            </span>


            {{-- VALIDAR SELECCIONADAS --}}
            <button
                type="button"
                id="btnValidarSeleccionadas"
                class="btn btn--sm"
                style="
                    display: none;
                    background: #6C9A1F;
                    color: #fff;
                    margin-right: 10px;
                    margin-bottom: 5px;
                "
            >
                ✓ Validar seleccionadas
            </button>


            {{-- VALIDAR TODOS --}}
            <button
                type="button"
                id="btnValidarTodosPendientes"
                class="btn btn--sm"
                style="
                    background: #003865;
                    color: #fff;
                    margin-bottom: 5px;
                "
            >
                ✓ Validar todos los pendientes
            </button>

        </div>


        {{-- ======================================================
             EXPORTACIONES
             ====================================================== --}}

        <div class="d-flex justify-end flex-wrap">

            <a
                href="{{ url(
                    'export/excel/' .
                    (request('month') ?: $actual_month) .
                    '/' .
                    (request('year') ?: $actual_year) .
                    '/' .
                    (request('area_id') ?: '1')
                ) }}"
                class="btn btn--sm mr-2 mb-2"
            >
                LAB-RG-01
            </a>


            <a
                href="{{ url(
                    'export/bitacora/' .
                    (request('month') ?: $actual_month) .
                    '/' .
                    (request('year') ?: $actual_year) .
                    '/' .
                    (request('area_id') ?: '1')
                ) }}"
                class="btn btn--sm mr-2 mb-2"
            >
                LAB-RG-03
            </a>


            <a
                href="{{ url(
                    'export/word/' .
                    (request('month') ?: $actual_month) .
                    '/' .
                    (request('year') ?: $actual_year) .
                    '/' .
                    (request('area_id') ?: '1')
                ) }}"
                class="btn btn--sm mb-2"
            >
                LAB-RG-06
            </a>

        </div>

    </div>


    @include('components.alert')


    {{-- ==========================================================
         TABLA
         ========================================================== --}}

    <section class="db-panel">

        <h3 class="db-panel__title">
            Lista de reservaciones de áreas
        </h3>


        @if (! $bookings->count())

            <p class="text-center py-1">
                Por el momento no hay reservaciones de áreas.
            </p>

        @else

            <resource-table
                :breakpoint="800"
                :model="{{ $bookingsItems }}"
                inline-template
            >

                <table
                    class="table size-caption mx-auto mb-16 md:table--responsive"
                >

                    <thead>

                        <tr class="table-resource__headings">

                            {{-- SELECCIONAR TODAS --}}
                            <th
                                style="
                                    width: 50px;
                                    text-align: center;
                                "
                            >

                                <input
                                    type="checkbox"
                                    id="selectAllBookings"
                                    title="Seleccionar pendientes"
                                    style="
                                        width: 18px;
                                        height: 18px;
                                        cursor: pointer;
                                        accent-color: #6C9A1F;
                                    "
                                >

                            </th>


                            <th>
                                Nombre
                            </th>


                            <th>
                                Laboratorio
                            </th>


                            <th>
                                Objetivo
                            </th>


                            <th>
                                Estado
                            </th>


                            <th>
                                Fecha
                            </th>


                            <th>
                                Hora
                            </th>


                            <th class="pr-4">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="bookingItem in resourceList"
                            class="table-resource__row"
                            :key="bookingItem.id"
                        >

                            {{-- ==================================================
                                 CHECKBOX
                                 ================================================== --}}

                            <td
                                data-label="Seleccionar:"
                                style="
                                    text-align: center;
                                "
                            >

                                <input
                                    type="checkbox"
                                    class="booking-checkbox"
                                    :data-id="bookingItem.id"
                                    :data-status="bookingItem.status"
                                    :disabled="bookingItem.status !== 'Pendiente'"
                                    style="
                                        width: 18px;
                                        height: 18px;
                                        cursor: pointer;
                                        accent-color: #6C9A1F;
                                    "
                                >

                            </td>


                            {{-- NOMBRE --}}
                            <td data-label="Nombre:">

                                @{{ bookingItem.name }}

                            </td>


                            {{-- LABORATORIO --}}
                            <td data-label="Laboratorio:">

                                @{{ bookingItem.area.name }}

                            </td>


                            {{-- OBJETIVO --}}
                            <td data-label="Objetivo:">

                                @{{ bookingItem.subject }}

                            </td>


                            {{-- ESTADO --}}
                            <td data-label="Estado:">

                                <span
                                    class="badge-status"
                                    :style="{
                                        backgroundColor: bookingItem.color
                                    }"
                                >
                                    @{{ bookingItem.status }}
                                </span>

                            </td>


                            {{-- FECHA --}}
                            <td data-label="Fecha:">

                                @{{ bookingItem.day_format }}

                            </td>


                            {{-- HORA --}}
                            <td data-label="Hora:">

                                @{{ bookingItem.hour_range }}

                            </td>


                            {{-- ACCIONES --}}
                            <td
                                class="table-resource__actions"
                                data-label="Acciones:"
                            >

                                <a
                                    class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2"
                                    :href="$root.path + '/admin/reservaciones/' + bookingItem.id + '/editar'"
                                >

                                    <img
                                        class="svg-icon"
                                        src="{{ url('img/svg/edit.svg') }}"
                                        alt=""
                                    >

                                    Editar

                                </a>


                                <delete-button
                                    class="btn--danger table-resource__button"
                                    :url="$root.path + '/admin/reservaciones/eliminar/' + bookingItem.id"
                                    :resource-id="bookingItem.id"
                                    :options="{ onDelete: onResourceDelete }"
                                >

                                    <img
                                        class="svg-icon"
                                        src="{{ url('img/svg/trash.svg') }}"
                                        alt=""
                                    >

                                    Eliminar

                                </delete-button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </resource-table>


            {{-- PAGINACIÓN --}}
            {{ $bookings->links('layout.pagination') }}

        @endif

    </section>

</div>

@endsection

{{-- ================================================================
SCRIPTS
IMPORTANTE:
Este section está FUERA de @section('content')
================================================================ --}}

@section('scripts')

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    /*
    |--------------------------------------------------------------------------
    | RESERVACIONES
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            /*
            |--------------------------------------------------------------------------
            | VARIABLES
            |--------------------------------------------------------------------------
            */

            let selectedBookingIds = new Set();


            const selectAll =
                document.getElementById(
                    'selectAllBookings'
                );


            const counter =
                document.getElementById(
                    'selectedBookingsCounter'
                );


            const btnSeleccionadas =
                document.getElementById(
                    'btnValidarSeleccionadas'
                );


            const btnTodos =
                document.getElementById(
                    'btnValidarTodosPendientes'
                );


            /*
            |--------------------------------------------------------------------------
            | SI NO ESTAMOS EN LA PÁGINA DE RESERVACIONES
            |--------------------------------------------------------------------------
            */

            if (
                !selectAll &&
                !btnSeleccionadas &&
                !btnTodos
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR INTERFAZ
            |--------------------------------------------------------------------------
            */

            function updateSelectionUI()
            {

                const cantidad =
                    selectedBookingIds.size;


                /*
                |--------------------------------------------------------------------------
                | CONTADOR
                |--------------------------------------------------------------------------
                */

                if (counter) {

                    if (cantidad === 0) {

                        counter.style.display =
                            'none';

                    } else {

                        counter.style.display =
                            'inline-block';


                        counter.textContent =
                            cantidad === 1
                                ? '1 seleccionada'
                                : cantidad +
                                  ' seleccionadas';

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | BOTÓN VALIDAR SELECCIONADAS
                |--------------------------------------------------------------------------
                */

                if (btnSeleccionadas) {

                    btnSeleccionadas.style.display =
                        cantidad > 0
                            ? 'inline-block'
                            : 'none';

                }


                /*
                |--------------------------------------------------------------------------
                | CHECKBOX GENERAL
                |--------------------------------------------------------------------------
                */

                if (selectAll) {

                    const checkboxes =
                        Array.from(
                            document.querySelectorAll(
                                '.booking-checkbox'
                            )
                        )
                        .filter(
                            checkbox =>
                                !checkbox.disabled
                        );


                    const checked =
                        checkboxes.filter(
                            checkbox =>
                                checkbox.checked
                        );


                    if (
                        checkboxes.length === 0
                    ) {

                        selectAll.checked =
                            false;

                        selectAll.indeterminate =
                            false;

                    } else {

                        selectAll.checked =
                            checked.length ===
                            checkboxes.length;


                        selectAll.indeterminate =
                            checked.length > 0 &&
                            checked.length <
                            checkboxes.length;

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | SELECCIONAR TODAS
            |--------------------------------------------------------------------------
            */

            if (selectAll) {

                selectAll.addEventListener(
                    'change',
                    function () {


                        const checkboxes =
                            document.querySelectorAll(
                                '.booking-checkbox'
                            );


                        checkboxes.forEach(
                            function (checkbox) {


                                /*
                                |--------------------------------------------------------------------------
                                | SOLO PENDIENTES
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    checkbox.disabled
                                ) {
                                    return;
                                }


                                checkbox.checked =
                                    selectAll.checked;


                                const id =
                                    String(
                                        checkbox.dataset.id
                                    );


                                if (
                                    selectAll.checked
                                ) {

                                    selectedBookingIds.add(
                                        id
                                    );

                                } else {

                                    selectedBookingIds.delete(
                                        id
                                    );

                                }

                            }
                        );


                        updateSelectionUI();

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX INDIVIDUAL
            |--------------------------------------------------------------------------
            |
            | Se usa delegación de eventos porque resource-table
            | puede volver a renderizar sus filas.
            |
            */

            document.addEventListener(
                'change',
                function (event) {


                    const checkbox =
                        event.target.closest(
                            '.booking-checkbox'
                        );


                    if (!checkbox) {
                        return;
                    }


                    const id =
                        String(
                            checkbox.dataset.id
                        );


                    if (
                        checkbox.checked
                    ) {

                        selectedBookingIds.add(
                            id
                        );

                    } else {

                        selectedBookingIds.delete(
                            id
                        );

                    }


                    updateSelectionUI();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | VALIDAR SELECCIONADAS
            |--------------------------------------------------------------------------
            */

            if (btnSeleccionadas) {

                btnSeleccionadas.addEventListener(
                    'click',
                    function () {


                        const ids =
                            Array.from(
                                selectedBookingIds
                            );


                        if (
                            ids.length === 0
                        ) {

                            Swal.fire({

                                icon:
                                    'info',

                                title:
                                    'No hay reservaciones seleccionadas',

                                text:
                                    'Selecciona al menos una reservación pendiente.',

                                confirmButtonText:
                                    'Entendido'

                            });

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CONFIRMACIÓN
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon:
                                'question',

                            title:
                                '¿Validar reservaciones?',

                            html:
                                'Se aprobarán <strong>' +
                                ids.length +
                                '</strong> reservaciones seleccionadas.',

                            showCancelButton:
                                true,

                            confirmButtonText:
                                'Sí, validar',

                            cancelButtonText:
                                'Cancelar',

                            reverseButtons:
                                true

                        }).then(
                            function (result) {


                                if (
                                    result.isConfirmed
                                ) {

                                    validarReservaciones(
                                        ids,
                                        false
                                    );

                                }

                            }
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDAR TODOS LOS PENDIENTES
            |--------------------------------------------------------------------------
            */

            if (btnTodos) {

                btnTodos.addEventListener(
                    'click',
                    function () {


                        Swal.fire({

                            icon:
                                'warning',

                            title:
                                '¿Validar todos los pendientes?',

                            html:
                                '<p>' +
                                'Se aprobarán todas las reservaciones ' +
                                'pendientes que coincidan con los ' +
                                'filtros actuales.' +
                                '</p>' +

                                '<p class="mb-0">' +
                                '<strong>La acción incluye otras páginas.</strong>' +
                                '</p>',

                            showCancelButton:
                                true,

                            confirmButtonText:
                                'Sí, validar todos',

                            cancelButtonText:
                                'Cancelar',

                            reverseButtons:
                                true

                        }).then(
                            function (result) {


                                if (
                                    result.isConfirmed
                                ) {

                                    validarReservaciones(
                                        [],
                                        true
                                    );

                                }

                            }
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FUNCIÓN PRINCIPAL
            |--------------------------------------------------------------------------
            */

            function validarReservaciones(
                ids,
                allPending
            )
            {


                /*
                |--------------------------------------------------------------------------
                | OBTENER FILTROS ACTUALES
                |--------------------------------------------------------------------------
                */

                const urlParams =
                    new URLSearchParams(
                        window.location.search
                    );


                const data = {

                    ids:
                        ids,

                    all_pending:
                        allPending,

                    month:
                        urlParams.get(
                            'month'
                        ),

                    year:
                        urlParams.get(
                            'year'
                        ),

                    area_id:
                        urlParams.get(
                            'area_id'
                        ),

                    search:
                        urlParams.get(
                            'search'
                        ),

                    status:
                        urlParams.get(
                            'status'
                        )

                };


                /*
                |--------------------------------------------------------------------------
                | LOADING
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    title:
                        'Validando reservaciones',

                    text:
                        'Por favor espera...',

                    allowOutsideClick:
                        false,

                    allowEscapeKey:
                        false,

                    showConfirmButton:
                        false,

                    didOpen:
                        function () {

                            Swal.showLoading();

                        }

                });


                /*
                |--------------------------------------------------------------------------
                | PETICIÓN
                |--------------------------------------------------------------------------
                */

                fetch(
                    '{{ url('admin/reservaciones/validar') }}',
                    {

                        method:
                            'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    ?.getAttribute(
                                        'content'
                                    ),

                            'X-Requested-With':
                                'XMLHttpRequest'

                        },

                        body:
                            JSON.stringify(
                                data
                            )

                    }
                )


                /*
                |--------------------------------------------------------------------------
                | RESPUESTA
                |--------------------------------------------------------------------------
                */

                .then(
                    async function (response) {


                        let result;


                        try {

                            result =
                                await response.json();

                        } catch (error) {

                            throw new Error(
                                'El servidor devolvió una respuesta no válida.'
                            );

                        }


                        if (
                            !response.ok
                        ) {

                            throw new Error(
                                result.message ||
                                'No fue posible validar las reservaciones.'
                            );

                        }


                        return result;

                    }
                )


                /*
                |--------------------------------------------------------------------------
                | ÉXITO
                |--------------------------------------------------------------------------
                */

                .then(
                    function (result) {


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Reservaciones validadas',

                            html:
                                result.message,

                            confirmButtonText:
                                'Aceptar'

                        }).then(
                            function () {


                                selectedBookingIds.clear();


                                window.location.reload();

                            }
                        );

                    }
                )


                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                .catch(
                    function (error) {


                        console.error(
                            error
                        );


                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Error',

                            text:
                                error.message ||
                                'Ocurrió un error al validar las reservaciones.',

                            confirmButtonText:
                                'Aceptar'

                        });

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR
            |--------------------------------------------------------------------------
            */

            updateSelectionUI();

        }
    );

</script>

@endsection
