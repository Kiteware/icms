<?php
namespace Nixhatter\ICMS\controller;

/**
 * eCommerce Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Respect\Validation\Validator as v;

class shopController extends Controller
{
    protected $model;
    public $user_id;
    protected $settings;
    protected $error = [];
    public $total;
    public $paypalPayKey;

    public function __construct(\Nixhatter\ICMS\model\UserModel $model)
    {
        $this->model = $model;
        $this->settings = $model->container['settings'];
    }

    public function getName()
    {
        return 'ShippingController';
    }

    public function payment()
    {
        $this->page = "payment";
        // More info on the paypal pay key can be found here:
        // https://developer.paypal.com/docs/classic/adaptive-payments/ht_ap-embeddedPayment-curl-etc/#mini-b
        $this->paypalPayKey = $this->settings->production->addons->paypalpaykey;
    }

    public function shipping($total)
    {
        $this->page = "shipping";
        $this->total = $total;

        if (!empty($_POST['submit'])) {
            if (isset($_POST['tos'])) {
                $shippingFirstName = !empty($_POST['shippingfirstname']) ? $this->postValidation($_POST['shippingfirstname']) : '';
                $shippingLastName = !empty($_POST['shippinglastname']) ? $this->postValidation($_POST['shippinglastname']) : '';
                $shippingAddress1 = !empty($_POST['shippingaddress1']) ? $this->postValidation($_POST['shippingaddress1']) : '';
                $shippingAddress2 = !empty($_POST['shippingaddress2']) ? $this->postValidation($_POST['shippingaddress2']) : '';
                $shippingCountry = !empty($_POST['shippingcountry']) ? $this->postValidation($_POST['shippingcountry']) : '';
                $shippingState = !empty($_POST['shippingstate']) ? $this->postValidation($_POST['shippingstate']) : '';
                $shippingCity = !empty($_POST['shippingcity']) ? $this->postValidation($_POST['shippingcity']) : '';
                $shippingPostalCode = !empty($_POST['shippingpost']) ? $this->postValidation($_POST['shippingpost']) : '';

                $billingFirstName = !empty($_POST['billingfirstname']) ? $this->postValidation($_POST['billingfirstname']) : '';
                $billingLastName = !empty($_POST['billinglastname']) ? $this->postValidation($_POST['billinglastname']) : '';
                $billingCard = !empty($_POST['billingcard']) ? $this->postValidation($_POST['billingcard']) : '';
                $billingCVV = !empty($_POST['cvv']) ? $this->postValidation($_POST['cvv']) : '';
                $billingExpiryMonth = !empty($_POST['expiremonth']) ? $this->postValidation($_POST['expiremonth']) : '';
                $billingExpiryYear = !empty($_POST['expireyear']) ? $this->postValidation($_POST['expireyear']) : '';

                if (isset($_POST['useshipping'])) {
                    $billingAddress1 = $shippingAddress1;
                    $billingAddress2 = $shippingAddress2;
                    $billingCountry = $shippingCountry;
                    $billingState = $shippingState;
                    $billingCity = $shippingCity;
                    $billingPostalCode = $shippingPostalCode;
                } else {
                    $billingAddress1 = !empty($_POST['billingaddress1']) ? $this->postValidation($_POST['billingaddress1']) : '';
                    $billingAddress2 = !empty($_POST['billingaddress2']) ? $this->postValidation($_POST['billingaddress2']) : '';
                    $billingCity = !empty($_POST['billingcity']) ? $this->postValidation($_POST['billingcity']) : '';
                    $billingCountry = !empty($_POST['billingcountry']) ? $this->postValidation($_POST['billingcountry']) : '';
                    $billingState = !empty($_POST['billingstate']) ? $this->postValidation($_POST['billingstate']) : '';
                    $billingPostalCode = !empty($_POST['billingpost']) ? $this->postValidation($_POST['billingpost']) : '';
                }

                $emailAddress = !empty($_POST['email']) ? $this->postValidation($_POST['email']) : '';
                $phone = !empty($_POST['phone']) ? $this->postValidation($_POST['phone']) : '';
                $organization = !empty($_POST['organization']) ? $this->postValidation($_POST['organization']) : '';
                $additional = !empty($_POST['additional']) ? $this->postValidation($_POST['additional']) : '';

                $next = !empty($_POST['next']) ? $this->postValidation($_POST['next']) : '';


                $content = " A new order has been placed by: " . $shippingFirstName . " " . $shippingLastName . "
Shipping Address: " . $shippingAddress1 . " " . $shippingAddress2 . ", " . $shippingCity . ", " . $shippingState . ", " . $shippingCountry . ". " . $shippingPostalCode . "
Billing Info: " . $billingFirstName . " " . $billingLastName . ", " . $billingAddress1 . " " . $billingAddress2 . " " . $billingCity . ", " . $billingState . ", " . $billingCountry . ". " . $billingPostalCode . "
Payment Info: " . $billingCard . " CVV:" . $billingCVV . " " . $billingExpiryMonth . "/" . $billingExpiryYear . "
Contact Info: email - " . $emailAddress . ", phone -" . $phone . ", organization -" . $organization . "
Additional: " . $additional;

                if ($this->model->mail($this->settings->production->site->email, $this->settings->production->site->name, "Shipping Form", $content)) {
                    if ($next == "final") {
                        $_SESSION['message'] = ['success', 'Email sent, we\'ll get back to you shortly'];
                    } elseif ($next = "pay") {
                        header("Location: /user/shop/payment/".$total);
                        exit();
                    }
                } else {
                    $_SESSION['message'] = ['success', 'Server error when sending email, please try again'];
                }
            }
        }
    }
}
