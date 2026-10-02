$(document).ready(function()
{
    $('.toast').each(function()
    {
        var toastElement = new bootstrap.Toast(this,
            {
                animation: true,
                autohide: true,
                delay: 4500
            });
        toastElement.show();
    });
});
