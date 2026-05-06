@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Asignar equipo o material')
@section('tab_title', 'Asignar equipo o material | ' . config('app.name'))
@section('description', 'Asignar equipo o material.')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Asignar equipo o material
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/lista-equipo-asignado/') }}">Ver todos los usuarios con equipo asignado</a>
        </p>
        
        <project-registration-form 
            action="{{ url('admin/asignar-equipo/crear') }}"
            :products="4"
            :min-products="1"
            :users="{{ $users }}"
            :products-data="{{ $equipments }}"
            :assigned-products="[]"
            user-data=""
        >
        </project-registration-form>
            
            
    </div>
</section>

@endsection
