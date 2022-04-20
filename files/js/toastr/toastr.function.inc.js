function alertToastr(title, message, type, position, clearPrevious) {
    if (position == '')
        position = 'toast-top-right';

    if (clearPrevious)
        toastr.clear();

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": position,
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    if (type == 'success')
        toastr.success(message, title);
    else if (type == 'warning')
        toastr.warning(message, title);
    else if (type == 'error')
        toastr.error(message, title);
    else if (type == 'info')
        toastr.info(message, title);
}