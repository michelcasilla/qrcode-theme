<?php
class AzulPayment
{

	public $amount = '';
	public $currency = '';
	public $eventId = '';
	public $invoiceId = '';
	public $email = '';
	public $extraFields = '';
	public $qtyTickets = 1;
	public $approveUrl = null;
	public $cancelUrl = null;
	public $declineUrl = null;
	public $selectSponsor = 1;
	public $productImageUrl = '';
	public $price;
	public $refundPolicy;
	public $emails;

	public function  __construct(Int $amount = 0, String $currency = '', String $eventId = '', String $invoiceId = '', String $email = '', String $extraFields = '', Int $qtyTickets = 1, String $selectSponsor = '', String $approveUrl = '', String $cancelUrl = '', String $declineUrl = '', String $productImageUrl = '')
	{
		$this->amount = $amount;
		$this->currency = $currency;
		$this->eventId = $eventId;
		$this->invoiceId = $invoiceId;
		$this->email = $email;
		$this->extraFields = $extraFields;
		$this->qtyTickets = $qtyTickets;
		$this->approveUrl = $approveUrl;
		$this->cancelUrl = $cancelUrl;
		$this->declineUrl = $declineUrl;
		$this->selectSponsor = $selectSponsor;
		$this->productImageUrl = $productImageUrl;
	}
}


class AzulPaymentResponse
{

	public $OrderNumber = '';
	public $Amount = '';
	public $AuthorizationCode = '';
	public $DateTime = '';
	public $ResponseCode = '';
	public $IsoCode = '';
	public $ResponseMessage = '';
	public $ErrorDescription = '';
	public $RRN = '';
	public $AuthHash = '';
	public $localHash='';
	public $Itbis='';
	public $CustomOrderId='';
	public $CardNumber='';
	public $DataVaultToken='';
	public $DataVaultExpiration='';
	public $DataVaultBrand='';
	public $AzulOrderId='';

	public function __construct(String $OrderNumber = '', String $Amount = '', String $AuthorizationCode = '', String $DateTime = '', String $ResponseCode = '', String $IsoCode = '', String $ResponseMessage = '', String $ErrorDescription = '', String $RRN = '')
	{
		$this->OrderNumber = $OrderNumber;
		$this->Amount = $Amount;
		$this->AuthorizationCode = $AuthorizationCode;
		$this->DateTime = $DateTime;
		$this->ResponseCode = $ResponseCode;
		$this->IsoCode = $IsoCode;
		$this->ResponseMessage = $ResponseMessage;
		$this->ErrorDescription = $ErrorDescription;
		$this->RRN = $RRN;
	}
}

class AzulState
{
	public $hasError = false;
	public $error = '';
	public $data;

	public function __construct($data = [], $hasError = false,  $error = '')
	{
		$this->hasError = $hasError;
		$this->data = $data;
		$this->error = $error;
	}
}
