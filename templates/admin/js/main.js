function successAlert (message) {
    PNotify.prototype.options.styling = "bootstrap3";
    new PNotify({
        title: 'Success! ' + message,
        type: 'success',
        icon: false,
        delay: 20000
    });
}

function warnAlert (message) {
    PNotify.prototype.options.styling = "bootstrap3";
    new PNotify({
        title: 'Warning',
        text: message,
        icon: false,
        delay: 20000
    });
}

function errorAlert (message) {
    PNotify.prototype.options.styling = "bootstrap3";
    new PNotify({
        title: 'Error!',
        text: message,
        type: 'error',
        icon: false,
        buttons: {
            closer: false,
            sticker: false
        }
    });

    notice.get().click(function() {
        notice.remove();
    });
}

function infoAlert (message) {
    PNotify.prototype.options.styling = "bootstrap3";
    new PNotify({
        title: 'Info',
        text: message,
        type: 'info',
        icon: false,
        delay: 20000
    });
}