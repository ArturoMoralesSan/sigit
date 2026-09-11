<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIGIT - Calendario de reservaciones</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- FullCalendar --}}
    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css"
        rel="stylesheet"
    >

    {{-- Nunito --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- SweetAlert2 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
        rel="stylesheet"
    >

    <style>

        :root {
            --azul: #003865;
            --verde: #6C9A1F;
        }

        body {
            background: #f2f2f2;
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
        }

        .page-container {
            width: 100%;
            padding: 35px 20px;
        }

        .logo-sigit {
            width: 300px;
            max-width: 80%;
            height: auto;
            display: block;
            margin: 0 auto 5px;
        }

        .wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 0 40px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .card-custom {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            padding: 30px;
            width: 100%;
        }

        h1 {
            margin: 0 0 10px;
            color: var(--azul);
            font-size: 26px;
            font-weight: 800;
        }

        .sub {
            color: #6b7280;
            margin-bottom: 20px;
        }

        #calendar {
            margin-top: 10px;
        }

        .back {
            display: inline-block;
            margin-bottom: 15px;
            color: var(--azul);
            text-decoration: none;
            font-weight: 700;
        }

        .back:hover {
            color: var(--verde);
        }

        .modal-custom {
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            padding: 10px;
        }

        .form-label-custom {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--azul);
            margin-bottom: 6px;
        }

        .input-custom {
            width: 100%;
            border: 1px solid #d8dee6;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: .2s;
        }

        .input-custom:focus {
            border-color: var(--verde);
            box-shadow: 0 0 0 2px rgba(108, 154, 31, .08);
        }

        .textarea-custom {
            min-height: 100px;
            resize: vertical;
        }

        .btn-save {
            background: var(--verde);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-save:hover {
            background: var(--azul);
            color: white;
        }

        .btn-save:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-cancel:hover {
            background: #4b5563;
            color: white;
        }

        .error-text {
            color: #EF4444;
            font-size: 13px;
            margin-top: 4px;
        }

        .input-error {
            border-color: #EF4444 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | HORARIO SELECCIONADO
        |--------------------------------------------------------------------------
        */

        .selected-schedule {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-left: 4px solid var(--verde);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .selected-schedule-title {
            color: var(--azul);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .selected-schedule-date {
            color: #374151;
            font-size: 14px;
            font-weight: 700;
            text-transform: capitalize;
            margin-bottom: 12px;
        }

        .selected-schedule-time {
            color: var(--verde);
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 8px;
        }

        .selected-schedule-recurrence {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        /*
        |--------------------------------------------------------------------------
        | HORAS DESDE / HASTA
        |--------------------------------------------------------------------------
        */

        .schedule-times {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 10px;
        }

        .schedule-time-box {
            background: #fff;
            border: 1px solid #d8dee6;
            border-radius: 10px;
            padding: 10px;
        }

        .schedule-time-label {
            display: block;
            color: var(--azul);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .schedule-time-select {
            width: 100%;
            border: 0;
            outline: none;
            background: transparent;
            color: #374151;
            font-size: 15px;
            font-weight: 700;
        }

        .schedule-duration {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | RECURRENCIA
        |--------------------------------------------------------------------------
        */

        .recurrence-container {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px;
        }

        .recurrence-title {
            color: var(--azul);
            font-weight: 800;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .recurrence-option {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        .recurrence-option input {
            accent-color: var(--verde);
            width: 17px;
            height: 17px;
        }

        .recurrence-option label {
            cursor: pointer;
            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | DIAS
        |--------------------------------------------------------------------------
        */

        .days-container {
            margin-top: 15px;
            display: none;
        }

        .days-container.show {
            display: block;
        }

        .days-title {
            font-size: 14px;
            color: var(--azul);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .day-option {
            position: relative;
        }

        .day-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .day-option label {
            display: block;
            text-align: center;
            padding: 9px 5px;
            background: #fff;
            border: 1px solid #d8dee6;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            cursor: pointer;
            transition: .2s;
        }

        .day-option input:checked + label {
            background: var(--verde);
            border-color: var(--verde);
            color: #fff;
        }

        .day-option label:hover {
            border-color: var(--verde);
        }

        .recurrence-info {
            margin-top: 12px;
            font-size: 12px;
            color: #6b7280;
        }

        /*
        |--------------------------------------------------------------------------
        | CONFLICTOS
        |--------------------------------------------------------------------------
        */

        .conflict-list {
            margin-top: 10px;
            padding-left: 20px;
            margin-bottom: 0;
        }

        .conflict-list li {
            margin-bottom: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | SWEETALERT
        |--------------------------------------------------------------------------
        */

        .swal2-popup {
            font-family: 'Nunito', sans-serif;
            border-radius: 14px;
        }

        .swal2-title {
            color: var(--azul);
        }

        .swal2-confirm {
            background-color: var(--verde) !important;
        }

        .swal2-cancel {
            background-color: #6b7280 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | FULLCALENDAR
        |--------------------------------------------------------------------------
        */

        .fc {
            font-family: 'Nunito', sans-serif;
        }

        .fc .fc-toolbar-title {
            color: var(--azul);
            font-weight: 800;
            font-size: 20px;
        }

        .fc .fc-button {
            background: var(--azul);
            border-color: var(--azul);
            font-weight: 700;
        }

        .fc .fc-button:hover {
            background: var(--verde);
            border-color: var(--verde);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--verde);
            border-color: var(--verde);
        }

        .fc .fc-col-header-cell-cushion {
            color: var(--azul);
            font-weight: 800;
            text-decoration: none;
        }

        .fc .fc-timegrid-slot-label-cushion {
            color: #6b7280;
            font-weight: 600;
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-timegrid-axis-cushion {
            color: #6b7280;
        }

        .fc .fc-highlight {
            background: rgba(108, 154, 31, .18);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 576px) {

            .page-container {
                padding: 20px 10px;
            }

            .logo-sigit {
                width: 240px;
            }

            .wrapper {
                padding: 15px 0 25px;
            }

            .card-custom {
                padding: 20px;
            }

            .days-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            h1 {
                font-size: 22px;
            }

            .selected-schedule-time {
                font-size: 21px;
            }

            .schedule-times {
                grid-template-columns: 1fr 1fr;
            }

            .fc .fc-toolbar {
                flex-direction: column;
                gap: 10px;
            }

            .fc .fc-toolbar-title {
                font-size: 18px;
            }
        }

    </style>

</head>

<body>

<div class="page-container">

    <img
        src="{{ asset('img/LOGO_BIS_UNIPOLI.png') }}"
        alt="Universidad Politécnica de Durango"
        class="logo-sigit"
    >

    <div class="wrapper">

        <div class="card-custom">

            <a
                href="{{ url('agenda-espacios') }}"
                class="back"
            >
                ← Regresar
            </a>

            <h1>
                SIGIT – Reserva de {{ $area->name }}
            </h1>

            <p class="sub">
                Consulta disponibilidad y agenda espacios.
            </p>

            <div id="calendar"></div>

        </div>

    </div>

</div>


{{-- ==============================================================
     MODAL
     ============================================================== --}}

<div
    class="modal fade"
    id="modalReserva"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modal-custom">

            <div class="modal-header border-0">

                <h5
                    class="modal-title fw-bold"
                    style="color:var(--azul);"
                >
                    Nueva Reserva
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                {{-- HORARIO SELECCIONADO --}}

                <div
                    class="selected-schedule"
                    id="selectedSchedule"
                >

                    <div class="selected-schedule-title">
                        Horario seleccionado
                    </div>

                    <div
                        class="selected-schedule-date"
                        id="selectedDate"
                    >
                        —
                    </div>


                    {{-- ==================================================
                         HORAS DESDE / HASTA
                         ================================================== --}}

                    <div class="schedule-times">

                        <div class="schedule-time-box">

                            <label
                                for="horaInicio"
                                class="schedule-time-label"
                            >
                                Hora desde
                            </label>

                            <select
                                id="horaInicio"
                                class="schedule-time-select"
                            ></select>

                        </div>


                        <div class="schedule-time-box">

                            <label
                                for="horaFin"
                                class="schedule-time-label"
                            >
                                Hora hasta
                            </label>

                            <select
                                id="horaFin"
                                class="schedule-time-select"
                            ></select>

                        </div>

                    </div>


                    <div
                        class="selected-schedule-time"
                        id="selectedTime"
                    >
                        —
                    </div>

                    <div
                        class="schedule-duration"
                        id="scheduleDuration"
                    >
                        Duración: —
                    </div>

                    <div
                        class="selected-schedule-recurrence"
                        id="selectedRecurrence"
                    >
                        Reservación única
                    </div>

                </div>


                {{-- NOMBRE --}}

                <div class="mb-3">

                    <label class="form-label-custom">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="name"
                        class="input-custom"
                    >

                    <div
                        class="error-text"
                        id="error-name"
                    ></div>

                </div>


                {{-- ASIGNATURA --}}

                <div class="mb-3">

                    <label class="form-label-custom">
                        Asignatura
                    </label>

                    <input
                        type="text"
                        id="asignatura"
                        class="input-custom"
                    >

                    <div
                        class="error-text"
                        id="error-asignatura"
                    ></div>

                </div>


                {{-- PRACTICA --}}

                <div class="mb-3">

                    <label class="form-label-custom">
                        Práctica
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        class="input-custom"
                    >

                    <div
                        class="error-text"
                        id="error-titulo"
                    ></div>

                </div>


                {{-- OBJETIVO --}}

                <div class="mb-3">

                    <label class="form-label-custom">
                        Objetivo de la práctica
                    </label>

                    <textarea
                        id="obs"
                        class="input-custom textarea-custom"
                    ></textarea>

                    <div
                        class="error-text"
                        id="error-obs"
                    ></div>

                </div>


                {{-- RECURRENCIA --}}

                <div class="mb-3">

                    <div class="recurrence-container">

                        <div class="recurrence-title">
                            Repetir reserva
                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-once"
                                value="once"
                                checked
                            >

                            <label for="recurrence-once">
                                Una sola vez
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-1week"
                                value="1week"
                            >

                            <label for="recurrence-1week">
                                Durante 1 semana
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-2weeks"
                                value="2weeks"
                            >

                            <label for="recurrence-2weeks">
                                Durante 2 semanas
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-1month"
                                value="1month"
                            >

                            <label for="recurrence-1month">
                                Durante 1 mes
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-2months"
                                value="2months"
                            >

                            <label for="recurrence-2months">
                                Durante 2 meses
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-3months"
                                value="3months"
                            >

                            <label for="recurrence-3months">
                                Durante 3 meses
                            </label>

                        </div>


                        <div class="recurrence-option">

                            <input
                                type="radio"
                                name="recurrencia"
                                id="recurrence-4months"
                                value="4months"
                            >

                            <label for="recurrence-4months">
                                Durante 4 meses
                            </label>

                        </div>


                        {{-- DIAS --}}

                        <div
                            id="daysContainer"
                            class="days-container"
                        >

                            <div class="days-title">
                                Selecciona los días
                            </div>


                            <div class="days-grid">

                                <div class="day-option">

                                    <input
                                        type="checkbox"
                                        id="day-1"
                                        name="dias[]"
                                        value="1"
                                    >

                                    <label for="day-1">
                                        Lunes
                                    </label>

                                </div>


                                <div class="day-option">

                                    <input
                                        type="checkbox"
                                        id="day-2"
                                        name="dias[]"
                                        value="2"
                                    >

                                    <label for="day-2">
                                        Martes
                                    </label>

                                </div>


                                <div class="day-option">

                                    <input
                                        type="checkbox"
                                        id="day-3"
                                        name="dias[]"
                                        value="3"
                                    >

                                    <label for="day-3">
                                        Miércoles
                                    </label>

                                </div>


                                <div class="day-option">

                                    <input
                                        type="checkbox"
                                        id="day-4"
                                        name="dias[]"
                                        value="4"
                                    >

                                    <label for="day-4">
                                        Jueves
                                    </label>

                                </div>


                                <div class="day-option">

                                    <input
                                        type="checkbox"
                                        id="day-5"
                                        name="dias[]"
                                        value="5"
                                    >

                                    <label for="day-5">
                                        Viernes
                                    </label>

                                </div>

                            </div>


                            <div class="recurrence-info">
                                Selecciona los días de la semana en los que deseas repetir la reserva.
                            </div>


                            <div
                                class="error-text"
                                id="error-dias"
                            ></div>

                        </div>

                    </div>

                </div>


                {{-- DATOS OCULTOS --}}

                <input
                    type="hidden"
                    id="inicio"
                >

                <input
                    type="hidden"
                    id="fin"
                >

            </div>


            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="btn-cancel"
                    data-bs-dismiss="modal"
                >
                    Cerrar
                </button>

                <button
                    type="button"
                    class="btn-save"
                    id="btnGuardar"
                    onclick="guardar()"
                >
                    Guardar
                </button>

            </div>

        </div>

    </div>

</div>


{{-- ==============================================================
     SCRIPTS
     ============================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

/*
|--------------------------------------------------------------------------
| FECHA DE HOY
|--------------------------------------------------------------------------
*/

function obtenerFechaLocal()
{
    const hoy = new Date();

    const year = hoy.getFullYear();

    const month = String(
        hoy.getMonth() + 1
    ).padStart(2, '0');

    const day = String(
        hoy.getDate()
    ).padStart(2, '0');

    return `${year}-${month}-${day}`;
}


/*
|--------------------------------------------------------------------------
| CAPITALIZAR
|--------------------------------------------------------------------------
*/

function capitalizar(texto)
{
    if (!texto) {
        return '';
    }

    return texto.charAt(0).toUpperCase()
        + texto.slice(1);
}


/*
|--------------------------------------------------------------------------
| FORMATEAR HORA
|--------------------------------------------------------------------------
*/

function formatearHora(fecha)
{
    return fecha.toLocaleTimeString(
        'es-MX',
        {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    );
}


/*
|--------------------------------------------------------------------------
| OBTENER DIAS SELECCIONADOS
|--------------------------------------------------------------------------
*/

function obtenerDiasSeleccionados()
{
    const dias = [];

    document
        .querySelectorAll(
            'input[name="dias[]"]:checked'
        )
        .forEach(input => {

            const label =
                document.querySelector(
                    `label[for="${input.id}"]`
                );

            if (label) {

                dias.push(
                    label.textContent.trim()
                );
            }
        });

    return dias;
}


/*
|--------------------------------------------------------------------------
| GENERAR OPCIONES DE HORAS
|--------------------------------------------------------------------------
*/

function generarHoras()
{
    const horaInicio =
        document.getElementById(
            'horaInicio'
        );

    const horaFin =
        document.getElementById(
            'horaFin'
        );

    horaInicio.innerHTML = '';
    horaFin.innerHTML = '';

    /*
    |--------------------------------------------------------------------------
    | HORARIO DEL CALENDARIO
    |--------------------------------------------------------------------------
    */

    for (
        let hora = 7;
        hora <= 21;
        hora++
    ) {

        const valor =
            String(hora).padStart(2, '0') + ':00';

        const optionInicio =
            document.createElement('option');

        optionInicio.value = valor;
        optionInicio.textContent = valor;

        horaInicio.appendChild(
            optionInicio
        );


        const optionFin =
            document.createElement('option');

        optionFin.value = valor;
        optionFin.textContent = valor;

        horaFin.appendChild(
            optionFin
        );
    }
}


/*
|--------------------------------------------------------------------------
| ACTUALIZAR HORAS DESDE LOS SELECT
|--------------------------------------------------------------------------
*/

function actualizarHorario()
{
    const inicio =
        document.getElementById(
            'horaInicio'
        ).value;

    const fin =
        document.getElementById(
            'horaFin'
        ).value;

    if (!inicio || !fin) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR HORA
    |--------------------------------------------------------------------------
    */

    if (fin <= inicio) {

        document.getElementById(
            'selectedTime'
        ).textContent =
            `${inicio} – ${fin}`;

        document.getElementById(
            'scheduleDuration'
        ).textContent =
            'La hora hasta debe ser posterior a la hora desde.';

        document.getElementById(
            'scheduleDuration'
        ).style.color = '#EF4444';

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR FECHA/HORA OCULTA
    |--------------------------------------------------------------------------
    */

    const inicioActual =
        document.getElementById(
            'inicio'
        ).value;

    if (!inicioActual) {
        return false;
    }


    const fecha =
        inicioActual.substring(
            0,
            10
        );


    document.getElementById(
        'inicio'
    ).value =
        `${fecha}T${inicio}:00`;


    document.getElementById(
        'fin'
    ).value =
        `${fecha}T${fin}:00`;


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR HORARIO
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'selectedTime'
    ).textContent =
        `${inicio} – ${fin}`;


    /*
    |--------------------------------------------------------------------------
    | CALCULAR DURACION
    |--------------------------------------------------------------------------
    */

    const [horaI, minutoI] =
        inicio.split(':').map(Number);

    const [horaF, minutoF] =
        fin.split(':').map(Number);

    const minutosInicio =
        horaI * 60 + minutoI;

    const minutosFin =
        horaF * 60 + minutoF;

    const duracion =
        minutosFin - minutosInicio;

    const horas =
        Math.floor(duracion / 60);

    const minutos =
        duracion % 60;

    let textoDuracion = '';

    if (horas > 0) {

        textoDuracion +=
            horas +
            (horas === 1
                ? ' hora'
                : ' horas');
    }

    if (minutos > 0) {

        if (textoDuracion) {
            textoDuracion += ' ';
        }

        textoDuracion +=
            minutos +
            (minutos === 1
                ? ' minuto'
                : ' minutos');
    }

    document.getElementById(
        'scheduleDuration'
    ).textContent =
        `Duración: ${textoDuracion}`;

    document.getElementById(
        'scheduleDuration'
    ).style.color =
        '#6b7280';

    return true;
}


/*
|--------------------------------------------------------------------------
| ACTUALIZAR RESUMEN
|--------------------------------------------------------------------------
*/

function actualizarResumen()
{
    const recurrencia =
        document.querySelector(
            'input[name="recurrencia"]:checked'
        ).value;

    let textoRecurrencia = '';


    if (recurrencia === 'once') {

        textoRecurrencia =
            'Reservación única';
    }


    if (recurrencia === '1week') {

        textoRecurrencia =
            'Durante 1 semana';
    }


    if (recurrencia === '2weeks') {

        textoRecurrencia =
            'Durante 2 semanas';
    }


    if (recurrencia === '1month') {

        textoRecurrencia =
            'Durante 1 mes';
    }


    if (recurrencia === '2months') {

        textoRecurrencia =
            'Durante 2 meses';
    }


    if (recurrencia === '3months') {

        textoRecurrencia =
            'Durante 3 meses';
    }


    if (recurrencia === '4months') {

        textoRecurrencia =
            'Durante 4 meses';
    }


    if (recurrencia !== 'once') {

        const dias =
            obtenerDiasSeleccionados();

        if (dias.length) {

            textoRecurrencia +=
                ' · ' +
                dias.join(', ');
        }
    }


    document.getElementById(
        'selectedRecurrence'
    ).textContent =
        textoRecurrencia;
}


/*
|--------------------------------------------------------------------------
| LIMPIAR ERRORES
|--------------------------------------------------------------------------
*/

function limpiarErrores()
{
    document
        .querySelectorAll(
            '.error-text'
        )
        .forEach(e => {

            e.innerText = '';
        });


    document
        .querySelectorAll(
            '.input-custom'
        )
        .forEach(input => {

            input.classList.remove(
                'input-error'
            );
        });


    document.getElementById(
        'scheduleDuration'
    ).style.color =
        '#6b7280';
}


/*
|--------------------------------------------------------------------------
| MOSTRAR ERRORES
|--------------------------------------------------------------------------
*/

function mostrarErrores(errors)
{
    for (let campo in errors) {

        const input =
            document.getElementById(
                campo
            );

        const errorDiv =
            document.getElementById(
                'error-' + campo
            );

        if (input) {

            input.classList.add(
                'input-error'
            );
        }

        if (errorDiv) {

            errorDiv.innerText =
                errors[campo][0];
        }
    }
}


/*
|--------------------------------------------------------------------------
| INICIALIZAR
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        generarHoras();


        const calendarEl =
            document.getElementById(
                'calendar'
            );


        const fechaHoy =
            obtenerFechaLocal();


        /*
        |--------------------------------------------------------------------------
        | FULLCALENDAR
        |--------------------------------------------------------------------------
        */

        window.calendar =
            new FullCalendar.Calendar(
                calendarEl,
                {

                    initialView:
                        'timeGridWeek',

                    locale:
                        'es',

                    initialDate:
                        fechaHoy,

                    validRange: {
                        start:
                            fechaHoy
                    },

                    selectable:
                        true,

                    selectLongPressDelay:
                        0,

                    selectMinDistance:
                        0,

                    editable:
                        false,

                    height:
                        'auto',

                    contentHeight:
                        650,

                    expandRows:
                        true,

                    buttonText: {

                        today:
                            'Hoy',

                        month:
                            'Mes',

                        week:
                            'Semana',

                        day:
                            'Día'
                    },

                    allDaySlot:
                        false,

                    weekends:
                        false,

                    slotMinTime:
                        '07:00:00',

                    slotMaxTime:
                        '21:00:00',

                    firstDay:
                        1,

                    slotDuration:
                        '01:00:00',

                    headerToolbar: {

                        left:
                            'prev,next today',

                        center:
                            'title',

                        right:
                            'timeGridWeek,timeGridDay'
                    },

                    slotLabelFormat: {

                        hour:
                            '2-digit',

                        minute:
                            '2-digit',

                        hour12:
                            false
                    },

                    events: {

                        url:
                            '/reservacion/json/{{ $area->id }}',

                        method:
                            'GET'
                    },


                    /*
                    |--------------------------------------------------------------------------
                    | SELECCIONAR HORARIO
                    |--------------------------------------------------------------------------
                    */

                    select:
                        function (info) {

                            const fechaSeleccionada =
                                info.startStr.substring(
                                    0,
                                    10
                                );


                            if (
                                fechaSeleccionada <
                                fechaHoy
                            ) {

                                Swal.fire({

                                    icon:
                                        'warning',

                                    title:
                                        'Fecha no válida',

                                    text:
                                        'No puedes realizar una reservación en una fecha anterior al día de hoy.',

                                    confirmButtonText:
                                        'Entendido'
                                });

                                window.calendar.unselect();

                                return;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | FECHA
                            |--------------------------------------------------------------------------
                            */

                            const fechaTexto =
                                info.start.toLocaleDateString(
                                    'es-MX',
                                    {
                                        weekday:
                                            'long',

                                        day:
                                            'numeric',

                                        month:
                                            'long',

                                        year:
                                            'numeric'
                                    }
                                );


                            document.getElementById(
                                'selectedDate'
                            ).textContent =
                                capitalizar(
                                    fechaTexto
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | OBTENER HORAS DEL CALENDARIO
                            |--------------------------------------------------------------------------
                            */

                            const horaInicio =
                                formatearHora(
                                    info.start
                                );

                            const horaFin =
                                formatearHora(
                                    info.end
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | GUARDAR FECHA/HORA
                            |--------------------------------------------------------------------------
                            */

                            document.getElementById(
                                'inicio'
                            ).value =
                                info.startStr;


                            document.getElementById(
                                'fin'
                            ).value =
                                info.endStr;


                            /*
                            |--------------------------------------------------------------------------
                            | SELECCIONAR HORAS EN EL MODAL
                            |--------------------------------------------------------------------------
                            */

                            const selectInicio =
                                document.getElementById(
                                    'horaInicio'
                                );

                            const selectFin =
                                document.getElementById(
                                    'horaFin'
                                );


                            selectInicio.value =
                                horaInicio;


                            selectFin.value =
                                horaFin;


                            /*
                            |--------------------------------------------------------------------------
                            | SI FULLCALENDAR DEVUELVE 21:00
                            |--------------------------------------------------------------------------
                            */

                            if (
                                !selectFin.value
                            ) {

                                selectFin.value =
                                    '21:00';
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ACTUALIZAR RESUMEN
                            |--------------------------------------------------------------------------
                            */

                            actualizarHorario();


                            /*
                            |--------------------------------------------------------------------------
                            | REINICIAR RECURRENCIA
                            |--------------------------------------------------------------------------
                            */

                            document.getElementById(
                                'recurrence-once'
                            ).checked =
                                true;


                            document.getElementById(
                                'daysContainer'
                            ).classList.remove(
                                'show'
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | LIMPIAR DIAS
                            |--------------------------------------------------------------------------
                            */

                            document
                                .querySelectorAll(
                                    'input[name="dias[]"]'
                                )
                                .forEach(
                                    input => {

                                        input.checked =
                                            false;
                                    }
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | DETECTAR DIA
                            |--------------------------------------------------------------------------
                            */

                            const selectedDate =
                                new Date(
                                    info.start
                                );

                            const jsDay =
                                selectedDate.getDay();


                            /*
                            |--------------------------------------------------------------------------
                            | MARCAR AUTOMATICAMENTE DIA
                            |--------------------------------------------------------------------------
                            */

                            if (
                                jsDay >= 1 &&
                                jsDay <= 5
                            ) {

                                const selectedDay =
                                    document.getElementById(
                                        'day-' + jsDay
                                    );

                                if (selectedDay) {

                                    selectedDay.checked =
                                        true;
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | LIMPIAR ERRORES
                            |--------------------------------------------------------------------------
                            */

                            limpiarErrores();


                            /*
                            |--------------------------------------------------------------------------
                            | ACTUALIZAR RESUMEN
                            |--------------------------------------------------------------------------
                            */

                            actualizarResumen();


                            /*
                            |--------------------------------------------------------------------------
                            | ABRIR MODAL
                            |--------------------------------------------------------------------------
                            */

                            const modal =
                                new bootstrap.Modal(
                                    document.getElementById(
                                        'modalReserva'
                                    )
                                );

                            modal.show();
                        }
                }
            );


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR CALENDARIO
        |--------------------------------------------------------------------------
        */

        calendar.render();


        /*
        |--------------------------------------------------------------------------
        | CAMBIO HORA DESDE
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'horaInicio'
        ).addEventListener(
            'change',
            function () {

                actualizarHorario();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CAMBIO HORA HASTA
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'horaFin'
        ).addEventListener(
            'change',
            function () {

                actualizarHorario();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CAMBIO DE RECURRENCIA
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                'input[name="recurrencia"]'
            )
            .forEach(
                radio => {

                    radio.addEventListener(
                        'change',
                        function () {

                            const daysContainer =
                                document.getElementById(
                                    'daysContainer'
                                );


                            if (
                                this.value ===
                                'once'
                            ) {

                                daysContainer
                                    .classList
                                    .remove(
                                        'show'
                                    );

                            } else {

                                daysContainer
                                    .classList
                                    .add(
                                        'show'
                                    );
                            }


                            actualizarResumen();
                        }
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | CAMBIO DE DIAS
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                'input[name="dias[]"]'
            )
            .forEach(
                checkbox => {

                    checkbox.addEventListener(
                        'change',
                        function () {

                            actualizarResumen();
                        }
                    );
                }
            );

    }
);


/*
|--------------------------------------------------------------------------
| GUARDAR
|--------------------------------------------------------------------------
*/

function guardar()
{
    limpiarErrores();


    const btn =
        document.getElementById(
            'btnGuardar'
        );


    const recurrencia =
        document.querySelector(
            'input[name="recurrencia"]:checked'
        ).value;


    /*
    |--------------------------------------------------------------------------
    | DIAS
    |--------------------------------------------------------------------------
    */

    const dias = [];

    document
        .querySelectorAll(
            'input[name="dias[]"]:checked'
        )
        .forEach(
            input => {

                dias.push(
                    parseInt(
                        input.value
                    )
                );
            }
        );


    /*
    |--------------------------------------------------------------------------
    | VALIDAR DIAS
    |--------------------------------------------------------------------------
    */

    if (
        recurrencia !== 'once' &&
        dias.length === 0
    ) {

        Swal.fire({

            icon:
                'warning',

            title:
                'Selecciona los días',

            text:
                'Selecciona al menos un día de la semana para repetir la reserva.',

            confirmButtonText:
                'Entendido'
        });


        document.getElementById(
            'error-dias'
        ).innerText =
            'Selecciona al menos un día de la semana.';

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | FECHA
    |--------------------------------------------------------------------------
    */

    const inicio =
        document.getElementById(
            'inicio'
        ).value;

    const fin =
        document.getElementById(
            'fin'
        ).value;


    if (
        !inicio ||
        !fin
    ) {

        Swal.fire({

            icon:
                'warning',

            title:
                'Horario no seleccionado',

            text:
                'Debes seleccionar primero un horario en el calendario.',

            confirmButtonText:
                'Entendido'
        });

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR HORAS
    |--------------------------------------------------------------------------
    */

    const horaInicio =
        document.getElementById(
            'horaInicio'
        ).value;

    const horaFin =
        document.getElementById(
            'horaFin'
        ).value;


    if (
        !horaInicio ||
        !horaFin
    ) {

        Swal.fire({

            icon:
                'warning',

            title:
                'Horario incompleto',

            text:
                'Selecciona la hora desde y la hora hasta.',

            confirmButtonText:
                'Entendido'
        });

        return;
    }


    if (
        horaFin <= horaInicio
    ) {

        Swal.fire({

            icon:
                'warning',

            title:
                'Horario no válido',

            text:
                'La hora hasta debe ser posterior a la hora desde.',

            confirmButtonText:
                'Entendido'
        });

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | ASEGURAR HORARIO ACTUALIZADO
    |--------------------------------------------------------------------------
    */

    if (!actualizarHorario()) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | DESHABILITAR BOTON
    |--------------------------------------------------------------------------
    */

    btn.disabled =
        true;

    btn.innerHTML =
        'Guardando...';


    /*
    |--------------------------------------------------------------------------
    | LOADING
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        title:
            'Guardando reservación',

        text:
            'Por favor espera...',

        allowOutsideClick:
            false,

        allowEscapeKey:
            false,

        showConfirmButton:
            false,

        didOpen:
            () => {

                Swal.showLoading();
            }
    });


    /*
    |--------------------------------------------------------------------------
    | ENVIAR
    |--------------------------------------------------------------------------
    */

    fetch(
        '/reservacion/store',
        {
            method:
                'POST',

            headers: {

                'Content-Type':
                    'application/json',

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute(
                            'content'
                        ),

                'Accept':
                    'application/json'
            },

            body:
                JSON.stringify({

                    titulo:
                        document.getElementById(
                            'titulo'
                        ).value,

                    name:
                        document.getElementById(
                            'name'
                        ).value,

                    asignatura:
                        document.getElementById(
                            'asignatura'
                        ).value,

                    obs:
                        document.getElementById(
                            'obs'
                        ).value,

                    espacio:
                        @json($area->id),

                    inicio:
                        document.getElementById(
                            'inicio'
                        ).value,

                    fin:
                        document.getElementById(
                            'fin'
                        ).value,

                    recurrencia:
                        recurrencia,

                    dias:
                        dias
                })
        }
    )


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA
    |--------------------------------------------------------------------------
    */

    .then(
        async res => {

            let data;


            try {

                data =
                    await res.json();

            } catch (error) {

                Swal.close();

                await Swal.fire({

                    icon:
                        'error',

                    title:
                        'Respuesta inválida',

                    text:
                        'El servidor devolvió una respuesta inválida.',

                    confirmButtonText:
                        'Aceptar'
                });

                return;
            }


            Swal.close();


            /*
            |--------------------------------------------------------------------------
            | VALIDACION
            |--------------------------------------------------------------------------
            */

            if (
                res.status === 422
            ) {

                mostrarErrores(
                    data.errors || {}
                );


                let mensajes = [];


                if (data.errors) {

                    Object.values(
                        data.errors
                    ).forEach(
                        errores => {

                            errores.forEach(
                                error => {

                                    mensajes.push(
                                        error
                                    );
                                }
                            );
                        }
                    );
                }


                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Revisa los datos',

                    html:
                        mensajes.length
                            ?
                            `
                                <div class="text-start">

                                    ${mensajes.map(
                                        mensaje => `
                                            <div class="mb-2">
                                                • ${mensaje}
                                            </div>
                                        `
                                    ).join('')}

                                </div>
                            `
                            :
                            'Revisa los datos ingresados.',

                    confirmButtonText:
                        'Entendido'
                });

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CONFLICTOS
            |--------------------------------------------------------------------------
            */

            if (
                res.status === 409
            ) {

                let listaConflictos =
                    '';


                if (
                    data.conflictos &&
                    data.conflictos.length
                ) {

                    listaConflictos = `

                        <div class="text-start">

                            <strong>
                                Fechas ocupadas:
                            </strong>

                            <ul class="conflict-list">

                                ${data.conflictos
                                    .map(
                                        fecha => `
                                            <li>
                                                ${fecha}
                                            </li>
                                        `
                                    )
                                    .join('')
                                }

                            </ul>

                        </div>
                    `;
                }


                Swal.fire({

                    icon:
                        'warning',

                    title:
                        'Horario no disponible',

                    html: `

                        <p>
                            ${
                                data.message ||
                                'Existen conflictos de horario.'
                            }
                        </p>

                        ${listaConflictos}

                    `,

                    confirmButtonText:
                        'Entendido'
                });

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | OTROS ERRORES
            |--------------------------------------------------------------------------
            */

            if (!res.ok) {

                Swal.fire({

                    icon:
                        'error',

                    title:
                        'No se pudo guardar',

                    text:
                        data.message ||
                        'Ocurrió un error al guardar la reservación.',

                    confirmButtonText:
                        'Aceptar'
                });

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | EXITO
            |--------------------------------------------------------------------------
            */

            if (data.ok) {

                let mensaje =
                    data.message ||
                    'La reservación se creó correctamente.';


                if (
                    data.cantidad &&
                    data.cantidad > 1
                ) {

                    mensaje = `

                        Se crearon correctamente

                        <strong>
                            ${data.cantidad}
                        </strong>

                        reservaciones.

                    `;
                }


                await Swal.fire({

                    icon:
                        'success',

                    title:
                        '¡Reservación registrada!',

                    html:
                        mensaje,

                    confirmButtonText:
                        'Aceptar'
                });


                /*
                |--------------------------------------------------------------------------
                | CERRAR MODAL
                |--------------------------------------------------------------------------
                */

                const modalElement =
                    document.getElementById(
                        'modalReserva'
                    );


                const modal =
                    bootstrap.Modal
                        .getInstance(
                            modalElement
                        );


                if (modal) {
                    modal.hide();
                }


                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR CALENDARIO
                |--------------------------------------------------------------------------
                */

                if (window.calendar) {

                    window.calendar.refetchEvents();
                }


                /*
                |--------------------------------------------------------------------------
                | LIMPIAR FORMULARIO
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'name'
                ).value = '';

                document.getElementById(
                    'asignatura'
                ).value = '';

                document.getElementById(
                    'titulo'
                ).value = '';

                document.getElementById(
                    'obs'
                ).value = '';

                document.getElementById(
                    'inicio'
                ).value = '';

                document.getElementById(
                    'fin'
                ).value = '';


                /*
                |--------------------------------------------------------------------------
                | REINICIAR HORAS
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'horaInicio'
                ).value =
                    '07:00';

                document.getElementById(
                    'horaFin'
                ).value =
                    '08:00';


                document.getElementById(
                    'selectedDate'
                ).textContent =
                    '—';

                document.getElementById(
                    'selectedTime'
                ).textContent =
                    '—';

                document.getElementById(
                    'scheduleDuration'
                ).textContent =
                    'Duración: —';


                /*
                |--------------------------------------------------------------------------
                | RECURRENCIA
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'recurrence-once'
                ).checked =
                    true;


                document.getElementById(
                    'daysContainer'
                ).classList.remove(
                    'show'
                );


                document
                    .querySelectorAll(
                        'input[name="dias[]"]'
                    )
                    .forEach(
                        input => {

                            input.checked =
                                false;
                        }
                    );


                document.getElementById(
                    'selectedRecurrence'
                ).textContent =
                    'Reservación única';
            }
        }
    )


    /*
    |--------------------------------------------------------------------------
    | ERROR DE CONEXION
    |--------------------------------------------------------------------------
    */

    .catch(
        error => {

            console.error(
                error
            );

            Swal.close();


            Swal.fire({

                icon:
                    'error',

                title:
                    'Error de conexión',

                text:
                    'No fue posible comunicarse con el servidor.',

                confirmButtonText:
                    'Aceptar'
            });
        }
    )


    /*
    |--------------------------------------------------------------------------
    | FINALIZAR
    |--------------------------------------------------------------------------
    */

    .finally(
        () => {

            btn.disabled =
                false;

            btn.innerHTML =
                'Guardar';
        }
    );
}

</script>

</body>

</html>