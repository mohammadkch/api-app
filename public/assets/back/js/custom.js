function showNotif(type, title, message, delay = 3000) {
    var icon = '';
    var bgColor = '';

    if (type === 'success') {
        icon = 'fa-check-circle';
        bgColor = '#28a745';
    } else if (type === 'danger') {
        icon = 'fa-exclamation-triangle';
        bgColor = '#dc3545';
    } else if (type === 'warning') {
        icon = 'fa-exclamation-circle';
        bgColor = '#ffc107';
    } else if (type === 'info') {
        icon = 'fa-info-circle';
        bgColor = '#17a2b8';
    } else {
        icon = 'fa-bell';
        bgColor = '#7367f0';
    }

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