<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consultar Folio</title>

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
            font-size:24px;
            font-weight:800;
        }
        label{
            font-weight:700;
            color:var(--azul);
        }
        input{
            width:100%;
            padding:12px;
            border-radius:10px;
            border:1px solid #d8dee6;
            margin-top:8px;
            box-sizing: border-box;
        }
        .btn{
            margin-top:20px;
            background:var(--verde);
            color:#fff;
            border:none;
            padding:14px;
            border-radius:10px;
            font-weight:800;
            cursor:pointer;
        }
        .result{
            margin-top:25px;
            padding:20px;
            border-radius:10px;
            background:#f8fafb;
            display:none;
        }
        .badge{
            padding:6px 12px;
            border-radius:20px;
            color:#fff;
            font-weight:700;
            font-size:12px;
        }
        .back{
            display:inline-block;
            margin-top:20px;
            color:var(--azul);
            text-decoration:none;
            font-weight:700;
            margin-bottom:15px;
        }
        p.sub{
            margin-top:-10px;
            color:#6b7280;
            margin-bottom:30px;
        }
        .mx-15 {
            margin-left:15px;
            margin-right:15px;
        }
        </style>
    </head>

    <body>

        <div class="wrapper">
            <div class="card">
                <a href="{{ url('/') }}" class="back">← Regresar al inicio</a>
                <h1>Consultar Reporte por Folio</h1>
                <p class="sub">
                    Revisa cómo va tu reporte en segundos usando tu folio.
                </p>
                <div class="card__body">
                    <label>Ingresa tu folio</label>
                    <input type="text" id="folio" placeholder="Ej. RPT-2026-0001">
                    <button class="btn" onclick="buscarFolio()">Buscar</button>
                </div>
                <div id="resultado" class="result"></div> <br>
                
            </div>
        </div>
        <script>
        async function buscarFolio(){

            let folio = document.getElementById('folio').value;

            if(!folio){
                Swal.fire('Error','Ingresa un folio','warning');
                return;
            }

            Swal.fire({
                title:'Buscando...',
                allowOutsideClick:false,
                didOpen:()=>Swal.showLoading()
            });

            try{

                const response = await fetch(`{{ url('reportes/buscar') }}?folio=${folio}`,{
                    headers:{
                        'Accept':'application/json'
                    }
                });

                const data = await response.json();

                Swal.close();

                if(response.ok){

                    let estadoColor = data.color ?? '#6B7280';

                    document.getElementById('resultado').style.display = 'block';

                    document.getElementById('resultado').innerHTML = `
                        <b>Folio:</b> ${data.folio} <br><br>
                        <b>Nombre:</b> ${data.name} <br>
                        <b>Área:</b> ${data.area ?? 'N/A'} <br>
                        <b>Equipo:</b> ${data.item_name} <br>
                        <b>Problema:</b> ${data.problem} <br><br>

                        <b>Estado:</b>
                        <span class="badge" style="background:${estadoColor}">
                            ${data.status}
                        </span>
                    `;

                }else{

                    Swal.fire('No encontrado','No existe ese folio','error');

                }

            }catch(e){

                Swal.close();
                Swal.fire('Error','No se pudo consultar','error');

            }
        }
        </script>

    </body>
</html>