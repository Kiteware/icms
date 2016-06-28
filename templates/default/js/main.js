$("#toggle-login").click(function(){
    $("#login").toggle("slow");
});
$("#toggle-menu").click(function(){
    $("#menu").toggle("slow");
    $(this).toggleClass( "active" );
});
function successAlert (message) {
    new PNotify({
        title: 'Success! ' + message,
        type: 'success',
        icon: false
    });
}

function warnAlert (message) {
    new PNotify({
        title: 'Warning',
        text: message,
        icon: false
    });
}

function errorAlert (message) {
    new PNotify({
        title: 'Error!',
        text: message,
        type: 'error',
        icon: false,
        delay: 20000
    });
}

function infoAlert (message) {
    new PNotify({
        title: 'Info',
        text: message,
        type: 'info',
        icon: false
    });
}