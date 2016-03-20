$("#toggle-login").click(function(){
    $("#login").toggle("slow");
});
$("#toggle-menu").click(function(){
    $("#menu").toggle("slow");
    $(this).toggleClass( "active" );
});
function successAlert (message) {
    new PNotify({
        title: "Success! " + message,
        type: "success",
        icon: false
    });
}

function warnAlert () {
    new PNotify({
        title: "Warning",
        text: "I have no icon.",
        icon: false
    });
}

function errorAlert (message) {
    new PNotify({
        title: "Error!",
        text: message,
        type: "error",
        icon: false
    });
}

function infoAlert () {
    new PNotify({
        title: "Info",
        text: "Something went wrong.",
        type: "info",
        icon: false
    });
}