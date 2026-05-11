function showNotif(type, title, message, delay = 3000) {
    // type: 'success', 'danger', 'warning', 'info', 'theme'
    var icon = '';

    if (type === 'success') icon = 'fa-check-circle';
    else if (type === 'danger') icon = 'fa-exclamation-triangle';
    else if (type === 'warning') icon = 'fa-exclamation-circle';
    else if (type === 'info') icon = 'fa-info-circle';
    else icon = 'fa-bell';

    $.notify('<i class="fas ' + icon + '"></i> <strong>' + title + '</strong> ' + message, {
        type: type,
        allow_dismiss: true,
        delay: delay,
        showProgressbar: true,
        timer: 300,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });
}