/**
 * Bottom JS
 * Default Admin template for ICMS
 * dillon@nixx.co
 * Github: Nixhatter
 */
if($("textarea").length > 0){
    var simplemde = new SimpleMDE();
}
/**
 * PNotify Alerts
 * https://sciactive.com/pnotify/
 */

$(document).ready(function() {
    /**
     * Does not reload the form when submitted,
     * used on create pages
     */
    $('#reload-form').ajaxForm({
        success: function(response) {
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                successAlert(parsedResponse.message);
                simplemde.value('');
                $('#reload-form').trigger("reset");
            } else {
                errorAlert(parsedResponse.message);
            }
        }
    });
    /**
     * Does not reload the form when submitted,
     * used on edit pages
     */
    $('#no-reload-form').ajaxForm({
        success: function(response) {
            var parsedResponse = jQuery.parseJSON(response);
            if(parsedResponse.result == "success") {
                location.reload();
                successAlert(parsedResponse.message);
            } else {
                errorAlert(parsedResponse.message);
            }
        }
    });
    /**
     * Grabs the div to be refreshed from the FORM name attribute
     */
        $('#partial-reload-form').ajaxForm({
            success: function(response) {
                var updateThisDiv = $('#partial-reload-form').attr('name');
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
/**
 * Sidebar
 */
$(".edit_menu").click(function () {
    $("div.hidden_menu").show("slow");
    $("#nav_name").prop('readonly', false);
    $( "#create" ).attr('name', 'create');
    $( "#submit" ).attr('value', 'create');
    document.getElementById("nav_name").value = "";
    document.getElementById("nav_link").value = "";
    document.getElementById("nav_position").value = "";
});

/**
 * Admin Page Menu Manager
 * Edit Navigation Element
 * @param name
 * @param link
 * @param position
 */
function editNav(name, link, position) {
    document.getElementById("nav_name").value = name;
    document.getElementById("nav_link").value = link;
    document.getElementById("nav_position").value = position;

};
/**
 * Admin Page Menu Manager
 * Delete Navigation Element
 * @param nav_url - Will delete anything in the DB with that URL
 */
function deleteNav(nav_url) {
    $.ajax({
        type: 'POST',
        url: '/admin/pages/menu',
        data: { nav_link: nav_url, nav_delete: 'yes'},
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
};

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
};
