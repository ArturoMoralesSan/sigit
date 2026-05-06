@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Agregar usuario')
@section('tab_title', 'Agregar usuario | ' . config('app.name'))
@section('description', 'Agregar usuario.')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Crear Usuario
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/usuarios/') }}">Ver todos los usuarios</a>
        </p>

            <user-form action="{{ url('admin/usuarios/crear') }}"
                enctype="multipart/form-data"
                inline-template
                v-cloak
            >
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Datos de usuario
                        </h3>

                        <div class="md:row">
                            <div class="md:col-2/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="full_name">Nombre completo</label>
                                    <text-field name="full_name" v-model="fields.full_name" maxlength="80" initial=""></text-field>
                                    <field-errors name="full_name"></field-errors>

                                </div>
                                {{-- apellidos --}}
                                <div class="form-control">
                                    <label for="email">Correo electrónico</label>
                                    <text-field type="email" name="email" v-model="fields.email" maxlength="100" initial=""></text-field>
                                    <field-errors name="email"></field-errors>
                                </div>
                                {{-- password --}}
                                <div class="form-control">
                                    <label for="password">Contraseña temporal</label>
                                    <text-field name="password" v-model="fields.password" maxlength="100" initial=""></text-field>
                                    <field-errors name="password"></field-errors>

                                </div>


                            </div>

                        </div>
                    </section>

                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Roles
                        </h3>
                        <div class="md:row">
                            <div class="md:col-2/3">
                                @foreach($roles as $role)
                                    <label>
                                        <input id="roles" type="radio" name="roles" v-model="fields.roles" value="{{ $role->id }}">
                                        {{ $role->name}}
                                    </label><br>
                                @endforeach
                            </div>
                        </div>
                    </section>
                    <div class="text-center">
                        <form-button class="btn--dashboard btn--wide">
                            Crear
                        </form-button>
                    </div>
                </form>
            </user-form>
    </div>
</section>

@endsection
