<?php
require_once('transactionHold.db.php');

class AzulPaymentGateWay
{

	// the merchant id provided by azul
	private $merchant_id;
	// the merchant name provides by azul
	private $merchant_name;
	// the merchant type provided by azul
	private $merchantType;
	// The private key provided by azul
	private $private_key;
	// The environment mode LIVE | TEST
	private $mode;
	private $tax_percentage = 0.00;
	private $response_post_url = 'wctvApi/v1/azul/process_response';

	public function __construct($merchant_id, $merchant_name, $merchantType, $private_key, $mode)
	{
		$this->merchant_id = $merchant_id;
		$this->merchant_name = $merchant_name;
		$this->merchantType = $merchantType;
		$this->private_key = $private_key;
		$this->mode = $mode;
	}

	public function form($bucket)
	{

		$bucketData = unserialize($bucket->data);
		if ($this->mode == 'TEST') {
			$gateway_url = AZUL_TEST_URL;
		} elseif ($this->mode == 'LIVE') {
			$gateway_url = AZUL_LIVE_URL;
		}

		$currencyCode = '$';
		// US$ need to convert
		$orderTotal = $bucketData->amount;


		$taxAmount  = floatval(str_replace(",", "", $orderTotal)) * floatval($this->tax_percentage);
		$taxAmount = number_format((float)$taxAmount, 2, '.', '');
		$taxAmount = str_replace('.', '', $taxAmount);
		$orderTotal = $this->getAmount($orderTotal);
		$ApprovedUrl = get_rest_url() . '' . $this->response_post_url;
		$params['MerchantId'] 		 = $this->merchant_id;
		$params['MerchantName']		 = $this->merchant_name;
		$params['MerchantType'] 	 = $this->merchantType;
		$params['CurrencyCode'] 	 = $currencyCode;
		$params['OrderNumber'] 		 = $bucket->invoiceId;
		$params['Amount'] 			 = $orderTotal;
		$params['ITBIS'] 			 = $taxAmount;
		$params['ApprovedUrl'] 		 = $ApprovedUrl;
		$params['DeclinedUrl'] 		 = $bucketData->declineUrl;
		$params['CancelUrl']		 = $bucketData->cancelUrl;
		$params['UseCustomField1']   = '1';
		$params['CustomField1Label'] = 'Email';
		$params['CustomField1Value'] = $bucketData->email;
		$params['UseCustomField2']   = '1';
		$params['CustomField2Label'] = 'Tickets';
		$params['CustomField2Value'] = $bucketData->qtyTickets;

		$post_values = $params['MerchantId']
			. $params['MerchantName']
			. $params['MerchantType']
			. $params['CurrencyCode']
			. $params['OrderNumber']
			. $params['Amount']
			. $params['ITBIS']
			. $params['ApprovedUrl']
			. $params['DeclinedUrl']
			. $params['CancelUrl']
			. $params['UseCustomField1']
			. $params['CustomField1Label']
			. $params['CustomField1Value']
			. $params['UseCustomField2']
			. $params['CustomField2Label']
			. $params['CustomField2Value']
			. $this->private_key;

		$params['AuthHash'] = $this->encrypt($post_values);
		$params['ShowTransactionResult'] = 0;
		$params['DesignV2'] = 1;
		$params['LogoImageUrl'] = "https://www.worldcrowns.tv/wp-content/uploads/2022/11/wc500x500.png";
		if($bucketData->productImageUrl){
			$params['ProductImageUrl'] = $bucketData->productImageUrl;
		}
		$azul_arg_array = array();

		foreach ($params as $key => $value) {
			$azul_arg_array[] = '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
		}

		return  '<form action="' . esc_url($gateway_url) . '" method="post">
				' . implode('', $azul_arg_array) . '
				<input type="submit" class="button" value="' . __('Enviar') . '" />
			</form>';
	}

	private function encrypt($str)
	{
		$str = mb_convert_encoding($str, 'UTF-16LE', 'ASCII');
		return hash_hmac('sha512', $str, $this->private_key);
	}

	// public function getBucketId(String $bucketId)
	// {
	// 	$conf = new AzulPayment();
	// 	return $conf;
	// }

	public function processPayment(AzulPayment $data)
	{
		$bucket = new Bucket();
		$bucket->processor = 'AZUL';
		$bucket->data = serialize($data);
		$bucket->invoiceId = $data->invoiceId;
		$bucket->paymentId = '';
		$bucket->paymentData = '';
		$bucket->createAt = current_time('timestamp');
		$bucketId = TransactionBucket::insert($bucket);
		return $bucketId;
	}

	public function verifyPayment(AzulPaymentResponse $payment)
	{
		$response = new AzulState();
		$response->hasError = false;

		try {

			$post_values =
				$payment->OrderNumber
				. $payment->Amount
				. $payment->AuthorizationCode
				. $payment->DateTime
				. $payment->ResponseCode
				. $payment->IsoCode
				. $payment->ResponseMessage
				. $payment->ErrorDescription
				. $payment->RRN
				. $this->private_key;

			$payment->localHash = $this->encrypt($post_values);

			if ($payment->localHash !== $payment->AuthHash) {
				throw new Exception("INVALID_HASH");
			}

			if ($payment->IsoCode !== '00') {
				throw new Exception("TRANSACTION_NOT_APPROVED");
			}

			$response->hasError = false;
			$response->error = '';
			$response->data = $payment;
		} catch (\Throwable $e) {
			$response->hasError = true;
			$response->error = $e;
		}

		return $response;
	}

	public static function verifyBucketComplete($paymentId)
	{
		$bucket = TransactionBucket::findByPaymentId($paymentId);
		$result = false;
		if ($bucket && $bucket->status === 'COMPLETE') {
			$result = true;
		}
		return $result;
	}

	public function getAmount(int $amount)
	{
		return number_format(($amount * 100), 0, '', '');
	}

	public function parseAmount(int $amount)
	{
		return number_format(($amount / 100), 0, '', '');
	}
}
