<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIGIT - Consultar Reporte</title>


    {{-- Nunito --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- SweetAlert2 --}}
    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
    ></script>


    <style>

        /* ==========================================================
           VARIABLES
           ========================================================== */

        :root{
            --azul:#003865;
            --verde:#6C9A1F;
        }


        /* ==========================================================
           GENERAL
           ========================================================== */

        body{
            margin:0;
            font-family:'Nunito',sans-serif;
            background:#f2f2f2;
            min-height:100vh;
        }


        /* ==========================================================
           CONTENEDOR GENERAL
           ========================================================== */

        .page-container{
            width:100%;
            padding:35px 20px;
            box-sizing:border-box;
        }


        /* ==========================================================
           LOGO INSTITUCIONAL
           ========================================================== */

        .logo-sigit{
            width:300px;
            max-width:80%;
            height:auto;
            display:block;
            margin:0 auto 5px;
        }


        /* ==========================================================
           WRAPPER
           ========================================================== */

        .wrapper{
            max-width:1100px;
            margin:0 auto;
            padding:20px 0 40px;
            display:flex;
            justify-content:center;
            width:100%;
            box-sizing:border-box;
        }


        /* ==========================================================
           CARD
           ========================================================== */

        .card{
            background:#fff;
            border-radius:14px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            padding:35px;
            width:100%;
            max-width:700px;
            box-sizing:border-box;
        }


        /* ==========================================================
           TITULO
           ========================================================== */

        h1{
            margin:0 0 25px;
            color:var(--azul);
            font-size:24px;
            font-weight:800;
        }


        /* ==========================================================
           SUBTITULO
           ========================================================== */

        p.sub{
            margin-top:-10px;
            color:#6b7280;
            margin-bottom:30px;
        }


        /* ==========================================================
           LABEL
           ========================================================== */

        label{
            display:block;
            margin-bottom:7px;
            font-size:14px;
            font-weight:700;
            color:var(--azul);
        }


        /* ==========================================================
           INPUT
           ========================================================== */

        input{
            width:100%;
            padding:12px 14px;
            border-radius:10px;
            border:1px solid #d8dee6;
            box-sizing:border-box;
            font-family:'Nunito',sans-serif;
            font-size:14px;
            outline:none;
            transition:.2s;
        }

        input:focus{
            border-color:var(--verde);
            box-shadow:0 0 0 2px rgba(108,154,31,.08);
        }


        /* ==========================================================
           BOTON
           ========================================================== */

        .btn{
            margin-top:20px;
            background:var(--verde);
            color:#fff;
            border:none;
            padding:14px 24px;
            border-radius:10px;
            font-weight:800;
            cursor:pointer;
            font-family:'Nunito',sans-serif;
            transition:.2s;
        }

        .btn:hover{
            background:var(--azul);
        }


        /* ==========================================================
           RESULTADO
           ========================================================== */

        .result{
            margin-top:25px;
            padding:20px;
            border-radius:10px;
            background:#f8fafb;
            border:1px solid #e5e7eb;
            display:none;
            color:#374151;
            line-height:1.6;
        }


        /* ==========================================================
           RESULTADO - FOLIO
           ========================================================== */

        .result-folio{
            color:var(--azul);
            font-size:18px;
            font-weight:800;
            margin-bottom:15px;
        }


        /* ==========================================================
           RESULTADO - ESTADO
           ========================================================== */

        .result-status{
            margin-top:15px;
            padding-top:15px;
            border-top:1px solid #e5e7eb;
        }

        .badge{
            display:inline-block;
            padding:6px 12px;
            border-radius:20px;
            color:#fff;
            font-weight:700;
            font-size:12px;
            margin-left:5px;
        }


        /* ==========================================================
           REGRESAR
           ========================================================== */

        .back{
            display:inline-block;
            color:var(--azul);
            text-decoration:none;
            font-weight:700;
            margin-bottom:15px;
        }

        .back:hover{
            color:var(--verde);
        }


        /* ==========================================================
           BODY DE CARD
           ========================================================== */

        .card__body{
            width:100%;
        }


        /* ==========================================================
           RESPONSIVE
           ========================================================== */

        @media(max-width:768px){

            .page-container{
                padding:20px 10px;
            }

            .logo-sigit{
                width:240px;
            }

            .wrapper{
                padding:15px 0 25px;
            }

            .card{
                padding:25px 20px;
            }

            h1{
                font-size:22px;
            }

        }


        @media(max-width:480px){

            .logo-sigit{
                width:220px;
            }

            .card{
                padding:22px 17px;
            }

            .btn{
                width:100%;
            }

        }

    </style>

