@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Agregar Vale de Material')
@section('tab_title', 'Agregar Vale de Material | ' . config('app.name'))
@section('description', 'Agregar Vale de Material.')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Agregar Vale de Material
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/vales-equipo/') }}">Ver todos los vales</a>
            </p>

            <ticket-registration-form 
                action="{{ url('admin/vales-equipo/crear') }}"
                :products="8"
                :min-products="1"
                :products-data="{{ $equipments }}"
                :assigned-products="[]"
                ticket=""
            >
            </ticket-registration-form>
        </div>
    </section>

@endsection
