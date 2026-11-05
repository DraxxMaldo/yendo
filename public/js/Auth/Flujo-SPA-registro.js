// public/js/Auth/Flujo-SPA-registro.js

const csrfToken = document.querySelector('input[name="_token"]').value;

// ==========================================
// FUNCIÓN EXCLUSIVA PARA LA SPA (No afecta tu toast.js global)
// ==========================================
function notificarSPA(tipo, mensaje) {
    const container = document.querySelector('.toast-container');
    if (!container) return; // Si no está el contenedor, no hace nada

    let iconClass = '', progressClass = '', title = '';
    switch(tipo) {
        case 'success':
            iconClass = 'fas fa-check-circle text-success'; progressClass = 'bg-success'; title = 'Éxito'; break;
        case 'error':
            iconClass = 'fas fa-times-circle text-danger'; progressClass = 'bg-danger'; title = 'Error de validación'; break;
        case 'warning':
            iconClass = 'fas fa-exclamation-triangle text-warning'; progressClass = 'bg-warning'; title = 'Advertencia'; break;
        default:
            iconClass = 'fas fa-info-circle text-info'; progressClass = 'bg-info'; title = 'Información'; break;
    }

    const toastHTML = `
        <div class="toast custom-toast shadow-lg mb-3" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: var(--midnight-indigo, #282B4A); color: var(--vanilla-cream, #EEEBDA); border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
            <div class="toast-header border-0" style="background-color: rgba(255,255,255,0.03); color: var(--vanilla-cream, #EEEBDA);">
                <i class="${iconClass} mr-2" style="font-size: 1.1rem;"></i>
                <strong class="me-auto mr-auto">${title}</strong>
                <button type="button" class="ml-2 mb-1 close btn-close" data-bs-dismiss="toast" data-dismiss="toast" aria-label="Cerrar" style="color: inherit; text-shadow: none; opacity: 0.7; outline: none; background: transparent; border: none; font-size: 1.2rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body" style="font-size: 0.95rem; padding: 12px 15px;">
                ${mensaje}
            </div>
            <div style="height: 4px; background-color: rgba(255,255,255,0.05); width: 100%;">
                <div class="toast-progress ${progressClass}" style="height: 100%; width: 100%; transition: width 4.8s linear;"></div>
            </div>
        </div>
    `;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = toastHTML.trim();
    const toastEl = wrapper.firstChild;
    container.appendChild(toastEl);

    // Inicializar Bootstrap 5 o Bootstrap 4
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    } else if (typeof $ !== 'undefined') {
        $(toastEl).toast({ delay: 5000 }).toast('show');
    }

    // Animar barra
    const progressBar = toastEl.querySelector('.toast-progress');
    setTimeout(() => { if (progressBar) progressBar.style.width = '0%'; }, 50);

    // Limpiar DOM al cerrar
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    if (typeof $ !== 'undefined') {
        $(toastEl).on('hidden.bs.toast', function () { $(this).remove(); });
    }
}

// ==========================================
// PASO 1: ENVIAR CORREO (AJAX CON VALIDACIÓN)
// ==========================================
async function irAPaso2() {
    const correoInput = document.getElementById('correo_registro');
    const correo = correoInput.value.trim();
    const btn = document.getElementById('btn-continuar-registro');

    if (!correo) {
        notificarSPA('error', 'El campo de correo electrónico es obligatorio.');
        correoInput.focus();
        return;
    }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    btn.disabled = true;

    try {
        let response = await fetch(window.rutasLaravel.enviarCodigo, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ correo: correo })
        });

        if (response.status === 422) {
            let dataError = await response.json();
            if (dataError.errors && dataError.errors.correo) {
                notificarSPA('error', dataError.errors.correo[0]);
            } else {
                notificarSPA('error', dataError.message || 'Error de validación.');
            }
            correoInput.focus();
            return;
        }

        let data = await response.json();

        if (response.ok && data.success) {
            document.getElementById('correo_final').value = correo;
            document.getElementById('paso-1').style.display = 'none';
            document.getElementById('paso-2').style.display = 'block';
            document.querySelector('.codigo-input').focus();

            notificarSPA('success', data.mensaje);
            iniciarTemporizador();
        } else {
            notificarSPA('error', data.mensaje || 'Ocurrió un error inesperado.');
        }

    } catch (error) {
        console.error("Error en Fetch:", error);
        notificarSPA('error', 'Ocurrió un error de conexión al comunicarse con el servidor.');
    } finally {
        if (document.getElementById('paso-1').style.display !== 'none') {
            btn.innerHTML = 'Continuar';
            btn.disabled = false;
        }
    }
}