</head>


<body>


<div class="page-container">


    {{-- ==========================================================
         LOGO INSTITUCIONAL
         ========================================================== --}}

    <img
        src="{{ asset('img/LOGO_BIS_UNIPOLI.png') }}"
        alt="Universidad Politécnica de Durango"
        class="logo-sigit"
    >


    {{-- ==========================================================
         CONTENIDO
         ========================================================== --}}

    <div class="wrapper">

        <div class="card">


            {{-- ======================================================
                 REGRESAR
                 ====================================================== --}}

            <a
                href="{{ url('/') }}"
                class="back"
            >
                ← Regresar al inicio
            </a>


            {{-- ======================================================
                 TITULO
                 ====================================================== --}}

            <h1>
                Consultar Reporte por Folio
            </h1>


            <p class="sub">
                Revisa cómo va tu reporte en segundos usando tu folio.
            </p>


            {{-- ======================================================
                 BUSQUEDA
                 ====================================================== --}}

            <div class="card__body">

                <label for="folio">
                    Ingresa tu folio
                </label>

                <input
                    type="text"
                    id="folio"
                    placeholder="Ej. RPT-2026-0001"
                    autocomplete="off"
                >

                <button
                    type="button"
                    class="btn"
                    onclick="buscarFolio()"
                >
                    Buscar
                </button>

            </div>


            {{-- ======================================================
                 RESULTADO
                 ====================================================== --}}

            <div
                id="resultado"
                class="result"
            ></div>


        </div>

    </div>

</div>



<script>


/* ==============================================================
   BUSCAR FOLIO
   ============================================================== */

async function buscarFolio(){


    /* ==========================================================
       OBTENER FOLIO
       ========================================================== */

    let folio =
        document
            .getElementById('folio')
            .value
            .trim();


    /* ==========================================================
       VALIDAR FOLIO
       ========================================================== */

    if(!folio){

        Swal.fire({

            icon:
                'warning',

            title:
                'Folio requerido',

            text:
                'Ingresa un folio para realizar la consulta.',

            confirmButtonColor:
                '#6C9A1F'

        });

        return;

    }


    /* ==========================================================
       OCULTAR RESULTADO ANTERIOR
       ========================================================== */

    document
        .getElementById('resultado')
        .style.display =
            'none';


    /* ==========================================================
       LOADING
       ========================================================== */

    Swal.fire({

        title:
            'Buscando...',

        text:
            'Consultando información del reporte.',

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


    /* ==========================================================
       CONSULTA
       ========================================================== */

    try{


        const response =
            await fetch(
                `{{ url('reportes/buscar') }}?folio=${encodeURIComponent(folio)}`,
                {

                    headers:{

                        'Accept':
                            'application/json'

                    }

                }
            );


        /* ======================================================
           RESPUESTA
           ====================================================== */

        const data =
            await response.json();


        Swal.close();


        /* ======================================================
           REPORTE ENCONTRADO
           ====================================================== */

        if(response.ok){


            let estadoColor =
                data.color ??
                '#6B7280';


            document
                .getElementById('resultado')
                .style.display =
                    'block';


            document
                .getElementById('resultado')
                .innerHTML = `

                    <div class="result-folio">

                        Folio:
                        ${data.folio}

                    </div>


                    <div>

                        <b>Nombre:</b>
                        ${data.name ?? 'N/A'}

                    </div>


                    <div>

                        <b>Área:</b>
                        ${data.area ?? 'N/A'}

                    </div>


                    <div>

                        <b>Equipo:</b>
                        ${data.item_name ?? 'N/A'}

                    </div>


                    <div>

                        <b>Problema:</b>
                        ${data.problem ?? 'N/A'}

                    </div>


                    <div class="result-status">

                        <b>Estado:</b>

                        <span
                            class="badge"
                            style="background:${estadoColor}"
                        >
                            ${data.status ?? 'Sin estado'}
                        </span>

                    </div>

                `;


        }else{


            /* ==================================================
               NO ENCONTRADO
               ================================================== */

            Swal.fire({

                icon:
                    'error',

                title:
                    'No encontrado',

                text:
                    'No existe ningún reporte asociado a ese folio.',

                confirmButtonColor:
                    '#6C9A1F'

            });

        }


    }catch(e){


        /* ======================================================
           ERROR
           ====================================================== */

        console.error(
            e
        );


        Swal.close();


        Swal.fire({

            icon:
                'error',

            title:
                'Error de conexión',

            text:
                'No se pudo consultar el reporte. Intenta nuevamente.',

            confirmButtonColor:
                '#6C9A1F'

        });

    }

}

</script>


</body>

</html>
