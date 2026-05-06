<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGIT - Reporte de Mantenimiento</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{
            margin:0;
            font-family:'Nunito',sans-serif;
            background:#f2f2f2;
        }
        :root{
            --azul:#003865;
            --verde:#6C9A1F;
        }
        .wrapper{
            max-width:1100px;
            margin:auto;
            padding:40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 90vh;
        }
        .card{
            background:#fff;
            border-radius:14px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            padding:35px;
        }
        h1{
            margin:0 0 25px;
            color:var(--azul);
            font-size:26px;
            font-weight:800;
        }
        p.sub{
            margin-top:-10px;
            color:#6b7280;
            margin-bottom:30px;
        }
        .row{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
        }
        .col-full{
            grid-column:1 / -1;
        }
        label{
            display:block;
            margin-bottom:7px;
            font-size:14px;
            font-weight:700;
            color:var(--azul);
        }
        input,select,textarea{
            width:100%;
            border:1px solid #d8dee6;
            border-radius:10px;
            padding:12px 14px;
            font-size:14px;
            outline:none;
            box-sizing:border-box;
        }
        textarea{
            min-height:120px;
            resize:vertical;
        }
        .btn{
            background:var(--verde);
            color:#fff;
            border:none;
            padding:14px 24px;
            border-radius:10px;
            font-weight:800;
            cursor:pointer;
        }
        .btn:disabled{
            opacity:.7;
            cursor:not-allowed;
        }
        .back{
            display:inline-block;
            margin-top:18px;
            color:var(--azul);
            text-decoration:none;
            font-weight:700;
            margin-bottom:15px;
        }
        @media(max-width:768px){
            .row{ grid-template-columns:1fr; }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="card">
            <a href="{{ url('/') }}" class="back">← Regresar al inicio</a>

            <h1>SIGIT – Reporte de Mantenimiento</h1>

            <p class="sub">
                Registra fallas, daños o incidencias en equipo, mobiliario o infraestructura.
            </p>

            <form id="formReporte" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-full">
                        <label>Nombre completo</label>
                        <input type="text" name="name" required>
                    </div>

                    <div>
                        <label>Edificio</label>
                        <input type="text" name="build">
                    </div>
                    <div>
                        <label>Aula / Área</label>
                        <input type="text" name="area">
                    </div>

                    <div>
                        <label>Tipo</label>
                        <select name="type" required>
                            <option value="">Seleccionar</option>
                            <option value="Equipo">Equipo</option>
                            <option value="Material">Material</option>
                            <option value="Mobiliario">Mobiliario</option>
                            <option value="Infraestructura">Infraestructura</option>
                        </select>
                    </div>

                    <div>
                        <label>Nombre del artículo</label>
                        <input type="text" name="item_name" required>
                    </div>

                    <div>
                        <label>Prioridad</label>
                        <select name="priority">
                        <option value="Baja">Baja</option>
                        <option value="Media" selected>Media</option>
                        <option value="Alta">Alta</option>
                        <option value="Urgente">Urgente</option>
                        </select>
                    </div>

                    <div>
                        <label>Problema detectado</label>
                        <input type="text" name="problem" required>
                    </div>

                    <div class="col-full">
                        <label>Descripción detallada</label>
                        <textarea name="description"></textarea>
                    </div>

                    <div class="col-full">
                        <label>Fotografía / Evidencia</label>
                        <input type="file" name="photo">
                    </div>

                    <div class="col-full">
                        <button type="submit" class="btn " id="btnEnviar">
                            Enviar Reporte
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('formReporte').addEventListener('submit', async function(e){

            e.preventDefault();

            let form = this;
            let btn = document.getElementById('btnEnviar');
            let data = new FormData(form);

            btn.disabled = true;
            btn.innerText = 'Enviando...';

            Swal.fire({
                title: 'Procesando...',
                text: 'Enviando reporte',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try{

                const response = await fetch("{{ url('reportes') }}", {
                    method: 'POST',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':'application/json'
                    },
                    body:data
                });

                const result = await response.json();

                Swal.close();

                if(response.ok){

                    Swal.fire({
                        icon:'success',
                        title:'Reporte enviado',
                        html:`Tu folio es:<br><b>${result.folio}</b>`,
                        confirmButtonColor:'#6C9A1F'
                    });

                    form.reset();

                }else{

                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text: result.message ?? 'No se pudo enviar'
                    });

                }

            }catch(error){

                Swal.close();

                Swal.fire({
                    icon:'error',
                    title:'Error de conexión',
                    text:'Intenta nuevamente'
                });

            }

            btn.disabled = false;
            btn.innerText = 'Enviar Reporte';

        });
    </script>

</body>
</html>