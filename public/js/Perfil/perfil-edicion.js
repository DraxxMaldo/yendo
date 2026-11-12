
document.addEventListener('DOMContentLoaded', function () {
    const btnEditar   = document.getElementById('perfil-btn-habilitar-edicion');
    const btnGuardar  = document.getElementById('perfil-btn-guardar');
    const btnCancelar = document.getElementById('perfil-btn-cancelar');
    const modoBadge   = document.getElementById('perfil-modo-badge');
    const form        = document.getElementById('perfil-form-informacion');
    const inputs      = document.querySelectorAll('.perfil-input:not(#perfil-input-correo)');

    const valoresOriginales = {};
    inputs.forEach(input => { valoresOriginales[input.id] = input.value; });

    function setModo(editando) {
        inputs.forEach(input => {
            input.readOnly = !editando;
            input.classList.toggle('perfil-input-editable', editando);
        });
        if (btnEditar) btnEditar.hidden = editando;
        if (btnGuardar) btnGuardar.hidden = !editando;
        if (btnCancelar) btnCancelar.hidden = !editando;

        if (modoBadge) {
            modoBadge.dataset.mode = editando ? 'edit' : 'read';
            modoBadge.textContent  = editando ? 'Modo Edición' : 'Modo Lectura';
        }
    }

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {
            setModo(true);
            const inputNombres = document.getElementById('perfil-input-nombres');
            if (inputNombres) inputNombres.focus();
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            // Restauramos los valores originales al cancelar
            inputs.forEach(input => { input.value = valoresOriginales[input.id]; });
            setModo(false);
            if (btnEditar) btnEditar.focus();
        });
    }

    // Efecto visual al enviar el formulario (el navegador hará el submit real automáticamente)
    if (form) {
        form.addEventListener('submit', function () {
            if (btnGuardar && !btnGuardar.hidden) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin perfil-icono" aria-hidden="true"></i> Guardando...';
            }
        });
    }

    // Si la página recargó con errores de validación de Laravel, abrimos el modo edición automáticamente
    const hayErrores = document.querySelector('.toast-container .bg-danger');
    if (hayErrores) {
        setModo(true);
    }
});
