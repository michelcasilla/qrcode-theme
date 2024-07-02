<?php

class StripePaymentGateWay
{

    private $STRIPE_API_URL = 'https://api.stripe.com/v1';
    private $STRIPE_API_KEY = STRIPE_API_KEY;
    private $PAYMENT_METHOD_TYPES = 'card';
    private $DEFAULT_CURRENCY = "usd";
    private static $instance = null;


    public function __construct(string $STRIPE_API_KEY = null)
    {
        if ($STRIPE_API_KEY) {
            $this->STRIPE_API_KEY = $STRIPE_API_KEY;
        }
    }

    function retrivePaymentIntentInfos(string $paymentId)
    {
        // https://stripe.com/docs/api/payment_intents/retrieve?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi("{$this->STRIPE_API_URL}/payment_intents/{$paymentId}", "", "GET", true);

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $CURLResponse->data->amount_received = StripePaymentGateWay::parseAmount($CURLResponse->data->amount_received);
                $CURLResponse->data->amount = StripePaymentGateWay::parseAmount($CURLResponse->data->amount);
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }

        return $response;
    }

    function updatePaymentIntent(string $paymentId, $amount, $eventID, $invoiceId, $statementDescriptor = "WC Events", $currency = false, $paymentMethod = false, $email, $ibo, $extraFields = '')
    {
        // https://stripe.com/docs/api/payment_intents/update?lang=curl
        $response = new stdClass();
        try {

            $amount = StripePaymentGateWay::getAmount($amount);
            $crnc = $this->DEFAULT_CURRENCY;
            $descriptor = substr($statementDescriptor, 0, 22);
            $freePayments = VTW_Event_Payment::getFreePaymentsByIbo($ibo, $eventID);
            $discount = 0;
            try {
                $discount = (FREE_PAYMENT_PER_EVENT - $freePayments->total_tickets) * 100;
                if ($discount < 0) {
                    $discount = 0;
                }
                if ($discount > $amount) {
                    $discount = $amount;
                }
            } catch (Exception $e) {
                // $discount = 0;
            }
            if ($currency !== NULL) {
                $crnc = $currency;
            }

            $payment_method_types = $this->PAYMENT_METHOD_TYPES;
            if ($paymentMethod !== NULL) {
                $payment_method_types = $paymentMethod;
            }

            $key = $this->STRIPE_API_KEY;
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents/{$paymentId}",
                "amount={$amount}&currency={$crnc}&statement_descriptor={$descriptor}&payment_method_types%5B%5D={$payment_method_types}&receipt_email={$email}&$extraFields",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }

        return $response;
    }

    function confirmPaymentIntent(string $paymentId)
    {
        // https://stripe.com/docs/api/payment_intents/confirm?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents/{$paymentId}/confirm",
                "",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }
        return $response;
    }

    function capturePaymentIntent(string $paymentId)
    {
        // https://stripe.com/docs/api/payment_intents/capture?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents/{$paymentId}/capture",
                "",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }
        return $response;
    }

    function cancelPaymentIntent(string $paymentId)
    {
        // https://stripe.com/docs/api/payment_intents/cancel?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents/{$paymentId}/cancel",
                "",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }
        return $response;
    }

    function listAllPaymentIntent($limit = 1000)
    {
        // https://stripe.com/docs/api/payment_intents/list?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents",
                "limit={$limit}",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }
        return $response;
    }

    function seactchPaymentIntent(string $invoiceId)
    {
        // https://stripe.com/docs/api/payment_intents/search?lang=curl
        $response = new stdClass();
        try {
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents/search",
                "query=\"metadata['invoiceId']:'${invoiceId}'\"",
                "POST"
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }
        return $response;
    }

    function paymentIntentCreate($amount, $eventID, $invoiceId, $statementDescriptor = "WC Events", $currency = false, $ibo, $paymentMethod = false)
    {

        $response = new stdClass();
        try {
            $amount = $amount;
            $crnc = $this->DEFAULT_CURRENCY;
            $descriptor = substr($statementDescriptor, 0, 22);

            if ($currency !== NULL) {
                $crnc = $currency;
            }
            $payment_method_types = $this->PAYMENT_METHOD_TYPES;
            if ($paymentMethod !== NULL) {
                $payment_method_types = $paymentMethod;
            }
            $amount = StripePaymentGateWay::getAmount($amount);
            $CURLResponse = $this->__callApi(
                "{$this->STRIPE_API_URL}/payment_intents",
                "amount={$amount}&currency={$crnc}&statement_descriptor={$descriptor}&payment_method_types%5B%5D={$payment_method_types}&metadata[invoiceID]={$invoiceId}&metadata[ibo]={$ibo}",
                "POST",
                false
            );

            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }

        return $response;
    }

    private function __callApi(string $url, $postFields, string $method = 'POST', bool $usePWD = true, string $contentType = 'application/x-www-form-urlencoded', string $cache = 'no-cache')
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $postFields,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_USERPWD => $usePWD ? $this->STRIPE_API_KEY : "",
            CURLOPT_HTTPHEADER => array(
                "authorization: " . $this->__getAuthorization(),
                "cache-control: " . $cache,
                "content-type: " . $contentType
            ),
        ));
        $stripeResponse = curl_exec($curl);
        $stripeErr = curl_error($curl);

        $response = new StdClass();
        $response->data = json_decode($stripeResponse);
        $response->error = $stripeErr || false;

        curl_close($curl);

        return $response;
    }

    private function __getAuthorization()
    {
        return "Bearer {$this->STRIPE_API_KEY}";
    }

    public static function getInstance(string $STRIPE_API_KEY = null)
    {
        if (self::$instance == null) {
            self::$instance = new StripePaymentGateWay($STRIPE_API_KEY);
        }

        return self::$instance;
    }

    private static function getAmount(int $amount)
    {
        return number_format(($amount * 100), 0, '', '');
    }

    private static function parseAmount(int $amount)
    {
        return number_format(($amount / 100), 0, '', '');
    }
}

abstract class CURLResponse
{
    public $data;
    public $error;
}
