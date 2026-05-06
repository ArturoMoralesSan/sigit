@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar Equipo o Material')
@section('tab_title', 'Editar Equipo o Material | ' . config('app.name'))
@section('description', 'Editar Equipo o Material.')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Editar Equipo o Material
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/inventario/') }}">Ver todo el equipo o material</a>
            </p>

            <base-form action="{{ url('admin/inventario/' . $equipment->id . '/actualizar') }}" method="put"
                enctype="multipart/form-data" inline-template v-cloak>
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Datos del Equipo o Material
                        </h3>

                        <div class="md:row">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="num_serie">Número de Serie</label>
                                    <text-field name="num_serie" v-model="fields.num_serie"
                                        initial="{{ $equipment->num_serie }}"></text-field>
                                    <field-errors name="num_serie"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="control_tag">Etiqueta de Control</label>
                                    <text-field name="control_tag" v-model="fields.control_tag"
                                        initial="{{ $equipment->control_tag }}"></text-field>
                                    <field-errors name="control_tag"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="product">Equipo o Material</label>
                                    <text-field name="product" v-model="fields.product" initial="{{ $equipment->product }}">
                                    </text-field>
                                    <field-errors name="product"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="area">Área</label>
                                    <text-field name="area" v-model="fields.area" initial="{{ $equipment->area }}">
                                    </text-field>
                                    <field-errors name="area"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="brand">Marca</label>
                                    <text-field name="brand" v-model="fields.brand" initial="{{ $equipment->brand }}">
                                    </text-field>
                                    <field-errors name="brand"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="model">Modelo</label>
                                    <text-field name="model" v-model="fields.model" initial="{{ $equipment->model }}">
                                    </text-field>
                                    <field-errors name="model"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="pu">P.U.</label>
                                    <text-field name="pu" v-model="fields.pu" initial="{{ $equipment->pu }}">
                                    </text-field>
                                    <field-errors name="pu"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="md:row">
                                    <div class="form-control">
                                        <label for="status">Estatus</label>
                                    </div>
                                </div>
                                <div class="md:row">
                                    <div class="form-control" style="width: 100%;">
                                        <select-field class="form-select" name="status" v-model="fields.status"
                                            :options="{ 'disponible': 'Disponible', 'prestamo': 'Préstamo', 'baja': 'Baja',
                                                'no disponible': 'No Disponible' }" initial="{{ $equipment->status }}">
                                        </select-field>
                                        <field-errors name="status"></field-errors>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="md:row">
                            <div class="md:col-1/2 justify-content-center">
                                <div class="row">
                                    <div class="form-control">
                                        <label for="quantity">Cantidad</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-control" style="width: 100%;">
                                        <text-field type="number" min="0" name="quantity" v-model="fields.quantity"
                                            initial="{{ $equipment->quantity }}">
                                        </text-field>
                                        <field-errors name="quantity"></field-errors>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control" style="width: 100%;">
                                    <label for="image">Imagen</label>
                                    <file-field name="image" v-model="fields.image" />
                                    <field-errors name="image"></field-errors>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="text-center">
                        <form-button class="btn--blue--dashboard btn--wide">
                            Actualizar
                        </form-button>
                    </div>
                </form>
                </user-form>
        </div>
    </section>

@endsection
