<?php
/**
 * ICMS
 * Payment
 * A generic payment page, this is where you enter the paypal details.
 * User: Dillon
 * Date: 5/8/2016
 * Time: 11:45 PM
 */

?>
<script src="https://www.paypalobjects.com/js/external/apdg.js" type="text/javascript"></script>
<div class="container section-md">
    <div class="row">
        <form action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame" class="standard">

            <label for="buy">Buy Now:</label>
            <input type="image" id="submitBtn" value="Pay with PayPal" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif">
            <input id="type" type="hidden" name="expType" value="mini">
            <input id="paykey" type="hidden" name="paykey" value="<?php echo $this->controller->paypalPayKey ?>">
        </form>
        <script type="text/javascript" charset="utf-8">
            var dgFlowMini = new PAYPAL.apps.DGFlowMini({trigger: 'submitBtn'});
        </script>
        </body>
    </div>
</div>
