/**
 * Bottom JS
 * Default Admin template for ICMS
 * dillon@nixx.co
 * Github: Nixhatter
 */

/**
 * Check if a textarea exists, usually so we can do something
 * to simplemde.
 * @returns {boolean}
 * @constructor
 */
function TextAreaExists() {
    if($("textarea").length > 0){
        if(window.location.href.indexOf("template") < 0) {
            return true;
        }
    }
    return false;
}

/**
 * PNotify Alerts
 * https://sciactive.com/pnotify/
 */

$(document).ready(function() {
    if(TextAreaExists()) {
        var lastPart = window.location.pathname;
        simplemde = new SimpleMDE({
            autosave: {
                enabled: false,
                uniqueId: lastPart,
                delay: 1000
            }
        });
    }
    /**
     * Reloads the data on the page through an ajax call
     * Saves the user a page refresh
     */
    $('.reload-form').ajaxForm({
        success: function(response) {
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
                if(parsedResponse.location) {
                    window.location = parsedResponse.location;
                }
            } else {
                errorAlert(parsedResponse.message);
            }
        }
    });
    /**
     * Does not reload the form when submitted,
     * used on edit pages
     */
    $('.no-reload-form').ajaxForm({
        success: function(response) {
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
                if(parsedResponse.location) {
                    window.location = parsedResponse.location;
                }
            } else {
                errorAlert(parsedResponse.message);
            }
        }
    });
    /**
     * Grabs the div to be refreshed from the FORM NAME attribute
     */
        $('.partial-reload-form').ajaxForm({
            success: function(response) {
                var updateThisDiv = $('.partial-reload-form').attr('name');
                $( "#" +updateThisDiv).load(document.URL + " #" +  updateThisDiv);
                var parsedResponse = jQuery.parseJSON(response);
                if(parsedResponse.result == "success") {
                    successAlert(parsedResponse.message);
                } else {
                    errorAlert(parsedResponse.message);
                }
            }
        });
});
$(".dropdown").on("click", function(e){
    if($(this).hasClass("open")) {
        $(this).removeClass("open");
        $(this).children("ul").slideUp("fast");
    } else {
        $(this).addClass("open");
        $(this).children("ul").slideDown("fast");
    }
});

function validate(formData, jqForm, options) {
    // formData is an array of objects representing the name and value of each field
    // that will be sent to the server;  it takes the following form:
    //
    // [
    //     { name:  username, value: valueOfUsernameInput },
    //     { name:  password, value: valueOfPasswordInput }
    // ]

    for (var i=0; i < formData.length; i++) {
        if (jqForm[0].name.indexOf("required") < 0 && !formData[i].value) {
            $('[name="'+formData[i].name+'"]').addClass( "error" )
                                    .delay(2000)
                                    .queue(function (next) {
                                        $(this).removeClass( "error" );
                                        next();
                                    });
            return false;
        }
    }
}
/**
 * Admin Page Menu Manager
 * Edit Navigation Element
 * @param name
 * @param link
 * @param position
 */
function editNav(name, link, position, nav_id, parent) {
    document.getElementById("nav-name").value = name;
    document.getElementById("nav-link").value = link;
    document.getElementById("nav-position").value = position;
    document.getElementById("parent").value = parent;
    document.getElementById("nav-id").value = nav_id;

}

/**
 * Admin Page Menu Manager
 * Delete Navigation Element
 * @param nav_url - Will delete anything in the DB with that URL
 */
function deleteNav(nav_id) {
    $.ajax({
        type: 'POST',
        url: '/admin/pages/menu',
        data: { 'nav-id': nav_id, nav_delete: 'yes'},
        success: function(response){
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
            } else {
                errorAlert(parsedResponse.message);
            }
            $('#menu-manager').load(document.URL +  ' #menu-manager');
        }
    });
}

function ajaxCall(url, divToUpdate) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function(response){
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
            } else {
                errorAlert(parsedResponse.message);
            }
            $('#'+divToUpdate).load(document.URL +  ' #' + divToUpdate);
        }
    });
}

$(window).keypress(function(event) {
    if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
    document.getElementsByName("submit")[0].click();
    event.preventDefault();
    return false;
});

$(document).ready(function(){
    $("#mailerType").change(function(){
        var id = $(this).val();
        if (id === "oauth") {
            $("#clientId").show();
            $("#clientSecret").show();
            $("#basicPass").hide();
        }
        if (id === "basic") {
            $("#clientId").hide();
            $("#clientSecret").hide();
            $("#basicPass").show();
        }
    });
    $("#mailerType").trigger('change');
});

$('form').parsley();