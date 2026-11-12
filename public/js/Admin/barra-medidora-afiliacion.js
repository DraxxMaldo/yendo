
document.addEventListener('DOMContentLoaded', function () {
    const inputsVal = document.querySelectorAll('.perfil-input-val');
    const barraProgreso = document.getElementById('barraProgresoPerfil');
    const porcentajeTexto = document.getElementById('porcentajeTexto');
    const btnEnviarSolicitudFinal = document.getElementById('btnEnviarSolicitudFinal');

    function calcularProgresoPerfil() {
        let totalCampos = inputsVal.length;
        let completados = 0;

        inputsVal.forEach(input => {
            if (input.value.trim() !== '') {
                completados++;
            }
        });

        let porcentaje = Math.round((completados / totalCampos) * 100);
        if (barraProgreso) {
            barraProgreso.style.width = porcentaje + '%';
            barraProgreso.setAttribute('aria-valuenow', porcentaje);
        }
        if (porcentajeTexto) {
            porcentajeTexto.textContent = porcentaje + '%';
        }

        // Habilita el botón solo cuando se completa el 100% de los campos del modal
        if (btnEnviarSolicitudFinal) {
            if (porcentaje === 100) {
                btnEnviarSolicitudFinal.disabled = false;
            } else {
                btnEnviarSolicitudFinal.disabled = true;
            }
        }
    }

    // Ejecutar al abrir el modal o al escribir en los campos
    $('#modalAfiliacionSPA').on('shown.bs.modal', calcularProgresoPerfil);
    inputsVal.forEach(input => {
        input.addEventListener('input', calcularProgresoPerfil);
    });
});
