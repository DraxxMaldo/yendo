@extends('layouts.layout')

@section('titulo', 'Nuestros Destinos | Yendo')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush

@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card border-0 shadow-lg p-4" style="background-color: var(--vanilla-cream); border: 2px solid var(--midnight-indigo) !important; border-radius: 8px;">

                    <div class="text-center mb-4">
                        <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--midnight-indigo);"></i>
                        <h2 class="fw-bold" style="color: var(--midnight-indigo);">Destinos y Establecimientos Yendo</h2>
                        <p class="text-muted">Consulta nuestra red de sucursales y puntos de entrega oficiales disponibles para tus encomiendas.</p>
                    </div>

                    <div class="row">
                        @forelse($destinos as $destino)
                            @php
                                // Validamos si el establecimiento tiene imagen guardada en storage
                                $urlImagenDestino = $destino->imagen && Storage::disk('public')->exists($destino->imagen)
                                    ? asset('storage/' . $destino->imagen)
                                    : 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80'; // Imagen por defecto de logística/sucursal
                            @endphp

                            <div class="col-md-4 mb-4">
                                <div class="card h-100 bg-white border-dark shadow-sm" style="border: 2px solid var(--midnight-indigo); border-radius: 6px; overflow: hidden;">

                                    {{-- Imagen del establecimiento --}}
                                    <img src="{{ $urlImagenDestino }}" alt="{{ $destino->nombre }}" class="card-img-top" style="height: 180px; object-fit: cover; border-bottom: 2px solid var(--midnight-indigo);">

                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="fw-bold text-dark mb-2">
                                            <i class="fas fa-building mr-1" style="color: var(--midnight-indigo);"></i> {{ $destino->nombre }}
                                        </h5>

                                        <p class="small text-muted mb-3 flex-grow-1">
                                            <i class="fas fa-map-marker-alt mr-1 text-danger"></i> <strong>Dirección:</strong> {{ $destino->direccion_exacta ?? 'No especificada' }}
                                        </p>

                                        <div class="mt-auto pt-2 border-top">
                                            <span class="badge badge-pill badge-primary px-3 py-2" style="background-color: var(--midnight-indigo); font-size: 0.75rem;">
                                                <i class="fas fa-check-circle mr-1"></i> Sucursal Activa
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted fw-bold">No hay establecimientos ni destinos registrados en este momento.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn fw-bold rounded-pill px-4 py-2 shadow-sm" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream);">
                            <i class="fas fa-arrow-left mr-2"></i> Volver al Inicio
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
