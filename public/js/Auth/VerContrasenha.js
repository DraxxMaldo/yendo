document.addEventListener('DOMContentLoaded', function() {
    const btnToggleClave = document.getElementById('btnToggleClave');
    const inputClave = document.getElementById('clave');
    const iconoClave = document.getElementById('iconoClave');

    if (btnToggleClave) {
        btnToggleClave.addEventListener('click', function() {
            // Alternar el tipo de input
            if (inputClave.type === 'password') {
                inputClave.type = 'text';
                // Cambiar el ícono a ojo tachado
                iconoClave.classList.remove('fa-eye');
                iconoClave.classList.add('fa-eye-slash');
            } else {
                inputClave.type = 'password';
                // Volver al ícono de ojo normal
                iconoClave.classList.remove('fa-eye-slash');
                iconoClave.classList.add('fa-eye');
            }
        });
    }
});
