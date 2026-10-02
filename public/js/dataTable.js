$(function () {
    $('#tbl').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "bootstrap": true,
        "language": {
            "url": "/files/Spanish.json"
        }
    });
});
