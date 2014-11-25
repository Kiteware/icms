

/// Library (usage example at bottom) ///

/**
 * Alert! Alert! is a minimalist JavaScript growl-style notification library
 * designed to run in modern browsers without external dependencies.
 *
 * @author  William Huster  <whusterj@gmail.com>
 * @version 1.0.0
 *
 * @param type: type of alert - 'info', 'success', 'warning', or 'error'
 * @param message: string/html to display in notification
 * @param config: currently only supports 'timeout' - how long to wait before
 *                dismissing the notification.
 */

var Alert = (function () {

    var container,
        CONTAINER_ID  = 'notificationContainer',
        ALERT_CLASS   = 'notification',
        INFO_CLASS    = 'info',
        SUCCESS_CLASS = 'success',
        WARNING_CLASS = 'warning',
        ERROR_CLASS   = 'error';

    exports = {
        alert: alert
    };

    return exports;

    /// functions ///

    function alert (type, message, config) {
        if (!container) { container = genNotificationContainer(); }
        container.appendChild(
            genAlertDiv(type, message, config.timeout)
        );
    }

    function genNotificationContainer () {
        if (container) { return; }
        var containerDiv = document.createElement('div');
        containerDiv.id = CONTAINER_ID;
        document.body.appendChild(containerDiv);
        return containerDiv;
    }

    function genAlertDiv (type, message, timeout) {
        var alertDiv = document.createElement('div');
        alertDiv.className = ALERT_CLASS + ' ' + type;
        alertDiv.innerHTML = '<div>' + message + '</div>';

        //
        alertDiv.addEventListener('click', alertClickHandler);

        //
        if (timeout) {
            alertDiv.timeout = setTimeout(
                function () {
                    removeAlert(alertDiv);
                }, timeout);
        }

        return alertDiv;
    }

    function removeAlert (alert) {
        window.clearTimeout(alert.timeout);
        container.removeChild(alert);
    }

    function alertClickHandler (event) {
        removeAlert(event.currentTarget);
    }

})();

/// Usage Example ///
function newAlert (type, message, timeout) {
    var type = type || 'info',
        message = message || 'No message given',
        config = {
            timeout: timeout || 7000
        };

    // AND HERE'S THE MAGIC:
    Alert.alert(type, message, config);
}

function infoAlert () {
    newAlert('info', '<p>Here\'s some vital info!</p>');
}

function successAlert () {
    newAlert('success', '<p>success!</p>');
}

function warnAlert () {
    newAlert('warning', '<p>Warning!</p>');
}

function errorAlert () {
    newAlert('error', '<p>Error!</p>');
}
$(".dropdown").on("click", function(e){
    // e.preventDefault();

    if($(this).hasClass("open")) {
        $(this).removeClass("open");
        $(this).children("ul").slideUp("fast");
    } else {
        $(this).addClass("open");
        $(this).children("ul").slideDown("fast");
    }
});
$(".edit_menu").click(function () {
    $("div.hidden_menu").show("slow");
    $("#nav_name").prop('readonly', false);
    $( "#create" ).attr('name', 'create');
    $( "#submit" ).attr('value', 'create');
    document.getElementById("nav_name").value = "";
    document.getElementById("nav_link").value = "";
    document.getElementById("nav_position").value = "";
});
//callback handler for form submit
$("#menu_manager").submit(function(e)
{
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data, textStatus, jqXHR)
            {
                //data: return data from server
                $('#hidden_menu').append(data);
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                //if fails
            }
        });
    e.preventDefault(); //STOP default action
});
function editNav(name, link, position) {
    $("div.hidden_menu").show("slow");

    document.getElementById("nav_name").value = name;
    document.getElementById("nav_link").value = link;
    document.getElementById("nav_position").value = position;
    document.getElementById("submit").value = "update";
}
function deleteNav(nav_url) {
    $.ajax({
        type: 'POST',
        url: 'index.php?page=edit_menu.php',
        data: { nav_link: nav_url, nav_delete: 'yes' }
    });
}