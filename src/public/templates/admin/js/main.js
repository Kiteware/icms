function successAlert (message) {
    PNotify.prototype.options.styling = "fontawesome";
    new PNotify({
        title: 'Success! ' + message,
        type: 'success',
        icon: false,
        delay: 20000
    });
}

function warnAlert (message) {
    PNotify.prototype.options.styling = "fontawesome";
    new PNotify({
        title: 'Warning',
        text: message,
        icon: false,
        delay: 20000
    });
}

function errorAlert (message) {
    PNotify.prototype.options.styling = "fontawesome";
    new PNotify({
        title: 'Error!',
        text: message,
        type: 'error',
        icon: false,
        delay: 20000,
        buttons: {
            closer: false,
            sticker: false
        }
    });
}

function infoAlert (message) {
    PNotify.prototype.options.styling = "fontawesome";
    new PNotify({
        title: 'Info',
        text: message,
        type: 'info',
        icon: false,
        delay: 20000
    });
}

$(function () {
    setNavigation();
});

function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".sidebar-nav a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).addClass('active');
            $(this).closest('li').addClass('active');
            if($(this).parent('div').hasClass('collapse')) {
                $(this).parent('div').addClass('in');
            }
        }
    });
}




