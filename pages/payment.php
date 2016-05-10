<?php
/**
 * ICMS
 * Payment
 * A generic payment page, this is where you enter the paypal details.
 * https://developer.paypal.com/docs/classic/adaptive-payments/ht_ap-embeddedPayment-curl-etc/
 * User: Dillon
 * Date: 5/8/2016
 * Time: 11:45 PM
 */

?>
<div class="container section-md">
    <div class="row">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="ENTERIDHERE">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
</div>
