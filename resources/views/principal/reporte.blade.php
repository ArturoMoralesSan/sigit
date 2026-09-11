<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIGIT - Reporte de Mantenimiento</title>


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
            box-sizing:border-box;
        }


        /* ==========================================================
           TITULO
           ========================================================== */

        h1{
            margin:0 0 25px;
            color:var(--azul);
            font-size:26px;
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
           FORMULARIO
           ========================================================== */

        .row{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
        }

        .col-full{
            grid-column:1 / -1;
        }


        /* ==========================================================
           LABELS
           ========================================================== */

        label{
            display:block;
            margin-bottom:7px;
            font-size:14px;
            font-weight:700;
            color:var(--azul);
        }


        /* ==========================================================
           INPUTS
           ========================================================== */

        input,
        select,
        textarea{
            width:100%;
            border:1px solid #d8dee6;
            border-radius:10px;
            padding:12px 14px;
            font-size:14px;
            outline:none;
            box-sizing:border-box;
            font-family:'Nunito',sans-serif;
            transition:.2s;
        }

        input:focus,
        select:focus,
        textarea:focus{
            border-color:var(--verde);
            box-shadow:0 0 0 2px rgba(108,154,31,.08);
        }


        /* ==========================================================
           TEXTAREA
           ========================================================== */

        textarea{
            min-height:120px;
            resize:vertical;
        }


        /* ==========================================================
           BOTON
           ========================================================== */

        .btn{
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

        .btn:disabled{
            opacity:.7;
            cursor:not-allowed;
        }


        /* ==========================================================
           REGRESAR
           ========================================================== */

        .back{
            display:inline-block;
            margin-top:0;
            color:var(--azul);
            text-decoration:none;
            font-weight:700;
            margin-bottom:15px;
        }

        .back:hover{
            color:var(--verde);
        }


        /* ==========================================================
           SWEETALERT
           ========================================================== */

        .swal2-popup{
            font-family:'Nunito',sans-serif;
            border-radius:14px;
        }

        .swal2-title{
            color:var(--azul);
        }

        .swal2-confirm{
            background-color:var(--verde) !important;
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

            .row{
                grid-template-columns:1fr;
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
                SIGIT – Reporte de Mantenimiento
            </h1>


            <p class="sub">
                Registra fallas, daños o incidencias en equipo,
                mobiliario o infraestructura.
            </p>


            {{-- ======================================================
                 FORMULARIO
                 ====================================================== --}}

            <form
                id="formReporte"
                enctype="multipart/form-data"
            >

                <div class="row">


                    {{-- ==================================================
                         NOMBRE
                         ================================================== --}}

                    <div class="col-full">

                        <label>
                            Nombre completo
                        </label>

                        <input
                            type="text"
                            name="name"
                            required
                        >

                    </div>


                    {{-- ==================================================
                         EDIFICIO
                         ================================================== --}}

                    <div>

                        <label>
                            Edificio
                        </label>

                        <input
                            type="text"
                            name="build"
                        >

                    </div>


                    {{-- ==================================================
                         AREA
                         ================================================== --}}

                    <div>

                        <label>
                            Aula / Área
                        </label>

                        <input
                            type="text"
                            name="area"
                        >

                    </div>


                    {{-- ==================================================
                         TIPO
                         ================================================== --}}

                    <div>

                        <label>
                            Tipo
                        </label>

                        <select
                            name="type"
                            required
                        >

                            <option value="">
                                Seleccionar
                            </option>

                            <option value="Equipo">
                                Equipo
                            </option>

                            <option value="Material">
                                Material
                            </option>

                            <option value="Mobiliario">
                                Mobiliario
                            </option>

                            <option value="Infraestructura">
                                Infraestructura
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         NOMBRE ARTICULO
                         ================================================== --}}

                    <div>

                        <label>
                            Nombre del artículo
                        </label>

                        <input
                            type="text"
                            name="item_name"
                            required
                        >

                    </div>


                    {{-- ==================================================
                         PRIORIDAD
                         ================================================== --}}

                    <div>

                        <label>
                            Prioridad
                        </label>

                        <select name="priority">

                            <option value="Baja">
                                Baja
                            </option>

                            <option
                                value="Media"
                                selected
                            >
                                Media
                            </option>

                            <option value="Alta">
                                Alta
                            </option>

                            <option value="Urgente">
                                Urgente
                            </option>

                        </select>

                    </div>


                    {{-- ==================================================
                         PROBLEMA
                         ================================================== --}}

                    <div>

                        <label>
                            Problema detectado
                        </label>

                        <input
                            type="text"
                            name="problem"
                            required
                        >

                    </div>


                    {{-- ==================================================
                         DESCRIPCION
                         ================================================== --}}

                    <div class="col-full">

                        <label>
                            Descripción detallada
                        </label>

                        <textarea
                            name="description"
                        ></textarea>

                    </div>


                    {{-- ==================================================
                         FOTOGRAFIA
                         Actualmente deshabilitada
                         ================================================== --}}

                    {{--

                    <div class="col-full">

                        <label>
                            Fotografía / Evidencia
                        </label>

                        <input
                            type="file"
                            name="photo"
                        >

                    </div>

                    --}}


                    {{-- ==================================================
                         BOTON
                         ================================================== --}}

                    <div class="col-full">

                        <button
                            type="submit"
                            class="btn"
                            id="btnEnviar"
                        >
                            Enviar Reporte
                        </button>

                    </div>


                </div>

            </form>


        </div>

    </div>

</div>



<script>

    /* ==============================================================
       FORMULARIO
       ============================================================== */

    document
        .getElementById('formReporte')
        .addEventListener(
            'submit',
            async function(e){

                e.preventDefault();


                /* ==================================================
                   VARIABLES
                   ================================================== */

                let form = this;

                let btn =
                    document.getElementById(
                        'btnEnviar'
                    );


                let data =
                    new FormData(form);


                /* ==================================================
                   DESHABILITAR BOTON
                   ================================================== */

                btn.disabled =
                    true;

                btn.innerText =
                    'Enviando...';


                /* ==================================================
                   LOADING
                   ================================================== */

                Swal.fire({

                    title:
                        'Procesando...',

                    text:
                        'Enviando reporte',

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


                /* ==================================================
                   ENVIO
                   ================================================== */

                try{

                    const response =
                        await fetch(
                            "{{ url('reportes') }}",
                            {

                                method:
                                    'POST',

                                headers:{

                                    'X-CSRF-TOKEN':
                                        document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .content,

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    data

                            }
                        );


                    /* ==================================================
                       RESPUESTA JSON
                       ================================================== */

                    const result =
                        await response.json();


                    Swal.close();


                    /* ==================================================
                       EXITO
                       ================================================== */

                    if(response.ok){

                        await Swal.fire({

                            icon:
                                'success',

                            title:
                                'Reporte enviado',

                            html:
                                `
                                    Tu folio es:
                                    <br>
                                    <b>
                                        ${result.folio}
                                    </b>
                                `,

                            confirmButtonColor:
                                '#6C9A1F'

                        });


                        /* ==================================================
                           LIMPIAR FORMULARIO
                           ================================================== */

                        form.reset();


                    }else{


                        /* ==================================================
                           ERROR DEL SERVIDOR
                           ================================================== */

                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Error',

                            text:
                                result.message ??
                                'No se pudo enviar'

                        });

                    }


                }catch(error){


                    /* ==================================================
                       ERROR DE CONEXION
                       ================================================== */

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
                            'Intenta nuevamente'

                    });

                }


                /* ==================================================
                   RESTAURAR BOTON
                   ================================================== */

                btn.disabled =
                    false;

                btn.innerText =
                    'Enviar Reporte';

            }
        );

</script>


</body>

</html>
