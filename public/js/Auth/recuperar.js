document.addEventListener('DOMContentLoaded', function() {
    const formRecuperar = document.getElementById('formRecuperar');

    if (formRecuperar) {
        formRecuperar.addEventListener('submit', function(e) {
            const inputCorreo = document.getElementById('correo');

            // Solo animamos si el campo tiene texto
            if (inputCorreo && inputCorreo.value.trim() !== '') {
                const btn = document.getElementById('btnRecuperar');
                const texto = document.getElementById('textoBtn');
                const icono = document.getElementById('iconoBtn');
                const spinner = document.getElementById('spinnerBtn');

                // Prevenir múltiples clics para evitar envíos duplicados
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.8';

                // Cambiar estado visual del botón
                if (texto) texto.innerText = 'Enviando...';
                if (icono) icono.style.display = 'none';
                if (spinner) spinner.style.display = 'inline-block';
            }
        });
    }
});
