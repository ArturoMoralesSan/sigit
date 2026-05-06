@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar usuario')
@section('tab_title', 'Editar usuario | ' . config('app.name'))
@section('description', 'Editar usuario.')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Editar Usuario
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/usuarios/') }}">Ver todos los usuarios</a>
        </p>

            <user-form action="{{ url('admin/usuarios/'. $user->id .'/actualizar') }}"
                method="put"
                enctype="multipart/form-data"
                :initial = "{
                    roles : {{ $userRole }},
                    is_active: {{ $user->is_active }}
                }"
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


                                <div class="form-control">
                                    <label for="full_name">Nombre</label>
                                    <text-field name="full_name" v-model="fields.full_name" maxlength="100" initial="{{ $user->full_name }}"></text-field>
                                    <field-errors name="full_name"></field-errors>

                                </div>
                                {{-- apellidos --}}
                                <div class="form-control">
                                    <label for="email">Correo electrónico</label>
                                    <text-field type="email" name="email" v-model="fields.email" maxlength="100" initial="{{ $user->email }}"></text-field>
                                    <field-errors name="email"></field-errors>
                                </div>
                                {{-- password --}}
                                <div class="form-control">
                                    <label for="password">Contraseña temporal</label>
                                    <text-field name="password" v-model="fields.password" maxlength="100" initial=""></text-field>
                                    <field-errors name="password"></field-errors>
                                </div>

                                <div class="form-control">
                                    <label>
                                        <input id="is_active" type="checkbox" name="is_active" v-model="fields.is_active" value="{{ $user->is_active }}">
                                        Activo
                                    </label><br>

                                    <field-errors :name="'is_active'"></field-errors>
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
                            Actualizar
                        </form-button>
                    </div>
                </form>
            </user-form>
    </div>
</section>

@endsection
