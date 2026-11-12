// public/js/Perfil/perfil-seguridad.js
document.addEventListener('DOMContentLoaded', function () {
    // Lógica del contador de 60 segundos exclusivo para el paso 2
    const paso2 = document.getElementById('paso-2');

    if (paso2 && paso2.style.display !== 'none') {
        let timeLeft = 60;
        const contadorEl = document.getElementById('contador');
        const btnReenviar = document.getElementById('btnReenviar');
        const formReenviar = document.getElementById('formReenviar');

        if (btnReenviar) {
            btnReenviar.disabled = true;
            btnReenviar.classList.remove('text-primary');
            btnReenviar.classList.add('text-muted');

            const timerInterval = setInterval(() => {
                timeLeft--;
                if (contadorEl) contadorEl.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    btnReenviar.disabled = false;
                    btnReenviar.classList.remove('text-muted');
                    btnReenviar.classList.add('text-primary');
                    btnReenviar.textContent = "¿No llegó? Reenviar código";
                }
            }, 1000);
        }
    }

    // Funcionalidad del "ojito" para ver/ocultar contraseñas
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
