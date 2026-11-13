document.addEventListener('DOMContentLoaded', function () {
    const roleCards = document.querySelectorAll('.role-card');
    const inputRol = document.getElementById('inputRolSeleccionado');
    const btnSiguiente = document.getElementById('btnSiguientePaso');
    const pasoRol = document.getElementById('paso-rol');
    const pasoDatos = document.getElementById('paso-datos');
    const btnAnterior = document.getElementById('btnAnteriorPaso');

    if (!roleCards.length || !inputRol) return;

    // Si ya hay un rol seleccionado previamente, mantenerlo activo
    if (inputRol.value) {
        roleCards.forEach(c => {
            if (c.getAttribute('data-role') === inputRol.value) {
                c.style.backgroundColor = '#e9ecef';
                btnSiguiente.disabled = false;
            }
        });
    }

    // Selección interactiva de tarjetas de rol
    roleCards.forEach(card => {
        card.addEventListener('click', function () {
            roleCards.forEach(c => c.style.backgroundColor = '#fff');
            this.style.backgroundColor = '#e9ecef';
            inputRol.value = this.getAttribute('data-role');
            btnSiguiente.disabled = false;
        });
    });

    // Avanzar al paso 2
    btnSiguiente.addEventListener('click', function () {
        if (!inputRol.value) return;
        pasoRol.style.display = 'none';
        pasoDatos.style.display = 'block';
    });

    // Volver al paso 1
    btnAnterior.addEventListener('click', function () {
        pasoDatos.style.display = 'none';
        pasoRol.style.display = 'block';
    });
});

// Función global para manejar la apertura del modal y la transición de pasos ante errores de validación de Laravel
function inicializarModalErrores(tieneErrores, errorDatos) {
    if (tieneErrores) {
        $(document).ready(function() {
            $('#modalSpaPersonal').modal('show');
            if (errorDatos) {
                const pasoRol = document.getElementById('paso-rol');
                const pasoDatos = document.getElementById('paso-datos');
                if (pasoRol && pasoDatos) {
                    pasoRol.style.display = 'none';
                    pasoDatos.style.display = 'block';
                }
            }
        });
    }
}


