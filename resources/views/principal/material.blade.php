<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Consultar material</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --azul: #003865;
            --verde: #6C9A1F;
            --gris: #6b7280;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background: #f2f2f2;
            color: #1f2937;
        }

        .page-container {
            width: 100%;
            padding: 35px 20px 50px;
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
            width: 100%;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            padding: 35px;
            width: 100%;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--azul);
            text-decoration: none;
            font-weight: 700;
            transition: .2s;
        }

        .back:hover {
            color: var(--verde);
        }

        h1 {
            margin: 0;
            color: var(--azul);
            font-size: 28px;
            font-weight: 800;
        }

        .sub {
            margin-top: 10px;
            margin-bottom: 30px;
            color: var(--gris);
        }

        /* SEARCH */

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .search-box input {
            flex: 1;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #d8dee6;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            outline: none;
            transition: .2s;
        }

        .search-box input:focus {
            border-color: var(--verde);
            box-shadow: 0 0 0 3px rgba(108, 154, 31, .10);
        }

        .btn {
            background: var(--verde);
            color: #fff;
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--azul);
        }

        /* CATEGORIES */

        .categories {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .cat {
            background: #eef2f7;
            color: var(--azul);
            padding: 10px 16px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: .2s;
        }

        .cat:hover {
            background: var(--verde);
            color: #fff;
        }

        /* RESULTS */

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .material-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            transition: .2s;
        }

        .material-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .06);
        }

        .material-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
            background: #f3f4f6;
            display: block;
        }

        .material-title {
            color: var(--azul);
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .material-info {
            color: #4b5563;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            margin-top: 10px;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            margin-top: 40px;
            padding: 25px;
        }

        @media (max-width: 700px) {

            .page-container {
                padding: 20px 15px 40px;
            }

            .wrapper {
                padding-top: 10px;
            }

            .card {
                padding: 25px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .search-box {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }

            .logo-sigit {
                width: 260px;
            }
        }
    </style>
</head>

<body>

    <div class="page-container">

        <img
            class="logo-sigit"
            src="{{ asset('img/LOGO_BIS_UNIPOLI.png') }}"
            alt="Universidad Politécnica de Durango"
        >

        <div class="wrapper">

            <div class="card">

                <a href="{{ url('/') }}" class="back">
                    ← Regresar al inicio
                </a>

                <h1>Consultar material</h1>

                <p class="sub">
                    Busca material o equipo disponible para préstamo.
                </p>

                <!-- SEARCH -->

                <div class="search-box">

                    <input
                        type="text"
                        id="search"
                        placeholder="Buscar Arduino, cautín, sensor..."
                        onkeydown="if(event.key === 'Enter') buscarMaterial()"
                    >

                    <button
                        type="button"
                        class="btn"
                        onclick="buscarMaterial()"
                    >
                        Buscar
                    </button>

                </div>

                <!-- CATEGORIES -->

                <div class="categories">

                    <div
                        class="cat"
                        onclick="buscarCategoria('Arduino')"
                    >
                        Arduino
                    </div>

                    <div
                        class="cat"
                        onclick="buscarCategoria('Electrónica')"
                    >
                        Electrónica
                    </div>

                    <div
                        class="cat"
                        onclick="buscarCategoria('Redes')"
                    >
                        Redes
                    </div>

                    <div
                        class="cat"
                        onclick="buscarCategoria('Gadgets')"
                    >
                        Gadgets
                    </div>

                    <div
                        class="cat"
                        onclick="buscarCategoria('Perifericos')"
                    >
                        Periféricos
                    </div>

                    <div
                        class="cat"
                        onclick="buscarCategoria('Herramienta')"
                    >
                        Herramienta
                    </div>

                </div>

                <!-- RESULTS -->

                <div
                    id="results"
                    class="results-grid"
                ></div>

                <div
                    id="empty"
                    class="empty"
                >
                    Busca un material para comenzar.
                </div>

            </div>

        </div>

    </div>

    <script>

        async function buscarMaterial() {

            const search = document
                .getElementById('search')
                .value
                .trim();

            if (!search) {

                Swal.fire(
                    'Campo vacío',
                    'Escribe un material para buscar',
                    'warning'
                );

                return;
            }

            consultar(search);
        }


        function buscarCategoria(categoria) {

            document.getElementById('search').value = categoria;

            consultar(categoria);
        }


        async function consultar(search) {

            Swal.fire({
                title: 'Buscando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {

                const response = await fetch(
                    `{{ url('material/buscar') }}?search=${encodeURIComponent(search)}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();

                Swal.close();

                const container = document.getElementById('results');
                const empty = document.getElementById('empty');

                container.innerHTML = '';

                if (!response.ok || !Array.isArray(data) || data.length === 0) {

                    empty.style.display = 'block';

                    empty.innerHTML = `
                        No se encontró material disponible.
                    `;

                    return;
                }

                empty.style.display = 'none';


                data.forEach(item => {

                    let color = '#6C9A1F';
                    let estado = 'Disponible';

                    if (item.quantity <= 0) {

                        color = '#DC2626';
                        estado = 'No disponible';

                    } else if (item.quantity <= 2) {

                        color = '#D97706';
                        estado = 'Pocas unidades';

                    }


                    const image = item.image
                        ? item.image
                        : '/img/placeholder-material.png';


                    container.innerHTML += `

                        <div class="material-card">

                            <img
                                src="${image}"
                                class="material-image"
                                alt="${item.product ?? 'Material'}"
                            >

                            <div class="material-title">
                                ${item.product ?? 'Material'}
                            </div>

                            <div class="material-info">
                                <b>Categoría:</b>
                                ${item.category ?? 'General'}
                            </div>

                            <div class="material-info">
                                <b>Marca:</b>
                                ${item.brand ?? 'General'}
                            </div>

                            <div class="material-info">
                                <b>Modelo:</b>
                                ${item.model ?? 'General'}
                            </div>

                            <div class="material-info">
                                <b>Área:</b>
                                ${item.area ?? 'N/A'}
                            </div>

                            <div class="material-info">
                                <b>Disponibles:</b>
                                ${item.quantity ?? 0}
                            </div>

                            <span
                                class="badge"
                                style="background:${color}"
                            >
                                ${estado}
                            </span>

                        </div>

                    `;
                });

            } catch (e) {

                Swal.close();

                Swal.fire(
                    'Error',
                    'No se pudo consultar el material',
                    'error'
                );
            }
        }

    </script>

</body>

</html>