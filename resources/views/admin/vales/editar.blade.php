@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar Vale de Material')
@section('tab_title', 'Editar Vale de Material | ' . config('app.name'))
@section('description', 'Editar Vale de Material.')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Editar Vale de Material
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/vales-equipo/') }}">Ver todo los vales</a>
            </p>

            <ticket-registration-form 
                action="{{ url('admin/vales-equipo/crear') }}"
                :products="8"
                :min-products="1"
                :products-data="{{ $equipments }}"
                :assigned-products="{{ $assigned_products }}"
                :ticket="{{ $voucher }}"
            >
            </ticket-registration-form>
            

        </div>
    </section>

@endsection
