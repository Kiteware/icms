<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class Template
|
*/
class shopController extends Controller {
    protected $model;
    public $user_id;
    protected $settings;
    protected $error = [];
    public $total;
    public $paypalPayKey;

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
    }

    public function payment() {
        $this->page = "payment";
        // More info on the paypal pay key can be found here:
        // https://developer.paypal.com/docs/classic/adaptive-payments/ht_ap-embeddedPayment-curl-etc/#mini-b
        $this->paypalPayKey = $this->settings->production->addons->paypalpaykey;
    }

    public function shipping($total) {
        $this->page = "shipping";
        $this->total = $total;

        if (!empty($_POST)) {
            if(isset($_POST['tos'])) {
                $shippingFirstName = $this->postValidation($_POST['shippingfirstname']);
                $shippingLastName = $this->postValidation($_POST['shippinglastname']);
                $shippingAddress1 = $this->postValidation($_POST['shippingaddress1']);
                $shippingAddress2 = $this->postValidation($_POST['shippingaddress2']);
                $shippingCountry = $this->postValidation($_POST['shippingcountry']);
                $shippingState = $this->postValidation($_POST['shippingstate']);
                $shippingCity = $this->postValidation($_POST['shippingcity']);
                $shippingPostalCode = $this->postValidation($_POST['shippingpost']);

                $billingFirstName = $this->postValidation($_POST['billingfirstname']);
                $billingLastName = $this->postValidation($_POST[' billinglastname']);
                $billingCard = $this->postValidation($_POST['billingcard']);
                $billingCVV = $this->postValidation($_POST['cvv']);
                $billingExpiryMonth = $this->postValidation($_POST['expiremonth']);
                $billingExpiryYear = $this->postValidation($_POST['expireyear']);

                if (isset($_POST['useshipping'])) {
                    $billingAddress1 = $shippingAddress1;
                    $billingAddress2 = $shippingAddress2;
                    $billingCountry = $shippingCountry;
                    $billingState = $shippingState;
                    $billingCity = $shippingCity;
                    $billingPostalCode = $shippingPostalCode;
                } else {
                    $billingAddress1 = $this->postValidation($_POST['billingaddress1']);
                    $billingAddress2 = $this->postValidation($_POST['billingaddress2']);
                    $billingCity = $this->postValidation($_POST['billingcity']);
                    $billingCountry = $this->postValidation($_POST['billingcountry']);
                    $billingState = $this->postValidation($_POST['billingstate']);
                    $billingPostalCode = $this->postValidation($_POST['billingpost']);
                }

                $emailAddress = $this->postValidation($_POST['email']);
                $phone = $this->postValidation($_POST['phone']);
                $organization = $this->postValidation($_POST['organization']);
                $additional = $this->postValidation($_POST['additional']);


$content = " A new order has been placed by: " . $shippingFirstName . " " . $shippingLastName . "
Shipping Address: " . $shippingAddress1 . " " . $shippingAddress2 . ", " . $shippingCity . ", " . $shippingState . ", " . $shippingCountry . ". " . $shippingPostalCode . "
Billing Info: " . $billingFirstName . " " . $billingLastName . ", " . $billingAddress1 . " " . $billingAddress2 . " " . $billingCity . ", " . $billingState . ", " . $billingCountry . ". " . $billingPostalCode . "
Payment Info: " . $billingCard . " CVV:" . $billingCVV . " " . $billingExpiryMonth . "/" . $billingExpiryYear . "
Contact Info: email - " . $emailAddress . ", phone -" . $phone . ", organization -" . $organization . "
Additional: " . $additional;

                if ($this->model->mail($this->settings->production->site->email, $this->settings->production->site->name, "Shipping Form", $content)) {
                    $this->alert("success", "Email sent, we'll get back to you shortly.");
                } else {
                    $this->alert("error", "Server error when sending email, please try again.");
                }
            }
        }
    }
}