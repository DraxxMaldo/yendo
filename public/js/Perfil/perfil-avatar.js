// public/js/Perfil/perfil-avatar.js

document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatar-input-file');
    const imageToCrop = document.getElementById('imageToCrop');
    const modalEl     = document.getElementById('modalCropper');
    const btnGuardar  = document.getElementById('btnRecortarYGuardar');
    const btnCancelar = document.getElementById('btnCancelarRecorte');
    const btnCerrar   = document.getElementById('btnCerrarCropper');
    const formFoto    = document.getElementById('formFotoPerfil');

    let cropper = null;

    if (avatarInput && modalEl && formFoto) {
        // 1. Al seleccionar un archivo localmente
        avatarInput.addEventListener('change', function (e) {
            const archivo = this.files && this.files[0];
            if (!archivo || !archivo.type.startsWith('image/')) return;

            // Validación de tamaño en cliente (Máx 2MB)
            if (archivo.size > 2 * 1024 * 1024) {
                alert('La imagen excede el límite de 2MB.');
                avatarInput.value = '';
                return;
            }

            const lector = new FileReader();
            lector.onload = function (event) {
                imageToCrop.src = event.target.result;
                $('#modalCropper').modal('show');
            };
            lector.readAsDataURL(archivo);
        });

        // 2. Inicializar Cropper al abrir el modal
        $(modalEl).on('shown.bs.modal', function () {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1, // Cuadrado perfecto (1:1)
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }).on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            imageToCrop.src = '';
            // Si cancela, limpiamos el input para permitir elegir la misma foto si lo desea
            if (!formFoto.dataset.subiendo) {
                avatarInput.value = '';
            }
        });

        const limpiarModal = () => { $('#modalCropper').modal('hide'); };
        if (btnCancelar) btnCancelar.addEventListener('click', limpiarModal);
        if (btnCerrar) btnCerrar.addEventListener('click', limpiarModal);

        // 3. Al hacer clic en "Recortar y Guardar" -> Hacemos el envío tradicional
        if (btnGuardar) {
            btnGuardar.addEventListener('click', function () {
                if (!cropper) return;

                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...';
                formFoto.dataset.subiendo = 'true';

                // Obtenemos el lienzo recortado en formato Blob
                cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                }).toBlob(function (blob) {
                    // Creamos un objeto File a partir del Blob recortado
                    const archivoRecortado = new File([blob], 'avatar-recortado.jpg', { type: 'image/jpeg' });

                    // Usamos DataTransfer para inyectar el archivo recortado de forma segura en el input file original
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(archivoRecortado);
                    avatarInput.files = dataTransfer.files;

                    // Cerramos el modal
                    $('#modalCropper').modal('hide');

                    // ENVIÓ TRADICIONAL: El navegador recarga la página ejecutando el ciclo estándar de Laravel
                    formFoto.submit();
                }, 'image/jpeg', 0.9);
            });
        }
    }
});
