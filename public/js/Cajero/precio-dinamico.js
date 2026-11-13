document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('contenedor-tarifas');
    const btnAgregar = document.getElementById('agregar-tarifa');
    const inputValorDeclarado = document.getElementById('valor_declarado_producto');
    const radiosContraentrega = document.querySelectorAll('.radio-contraentrega');
    const totalCobrarVisual = document.getElementById('total-cobrar-visual');
    const detalleCalculo = document.getElementById('detalle-calculo');

    const inputEfectivoRecibido = document.getElementById('efectivo_recibido');
    const vueltoVisual = document.getElementById('vuelto-visual');

    const opcionesHTML = contenedor.querySelector('select').innerHTML;

    function calcularTotales() {
        let sumaTarifas = 0;
        document.querySelectorAll('.select-tarifa').forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.dataset.precio) {
                sumaTarifas += parseFloat(selectedOption.dataset.precio) || 0;
            }
        });

        let valorDeclarado = parseFloat(inputValorDeclarado.value) || 0;
        let esContraentrega = document.getElementById('contraentrega_si').checked;

        let totalFinal = esContraentrega ? (sumaTarifas + valorDeclarado) : sumaTarifas;

        // Actualizar la interfaz visual del total
        totalCobrarVisual.textContent = '$' + totalFinal.toFixed(2);
        if (esContraentrega) {
            detalleCalculo.textContent = `Tarifas ($${sumaTarifas.toFixed(2)}) + Valor Declarado ($${valorDeclarado.toFixed(2)})`;
        } else {
            detalleCalculo.textContent = `Solo Tarifas ($${sumaTarifas.toFixed(2)}) [Valor declarado omitido por no ser contraentrega]`;
        }

        // --- CÁLCULO DE VUELTO / CAMBIO ---
        let efectivoRecibido = parseFloat(inputEfectivoRecibido.value) || 0;
        let vuelto = efectivoRecibido - totalFinal;

        if (efectivoRecibido > 0 && vuelto >= 0) {
            vueltoVisual.textContent = '$' + vuelto.toFixed(2);
            vueltoVisual.className = 'fw-bold text-success h5 mb-0';
        } else if (efectivoRecibido > 0 && vuelto < 0) {
            vueltoVisual.textContent = 'Faltan $' + Math.abs(vuelto).toFixed(2);
            vueltoVisual.className = 'fw-bold text-danger h5 mb-0';
        } else {
            vueltoVisual.textContent = '$0.00';
            vueltoVisual.className = 'fw-bold text-primary h5 mb-0';
        }
    }

    btnAgregar.addEventListener('click', function () {
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'input-group mb-2 tarifa-row';
        nuevaFila.innerHTML = `
            <select name="id_precios[]" class="form-control select-tarifa" required>
                ${opcionesHTML}
            </select>
            <div class="input-group-append">
                <button type="button" class="btn btn-danger eliminar-tarifa">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        contenedor.appendChild(nuevaFila);
        actualizarBotonesEliminar();
        calcularTotales();
    });

    contenedor.addEventListener('click', function (e) {
        if (e.target.closest('.eliminar-tarifa')) {
            e.target.closest('.tarifa-row').remove();
            actualizarBotonesEliminar();
            calcularTotales();
        }
    });

    contenedor.addEventListener('change', function (e) {
        if (e.target.classList.contains('select-tarifa')) {
            calcularTotales();
        }
    });

    inputValorDeclarado.addEventListener('input', calcularTotales);

    if (inputEfectivoRecibido) {
        inputEfectivoRecibido.addEventListener('input', calcularTotales);
    }

    radiosContraentrega.forEach(radio => {
        radio.addEventListener('change', calcularTotales);
    });

    function actualizarBotonesEliminar() {
        const filas = contenedor.querySelectorAll('.tarifa-row');
        filas.forEach((fila) => {
            const btnEliminar = fila.querySelector('.eliminar-tarifa');
            btnEliminar.disabled = (filas.length === 1);
        });
    }

    // Cálculo inicial al cargar
    calcularTotales();
});
