{{-- resources/views/layouts/toast.blade.php --}}

<div class="toast-container position-fixed p-3" style="bottom: 0; right: 0; z-index: 1056;">

    @foreach (['success', 'error', 'warning', 'info'] as $msgType)
        @if(session($msgType))
            @php
                $iconClass = '';
                $progressClass = '';
                $title = '';

                // Asignamos colores semánticos solo al ícono y a la barra
                switch($msgType) {
                    case 'success':
                        $iconClass = 'fas fa-check-circle text-success';
                        $progressClass = 'bg-success';
                        $title = 'Éxito';
                        break;
                    case 'error':
                        $iconClass = 'fas fa-times-circle text-danger';
                        $progressClass = 'bg-danger';
                        $title = 'Error de validación';
                        break;
                    case 'warning':
                        $iconClass = 'fas fa-exclamation-triangle text-warning';
                        $progressClass = 'bg-warning';
                        $title = 'Advertencia';
                        break;
                    case 'info':
                        $iconClass = 'fas fa-info-circle text-info';
                        $progressClass = 'bg-info';
                        $title = 'Información';
                        break;
                }
            @endphp

            {{-- Toast rediseñado con fondo Midnight Indigo y bordes redondeados --}}
            <div class="toast custom-toast shadow-lg mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream); border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <div class="toast-header border-0" style="background-color: rgba(255,255,255,0.03); color: var(--vanilla-cream);">
                    <i class="{{ $iconClass }} mr-2" style="font-size: 1.1rem;"></i>
                    <strong class="mr-auto">{{ $title }}</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Cerrar" style="color: var(--vanilla-cream); text-shadow: none; opacity: 0.7; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body" style="font-size: 0.95rem; padding: 12px 15px;">
                    {{ session($msgType) }}
                </div>
                {{-- Contenedor de la barra de progreso --}}
                <div style="height: 4px; background-color: rgba(255,255,255,0.05); width: 100%;">
                    <div class="toast-progress {{ $progressClass }}" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Toasts generados por errores de validación del Request --}}
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="toast custom-toast shadow-lg mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="background-color: var(--midnight-indigo); color: var(--vanilla-cream); border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <div class="toast-header border-0" style="background-color: rgba(255,255,255,0.03); color: var(--vanilla-cream);">
                    <i class="fas fa-times-circle text-danger mr-2" style="font-size: 1.1rem;"></i>
                    <strong class="mr-auto">Error de validación</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Cerrar" style="color: var(--vanilla-cream); text-shadow: none; opacity: 0.7; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body" style="font-size: 0.95rem; padding: 12px 15px;">
                    {{ $error }}
                </div>
                {{-- Contenedor de la barra de progreso (Rojo por ser error) --}}
                <div style="height: 4px; background-color: rgba(255,255,255,0.05); width: 100%;">
                    <div class="toast-progress bg-danger" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        @endforeach
    @endif
</div>
