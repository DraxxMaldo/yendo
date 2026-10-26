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
