<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-4" style="z-index: 1056;" data-bs-theme="light">

    @foreach (['success', 'error', 'warning', 'info'] as $msgType)
        @if(session($msgType))
            @php
                $headerClass = '';
                $iconClass = '';
                $title = '';
                $btnCloseClass = 'btn-close-white';

                switch($msgType) {
                    case 'success':
                        $headerClass = 'toast-success';
                        $iconClass = 'fa-solid fa-circle-check';
                        $title = 'Éxito';
                        break;
                    case 'error':
                        $headerClass = 'toast-error';
                        $iconClass = 'fa-solid fa-circle-xmark';
                        $title = 'Error';
                        break;
                    case 'warning':
                        $headerClass = 'toast-warning';
                        $iconClass = 'fa-solid fa-triangle-exclamation';
                        $title = 'Advertencia';
                        break;
                    case 'info':
                        $headerClass = 'toast-info';
                        $iconClass = 'fa-solid fa-circle-info';
                        $title = 'Información';
                        break;
                }
            @endphp

            <div class="toast {{ $headerClass }} shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="width: 500px;">
                <div class="toast-header">
                    <i class="{{ $iconClass }} fs-5 me-2"></i>
                    <strong class="me-auto">{{ $title }}</strong>
                    <small>Justo ahora</small>
                    <button type="button" class="btn-close {{ $btnCloseClass }}" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body text-dark">
                    {{ session($msgType) }}
                </div>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="toast toast-danger shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="width: 500px;">
                <div class="toast-header">
                    <i class="fa-solid fa-circle-xmark fs-5 me-2"></i>
                    <strong class="me-auto">Error de validación</strong>
                    <small>Justo ahora</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body text-dark">
                    {{ $error }}
                </div>
            </div>
        @endforeach
    @endif
</div>
