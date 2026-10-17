 //Objetivo: Mostrar de manera interactiva cuando se seleccione un departamento, se muestre un municipio de ese departamento


document.addEventListener('DOMContentLoaded', function() {
    const selectDepto = document.getElementById('filtro_departamento');
    const selectMuni = document.getElementById('select_municipio');

    // Verificamos que los elementos existan en la vista actual antes de ejecutar la lógica
    if (selectDepto && selectMuni) {
        // 1. Guardamos una copia en memoria de todas las opciones de municipios
        const todasLasOpciones = Array.from(selectMuni.querySelectorAll('option[data-departamento]'))
            .map(opt => opt.cloneNode(true));

        // 2. Escuchamos cada vez que el usuario cambia el departamento
        selectDepto.addEventListener('change', function() {
            const idDeptoSeleccionado = this.value;

            // Habilitamos el selector de municipios y lo limpiamos
            selectMuni.disabled = false;
            selectMuni.innerHTML = '<option value="" disabled selected>Seleccione un municipio...</option>';

            // 3. Recorremos la memoria y agregamos solo los municipios de ese departamento
            todasLasOpciones.forEach(opcion => {
                if (opcion.getAttribute('data-departamento') === idDeptoSeleccionado) {
                    selectMuni.appendChild(opcion.cloneNode(true));
                }
            });
        });
    }
});
