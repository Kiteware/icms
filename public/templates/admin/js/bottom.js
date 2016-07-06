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
        $enabled = true;
        simplemde = new SimpleMDE({
            autosave: {
                enabled: $enabled,
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
            if(TextAreaExists()) {
                simplemde.clearAutosavedValue();
            }
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                location.reload();
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
            if(TextAreaExists()) {
                simplemde.clearAutosavedValue();
            }
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
            } else {
                errorAlert(parsedResponse.message);
            }
        }
    });
    /**
     * Grabs the div to be refreshed from the FORM NAME attribute
     */
        $('.partial-reload-form').ajaxForm({
            beforeSubmit: validate,
            success: function(response) {
                if(TextAreaExists()) {
                    simplemde.clearAutosavedValue();
                }
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
function editNav(name, link, position) {
    document.getElementById("nav_name_required").value = name;
    document.getElementById("nav_link_required").value = link;
    document.getElementById("nav_position_required").value = position;
    document.getElementById("is_update").checked = true;
    document.getElementById("is_update").value = link;

}

/**
 * Admin Page Menu Manager
 * Delete Navigation Element
 * @param nav_url - Will delete anything in the DB with that URL
 */
function deleteNav(nav_url) {
    $.ajax({
        type: 'POST',
        url: '/admin/pages/menu',
        data: { nav_link_required: nav_url, nav_delete: 'yes'},
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