<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>SIGIT - Calendario de reservaciones</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
body{
    background:#f2f2f2;
    font-family:'Nunito',sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    height:100vh;
}

:root{
    --azul:#003865;
    --verde:#6C9A1F;
}

/* WRAPPER */
.wrapper{
    max-width:1100px;
    margin:auto;
    padding:40px 20px;
    display:flex;
    justify-content:center;
}

/* CARD */
.card-custom{
    background:#fff;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    padding:30px;
    width:100%;
}

/* TITULO */
h1{
    margin:0 0 10px;
    color:var(--azul);
    font-size:26px;
    font-weight:800;
}

.sub{
    color:#6b7280;
    margin-bottom:20px;
}

/* CALENDAR */
#calendar{
    margin-top:10px;
}

/* BACK */
.back{
    display:inline-block;
    margin-bottom:15px;
    color:var(--azul);
    text-decoration:none;
    font-weight:700;
}

/* ================= MODAL ================= */

.modal-custom{
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    padding:10px;
}

.form-label-custom{
    display:block;
    font-size:14px;
    font-weight:700;
    color:var(--azul);
    margin-bottom:6px;
}

.input-custom{
    width:100%;
    border:1px solid #d8dee6;
    border-radius:10px;
    padding:12px 14px;
    font-size:14px;
    outline:none;
    transition:.2s;
}

.input-custom:focus{
    border-color:var(--verde);
}

.textarea-custom{
    min-height:100px;
    resize:vertical;
}

/* BOTONES */
.btn-save{
    background:var(--verde);
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    font-weight:700;
}

.btn-save:hover{
    background:var(--azul);
}

.btn-cancel{
    background:#6b7280;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    font-weight:700;
}

.btn-cancel:hover{
    background:#4b5563;
}

.error-text{
    color:#EF4444;
    font-size:13px;
    margin-top:4px;
}

.input-error{
    border-color:#EF4444 !important;
}
</style>

</head>

<body>

<div class="wrapper">

    <div class="card-custom">

        <a href="{{ url('agenda-espacios') }}" class="back">← Regresar</a>

        <h1>SIGIT – Reserva de {{ $area->name }}</h1>

        <p class="sub">
            Consulta disponibilidad y agenda espacios.
        </p>

        <div id="calendar"></div>

    </div>

</div>


<!-- MODAL -->
<div class="modal fade" id="modalReserva">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" style="color: var(--azul);">
                    Nueva Reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label-custom">Asignatura / Práctica</label>
                    <input type="text" id="titulo" class="input-custom">
                    <div class="error-text" id="error-titulo"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Objetivo de la práctica</label>
                    <textarea id="obs" class="input-custom textarea-custom"></textarea>
                    <div class="error-text" id="error-obs"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Nombre completo</label>
                    <input type="text" id="name" class="input-custom">
                    <div class="error-text" id="error-name"></div>
                </div>

                <input type="hidden" id="inicio">
                <input type="hidden" id="fin">

            </div>

            <div class="modal-footer border-0">
                <button class="btn-cancel" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button class="btn-save" onclick="guardar()">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calendar');

    window.calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'timeGridWeek',
        locale: 'es',
        selectable: true,
        editable: false,
        height: 'auto',
        contentHeight: 650,
        expandRows: true,

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },

        allDaySlot: false,
        weekends: false,

        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',

        firstDay: 1,
        slotDuration: '01:00:00',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        events: {
            url:'/reservacion/json/{{ $area->id }}',
            method:'GET'
        },

        select: function (info) {

            document.getElementById('inicio').value = info.startStr;
            document.getElementById('fin').value = info.endStr;

            new bootstrap.Modal(
                document.getElementById('modalReserva')
            ).show();

        }

    });

    calendar.render();

});


function limpiarErrores(){
    document.querySelectorAll('.error-text').forEach(e => e.innerText = '');
    document.querySelectorAll('.input-custom').forEach(i => i.classList.remove('input-error'));
}

function mostrarErrores(errors){

    for (let campo in errors){

        let input = document.getElementById(campo);
        let errorDiv = document.getElementById('error-' + campo);

        if(input){
            input.classList.add('input-error');
        }

        if(errorDiv){
            errorDiv.innerText = errors[campo][0];
        }
    }
}


function guardar() {

    limpiarErrores();

    fetch('/reservacion/store',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },

        body:JSON.stringify({
            titulo:document.getElementById('titulo').value,
            name:document.getElementById('name').value,
            obs:document.getElementById('obs').value,
            espacio: @json($area->id),
            inicio:document.getElementById('inicio').value,
            fin:document.getElementById('fin').value
        })

    })
    .then(async res => {

        let data = await res.json();

        if(!res.ok){
            mostrarErrores(data.errors);
            return;
        }

        location.reload();

    });

}
</script>

</body>
</html>