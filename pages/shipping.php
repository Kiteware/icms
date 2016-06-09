<?php
/**
 * ICMS
 * Shipping
 * A simple form for submitting shipping details. Currently it will email the results,
 * but the plans are to incorporate everything into the database.
 * User: Dillon
 * Date: 5/8/2016
 * Time: 1:32 PM
 */
?>
<div class="container section-md">
    <div class="row">
        <h1>Generic Shipping form</h1>
        <p>Details about the order form, with help instructions.</p>
        <h3 class="alert alert-success">Total: <?php echo $this->controller->total ?> USD</h3>

        <h3 class="col-md-12">Shipping Information</h3>
        <form class="form-horizontal" action=" " method="post"  id="contact_form">
            <div class="shipping-info">
                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingfirstname">First Name</label>
                    <input id="shippingfirstname" name="shippingfirstname" type="text" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippinglastname">Last Name</label>
                    <input id="shippinglastname" name="shippinglastname" type="text" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingaddress1">Shipping Address 1</label>
                    <input id="shippingaddress1" name="shippingaddress1" type="text" placeholder="" class="form-control" required=true">
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label" for="shippingaddress2">Shipping Address 2</label>
                    <input id="shippingaddress2" name="shippingaddress2" type="text" placeholder="" class="form-control">
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingcountry">Country</label>
                    <input id="shippingcountry" name="shippingcountry" type="text" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingstate">State</label>
                    <input id="shippingstate" name="shippingstate" type="text" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingcity">Shipping City</label>
                    <input id="shippingcity" name="shippingcity" type="text" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="shippingpost">Postal Code</label>
                    <input id="shippingpost" name="shippingpost" type="text" placeholder="" class="form-control" required>
                </div>

                <hr/>

                <div class="col-md-12 ">
                    <div class="col-md-4 ">
                        <h3>Billing Information</h3>
                    </div>
                    <div class="col-md-8 ">
                        <input type="checkbox" name="useshipping" id="useshipping" checked="checked">
                        Use Shipping Address
                    </div>

                </div>

                <div class="form-group col-md-6">
                    <div class="control-group">
                        <span class="required-lbl">* </span><label class="control-label" for="billingfirstname">First Name</label>
                        <input id="billingfirstname" name="billingfirstname" type="text" placeholder="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="control-group">
                        <span class="required-lbl">* </span><label class="control-label" for="billinglastname">Last Name</label>
                        <input id="billinglastname" name="billinglastname" type="text" placeholder="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="control-group">
                        <span class="required-lbl">* </span><label class="control-label" for="billingcard">Card Number</label>
                        <input id="billingcard" name="billingcard" type="text" placeholder="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group col-md-5">
                    <div class="control-group col-md-4">
                        <span class="required-lbl">* </span><label class="control-label" for="cvv">CVV</label>
                        <input id="cvv" name="cvv" type="text" placeholder="" class="form-control" required>
                    </div>
                    <div class="control-group col-md-8">
                        <div class="control-group">
                            <label class="control-label" for="month">Expiration Date</label>
                            <select id="expiremonth" name="expiremonth" class="form-control"">
                            <option>Select Month</option>
                            <option>01</option>
                            <option>02</option>
                            <option>03</option>
                            <option>04</option>
                            <option>05</option>
                            <option>06</option>
                            <option>07</option>
                            <option>08</option>
                            <option>09</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                            </select>
                            <select id="expireyear" name="expireyear" class="form-control">
                                <option>Select Year</option>
                                <option>14</option>
                                <option>15</option>
                                <option>16</option>
                                <option>17</option>
                                <option>18</option>
                                <option>19</option>
                                <option>20</option>
                                <option>21</option>
                                <option>22</option>
                                <option>23</option>
                                <option>24</option>
                                <option>25</option>
                                <option>26</option>
                                <option>27</option>
                                <option>28</option>
                                <option>29</option>
                                <option>30</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                </div>

                <hr/>
                <div id="billing-address">
                    <h3 class="col-md-12">Billing Address</h3>

                    <div class="form-group col-md-6">
                        <span class="required-lbl">* </span><label class="control-label" for="billingaddress1">Address 1</label>
                        <input id="billingaddress1" name="billingaddress1" type="text" placeholder="" class="form-control">

                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label" for="billingaddress2">Address 2</label>
                        <input id="billingaddress2" name="billingaddress2" type="text" placeholder="" class="form-control" >
                    </div>

                    <div class="form-group col-md-6">
                        <span class="required-lbl">* </span><label class="control-label" for="billingcountry">Country</label>
                        <input id="lastname" name="billingcountry" type="text" placeholder="" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <span class="required-lbl">* </span><label class="control-label" for="billingstate">State</label>
                        <input id="lastname" name="billingstate" type="text" placeholder="" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <span class="required-lbl">* </span><label class="control-label" for="billingcity">City</label>
                        <input id="billingcity" name="billingcity" type="text" placeholder="" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <span class="required-lbl">* </span><label class="control-label" for="billingpost">Postal Code</label>
                        <input id="billingpost" name="billingpost" type="text" placeholder="" class="form-control">
                    </div>
                </div>
                <h3 class="col-md-12">Contact Information:</h3>

                <div class="form-group col-md-6">
                    <span class="required-lbl">* </span><label class="control-label" for="emailaddress">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label" for="phone">Phone</label>
                    <input id="phone" name="phone" type="text" placeholder="" class="form-control">
                </div>

                <div class="form-group col-md-6">
                    <label class="control-label" for="organization">Organization</label>
                    <input id="organization" name="organization" type="text" placeholder="" class="form-control">
                </div>

                <h3 class="col-md-12">Additional Information:</h3>


                <div class="form-group col-md-6">
                    <label for="additional">Let us know if you have any special requests</label>

                    <input id="additional" name="additional" type="text" placeholder="" class="form-control" >
                </div>

                <div class="form-group col-md-12">
                        <label class="checkbox" for="iaccept-0">
                            <input type="checkbox" name="tos" id="tos" value="I accept the Teams and conditions" required>
                            I accept the <a href="">Teams and conditions</a>
                        </label>
                </div>

                <div class="form-group col-md-12">
                    <div class="control-group confirm-btn">
                        <label class="control-label" for="placeorderbtn"></label>
                        <button type="submit" class="btn btn-primary">Next - Payment</button>
                    </div>
                </div>
        </form>
    </div>
</div>
</div>

<script type="application/javascript">
    function evaluate(){

        var relatedItem = $("#billing-address");
        if(document.getElementById('useshipping').checked) {
            relatedItem.fadeOut();
        }else{
            relatedItem.fadeIn();
        }
    }

    $('input[type="checkbox"]').click(evaluate).each(evaluate);
</script>