// ==========================================
// TEMPORIZADOR Y REENVÍO
// ==========================================
let temporizador;
let tiempoRestante = 60;

function iniciarTemporizador() {
    tiempoRestante = 60;
    const btnReenviar = document.getElementById('btn-reenviar-codigo');
    const timerText = document.getElementById('timer-text');

    btnReenviar.style.pointerEvents = 'none';
    btnReenviar.style.color = '#929292';

    clearInterval(temporizador);
    temporizador = setInterval(() => {
        tiempoRestante--;
        timerText.innerText = `(${tiempoRestante}s)`;

        if (tiempoRestante <= 0) {
            clearInterval(temporizador);
            timerText.innerText = '';
            btnReenviar.style.pointerEvents = 'auto';
            btnReenviar.style.color = 'white';
        }
    }, 1000);
}

document.getElementById('btn-reenviar-codigo').addEventListener('click', function(e) {
    e.preventDefault();
    if (tiempoRestante <= 0) {
        document.querySelectorAll('.codigo-input').forEach(i => i.value = '');
        document.querySelector('.codigo-input').focus();
        irAPaso2();
    }
});

// ==========================================
// LÓGICA DE LAS CAJITAS (Auto-salto y Validación)
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.codigo-input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            // Se corrigió el error de sintaxis en esta línea
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            verificarCodigoCompleto();
        });

        input.addEventListener('keydown', function(e) {
            // Se corrigió el error de sintaxis en esta línea
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
});

async function verificarCodigoCompleto() {
    const inputs = document.querySelectorAll('.codigo-input');
    let codigo = '';
    inputs.forEach(input => codigo += input.value);

    if (codigo.length === 6) {
        const correo = document.getElementById('correo_final').value;
        inputs.forEach(i => i.disabled = true);

        try {
            let response = await fetch(window.rutasLaravel.verificarCodigo, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ correo: correo, codigo: codigo })
            });

            if (response.status === 422) {
                let dataError = await response.json();
                notificarSPA('error', dataError.message || 'Datos inválidos.');
                inputs.forEach(i => { i.disabled = false; i.value = ''; });
                inputs[0].focus();
                return;
            }

            let data = await response.json();

            if (data.success) {
                clearInterval(temporizador);
                document.getElementById('paso-2').style.display = 'none';
                document.getElementById('paso-3').style.display = 'block';
                notificarSPA('success', '¡Código verificado correctamente!');
            } else {
                notificarSPA('error', data.mensaje);
                inputs.forEach(i => i.value = '');
                inputs[0].focus();
            }
        } catch (error) {
            console.error("Error en Fetch (verificar):", error);
            notificarSPA('error', 'Error de conexión al verificar el código.');
        } finally {
            inputs.forEach(i => i.disabled = false);
        }
    }
}

// ==========================================
// PASO 3: FINALIZAR REGISTRO Y GUARDAR DATOS
// ==========================================
const formPaso3 = document.getElementById('form-paso-3');

if (formPaso3) {
    formPaso3.addEventListener('submit', async function(e) {
        e.preventDefault();

        const btnFinalizar = document.getElementById('btn-finalizar-registro');
        const textoOriginal = btnFinalizar.innerHTML;

        btnFinalizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        btnFinalizar.disabled = true;

        const formData = new FormData(formPaso3);
        const dataObj = Object.fromEntries(formData.entries());

        try {
            let response = await fetch(window.rutasLaravel.finalizar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(dataObj)
            });

            if (response.status === 422) {
                let dataError = await response.json();
                let primerError = Object.values(dataError.errors)[0][0];
                notificarSPA('error', primerError);
                return;
            }

            let data = await response.json();

            // Se agregó la llave "{" que faltaba en el if
            if (response.ok && data.success) {
                notificarSPA('success', data.mensaje);
                // Usar baseUrl para redirigir correctamente sin importar la subcarpeta
                setTimeout(() => {
                    window.location.href = window.rutasLaravel.login;
                }, 2000);
            } else {
                notificarSPA('error', data.mensaje || data.message || 'Ocurrió un error al crear la cuenta.');
            }

        } catch (error) {
            console.error("Error al finalizar registro:", error);
            notificarSPA('error', 'Ocurrió un error de conexión con el servidor.');
        } finally {
            btnFinalizar.innerHTML = textoOriginal;
            btnFinalizar.disabled = false;
        }
    });
}
