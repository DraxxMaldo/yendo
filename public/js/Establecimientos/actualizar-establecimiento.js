$(document).ready(function() {
    // 1. Al hacer clic en Editar
    $('.btn-editar').on('click', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let idDepartamento = $(this).data('iddepartamento');
        let idMunicipio = $(this).data('idmunicipio');
        let direccion = $(this).data('direccion');
        let imagenUrl = $(this).data('imagen'); // Capturamos la imagen

        $('#formEditarEstablecimiento').attr('action', '/establecimientos/' + id);
        $('#edit_nombre').val(nombre);
        $('#edit_direccion_exacta').val(direccion);

        // Lógica de select en cascada
        $('#edit_filtro_departamento').val(idDepartamento).trigger('change');
        setTimeout(() => { $('#edit_select_municipio').val(idMunicipio); }, 100);

        // Lógica de la vista previa de la imagen actual
        $('#edit_imagen').val(''); // Limpiamos el input file por si tenía algo de una edición anterior
        if (imagenUrl) {
            $('#preview_image').attr('src', imagenUrl);
            $('#preview_container').show();
        } else {
            $('#preview_image').attr('src', '');
            $('#preview_container').hide();
        }
    });

    // 2. Lógica para el cambio en el select de departamentos
    $('#edit_filtro_departamento').on('change', function() {
        let departamentoSeleccionado = $(this).val();
        let $selectMunicipio = $('#edit_select_municipio');

        $selectMunicipio.prop('disabled', false);
        $selectMunicipio.find('option').hide();
        $selectMunicipio.find('option[value=""]').show();
        $selectMunicipio.find('option[data-departamento="' + departamentoSeleccionado + '"]').show();

        if ($selectMunicipio.find('option:selected').data('departamento') != departamentoSeleccionado) {
            $selectMunicipio.val('');
        }
    });

    // 3. NUEVO: Previsualizar imagen nueva al seleccionar el archivo
    $('#edit_imagen').on('change', function(event) {
        let input = event.target;
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_image').attr('src', e.target.result);
                $('#preview_container').fadeIn();
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const editSelectDepto = document.getElementById('edit_filtro_departamento');
    const editSelectMuni = document.getElementById('edit_select_municipio');
    const editSelectDist = document.getElementById('edit_select_distrito');

    if (editSelectDepto && editSelectMuni && editSelectDist) {
        // Clonar opciones en memoria para poder filtrar sin perder datos
        const opcionesMuniEdit = Array.from(editSelectMuni.querySelectorAll('option[data-departamento]')).map(opt => opt.cloneNode(true));
        const opcionesDistEdit = Array.from(editSelectDist.querySelectorAll('option[data-municipio]')).map(opt => opt.cloneNode(true));

        // Función para filtrar municipios basada en el departamento
        function filtrarMunicipiosEdit(idDepto) {
            editSelectMuni.innerHTML = '<option value="" disabled selected>Seleccione un municipio...</option>';
            opcionesMuniEdit.forEach(opcion => {
                if (opcion.getAttribute('data-departamento') == idDepto) {
                    editSelectMuni.appendChild(opcion.cloneNode(true));
                }
            });
        }

        // Función para filtrar distritos basada en el municipio
        function filtrarDistritosEdit(idMuni) {
            editSelectDist.innerHTML = '<option value="" disabled selected>Seleccione un distrito...</option>';
            opcionesDistEdit.forEach(opcion => {
                if (opcion.getAttribute('data-municipio') == idMuni) {
                    editSelectDist.appendChild(opcion.cloneNode(true));
                }
            });
        }

        // Eventos de cambio manuales por el usuario
        editSelectDepto.addEventListener('change', function() {
            filtrarMunicipiosEdit(this.value);
            editSelectDist.innerHTML = '<option value="" disabled selected>Primero seleccione un municipio...</option>';
        });

        editSelectMuni.addEventListener('change', function() {
            filtrarDistritosEdit(this.value);
        });

        // Evento al abrir el modal (Si usas jQuery para el clic del botón de la tabla)
        $('.btn-editar').on('click', function() {
            let idDepto = $(this).data('iddepartamento');
            let idMuni = $(this).data('idmunicipio');
            let idDist = $(this).data('iddistrito');

            // 1. Cargar departamento
            editSelectDepto.value = idDepto;

            // 2. Filtrar y cargar municipio
            filtrarMunicipiosEdit(idDepto);
            editSelectMuni.value = idMuni;

            // 3. Filtrar y cargar distrito
            filtrarDistritosEdit(idMuni);
            editSelectDist.value = idDist;
        });
    }
});
