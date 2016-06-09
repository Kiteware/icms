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
                $shippingFirstName = isset($_POST['shippingfirstname']) ? $this->postValidation($_POST['shippingfirstname']) : '';
                $shippingLastName = isset($_POST['shippinglastname']) ? $this->postValidation($_POST['shippinglastname']) : '';
                $shippingAddress1 = isset($_POST['shippingaddress1']) ? $this->postValidation($_POST['shippingaddress1']) : '';
                $shippingAddress2 = isset($_POST['shippingaddress2']) ? $this->postValidation($_POST['shippingaddress2']) : '';
                $shippingCountry = isset($_POST['shippingcountry']) ? $this->postValidation($_POST['shippingcountry']) : '';
                $shippingState = isset($_POST['shippingstate']) ? $this->postValidation($_POST['shippingstate']) : '';
                $shippingCity = isset($_POST['shippingcity']) ? $this->postValidation($_POST['shippingcity']) : '';
                $shippingPostalCode = isset($_POST['shippingpost']) ? $this->postValidation($_POST['shippingpost']) : '';

                $billingFirstName = isset($_POST['billingfirstname']) ? $this->postValidation($_POST['billingfirstname']) : '';
                $billingLastName = isset($_POST['billinglastname']) ? $this->postValidation($_POST['billinglastname']) : '';
                $billingCard = isset($_POST['billingcard']) ? $this->postValidation($_POST['billingcard']) : '';
                $billingCVV = isset($_POST['cvv']) ? $this->postValidation($_POST['cvv']) : '';
                $billingExpiryMonth = isset($_POST['expiremonth']) ? $this->postValidation($_POST['expiremonth']) : '';
                $billingExpiryYear = isset($_POST['expireyear']) ? $this->postValidation($_POST['expireyear']) : '';

                if (isset($_POST['useshipping'])) {
                    $billingAddress1 = $shippingAddress1;
                    $billingAddress2 = $shippingAddress2;
                    $billingCountry = $shippingCountry;
                    $billingState = $shippingState;
                    $billingCity = $shippingCity;
                    $billingPostalCode = $shippingPostalCode;
                } else {
                    $billingAddress1 = isset($_POST['billingaddress1']) ? $this->postValidation($_POST['billingaddress1']) : '';
                    $billingAddress2 = isset($_POST['billingaddress2']) ? $this->postValidation($_POST['billingaddress2']) : '';
                    $billingCity = isset($_POST['billingcity']) ? $this->postValidation($_POST['billingcity']) : '';
                    $billingCountry = isset($_POST['billingcountry']) ? $this->postValidation($_POST['billingcountry']) : '';
                    $billingState = isset($_POST['billingstate']) ? $this->postValidation($_POST['billingstate']) : '';
                    $billingPostalCode = isset($_POST['billingpost']) ? $this->postValidation($_POST['billingpost']) : '';
                }

                $emailAddress = isset($_POST['email']) ? $this->postValidation($_POST['email']) : '';
                $phone = isset($_POST['phone']) ? $this->postValidation($_POST['phone']) : '';
                $organization = isset($_POST['organization']) ? $this->postValidation($_POST['organization']) : '';
                $additional = isset($_POST['additional']) ? $this->postValidation($_POST['additional']) : '';

                $next = isset($_POST['next']) ? $this->postValidation($_POST['next']) : '';


                $content = " A new order has been placed by: " . $shippingFirstName . " " . $shippingLastName . "
Shipping Address: " . $shippingAddress1 . " " . $shippingAddress2 . ", " . $shippingCity . ", " . $shippingState . ", " . $shippingCountry . ". " . $shippingPostalCode . "
Billing Info: " . $billingFirstName . " " . $billingLastName . ", " . $billingAddress1 . " " . $billingAddress2 . " " . $billingCity . ", " . $billingState . ", " . $billingCountry . ". " . $billingPostalCode . "
Payment Info: " . $billingCard . " CVV:" . $billingCVV . " " . $billingExpiryMonth . "/" . $billingExpiryYear . "
Contact Info: email - " . $emailAddress . ", phone -" . $phone . ", organization -" . $organization . "
Additional: " . $additional;

                if ($this->model->mail($this->settings->production->site->email, $this->settings->production->site->name, "Shipping Form", $content)) {
                    if($next == "final") {
                        $this->alert("success", "Email sent, we'll get back to you shortly.");
                    } elseif ($next = "pay") {
                        header("Location: /user/shop/payment/".$total);
                        die();
                    }
                } else {
                    $this->alert("error", "Server error when sending email, please try again.");
                }
            }
        }
    }
}