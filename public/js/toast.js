$(document).ready(function() {
    // Inicializa la configuración
    $('.toast').toast({
        animation: true,
        autohide: true,
        delay: 5000
    });

    // Muestra los toasts con una cascada más rápida y fluida
    $('.toast').each(function(index) {
        var toastElement = $(this);
        setTimeout(function() {
            toastElement.toast('show');
        }, index * 120); // Reducido a 120ms para mayor agilidad visual
    });
});
