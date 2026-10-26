 //Objetivo: Mostrar de manera interactiva cuando se seleccione un departamento, se muestre un municipio de ese departamento

 document.addEventListener('DOMContentLoaded', function() {
     const selectDepto = document.getElementById('filtro_departamento');
     const selectMuni = document.getElementById('select_municipio');
     const selectDist = document.getElementById('select_distrito');

     if (selectDepto && selectMuni && selectDist) {
         // Guardamos copias en memoria de todas las opciones
         const opcionesMuni = Array.from(selectMuni.querySelectorAll('option[data-departamento]')).map(opt => opt.cloneNode(true));
         const opcionesDist = Array.from(selectDist.querySelectorAll('option[data-municipio]')).map(opt => opt.cloneNode(true));

         // Evento 1: Al cambiar Departamento
         selectDepto.addEventListener('change', function() {
             const idDeptoSeleccionado = this.value;

             // Habilitar y limpiar municipios
             selectMuni.disabled = false;
             selectMuni.innerHTML = '<option value="" disabled selected>Seleccione un municipio...</option>';

             // Reiniciar y deshabilitar distritos (porque cambió el departamento)
             selectDist.disabled = true;
             selectDist.innerHTML = '<option value="" disabled selected>Primero seleccione un municipio...</option>';

             opcionesMuni.forEach(opcion => {
                 if (opcion.getAttribute('data-departamento') === idDeptoSeleccionado) {
                     selectMuni.appendChild(opcion.cloneNode(true));
                 }
             });
         });

         // Evento 2: Al cambiar Municipio
         selectMuni.addEventListener('change', function() {
             const idMuniSeleccionado = this.value;

             // Habilitar y limpiar distritos
             selectDist.disabled = false;
             selectDist.innerHTML = '<option value="" disabled selected>Seleccione un distrito...</option>';

             opcionesDist.forEach(opcion => {
                 if (opcion.getAttribute('data-municipio') === idMuniSeleccionado) {
                     selectDist.appendChild(opcion.cloneNode(true));
                 }
             });
         });
     }
 });
