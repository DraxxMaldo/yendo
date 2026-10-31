document.addEventListener('DOMContentLoaded', function() {
    const editSelectDepto = document.getElementById('edit_filtro_departamento');
    const editSelectMuni = document.getElementById('edit_select_municipio');
    const editSelectDist = document.getElementById('edit_select_distrito');
    const formEditar = document.getElementById('formEditarEstablecimiento');
    const previewContainer = document.getElementById('preview_container');
    const previewImage = document.getElementById('preview_image');
    const inputImagen = document.querySelector('input[name="imagen"]'); // El input del archivo

    if (editSelectDepto && editSelectMuni && editSelectDist) {
        // Clonar opciones para poder filtrar sin perder datos
        const opcionesMuniEdit = Array.from(editSelectMuni.querySelectorAll('option[data-departamento]')).map(opt => opt.cloneNode(true));
        const opcionesDistEdit = Array.from(editSelectDist.querySelectorAll('option[data-municipio]')).map(opt => opt.cloneNode(true));

        function filtrarMunicipiosEdit(idDepto) {
            editSelectMuni.innerHTML = '<option value="" disabled selected>Seleccione un municipio...</option>';
            opcionesMuniEdit.forEach(opcion => {
                if (opcion.getAttribute('data-departamento') == idDepto) {
                    editSelectMuni.appendChild(opcion.cloneNode(true));
                }
            });
        }

        function filtrarDistritosEdit(idMuni) {
            editSelectDist.innerHTML = '<option value="" disabled selected>Seleccione un distrito...</option>';
            opcionesDistEdit.forEach(opcion => {
                if (opcion.getAttribute('data-municipio') == idMuni) {
                    editSelectDist.appendChild(opcion.cloneNode(true));
                }
            });
        }

        editSelectDepto.addEventListener('change', function() {
            filtrarMunicipiosEdit(this.value);
            editSelectDist.innerHTML = '<option value="" disabled selected>Primero seleccione un municipio...</option>';
        });

        editSelectMuni.addEventListener('change', function() {
            filtrarDistritosEdit(this.value);
        });

        // Capturar clic en botones editar
        const botonesEditar = document.querySelectorAll('.btn-editar');
        botonesEditar.forEach(boton => {
            boton.addEventListener('click', function() {
                let id = this.dataset.id;
                let nombre = this.dataset.nombre;
                let idDepto = this.dataset.iddepartamento;
                let idMuni = this.dataset.idmunicipio;
                let idDist = this.dataset.iddistrito;
                let direccion = this.dataset.direccion;
                let imagenUrl = this.dataset.imagen;

                // 1. Llenar Action y campos de texto
                formEditar.action = '/establecimientos/' + id;
                document.getElementById('edit_nombre').value = nombre;
                document.getElementById('edit_direccion_exacta').value = direccion;

                // 2. Llenar Selects en cascada
                editSelectDepto.value = idDepto;
                filtrarMunicipiosEdit(idDepto);
                editSelectMuni.value = idMuni;
                filtrarDistritosEdit(idMuni);
                editSelectDist.value = idDist;

                // 3. Previsualizar imagen actual
                if (inputImagen) inputImagen.value = '';
                if (imagenUrl) {
                    previewImage.src = imagenUrl;
                    previewContainer.style.display = 'block';
                } else {
                    previewImage.src = '';
                    previewContainer.style.display = 'none';
                }
            });
        });

        // 4. Previsualizar nueva imagen seleccionada
        if (inputImagen) {
            inputImagen.addEventListener('change', function(event) {
                let input = event.target;
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
    }
});
