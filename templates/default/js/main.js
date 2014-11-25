$('#toggle-login').click(function(){
  $('#login').toggle();
});
$('#toggle-menu').click(function(){
  $("#menu").toggle();
});
//Notification code written by http://codepen.io/whusterj/
var Alert = (function () {

    var container,
        CONTAINER_ID  = 'aa-notificationContainer',
        ALERT_CLASS   = 'aa-notification',
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