<?php

class Bucket
{
	public $id;
	public $processor;
	public $data;
	public $invoiceId;
	public $paymentId;
	public $paymentData;
	public $createAt;
	public $updateAt;
	public $status;

	public function __construct(
		Int $id = null,
		String $processor = null,
		String $data = null,
		String $invoiceId = null,
		String $paymentId = null,
		String $paymentData = null,
		String $status = 'PENDING'
	) {
		$this->id = $id;
		$this->processor = $processor;
		$this->data = $data;
		$this->invoiceId = $invoiceId;
		$this->paymentId = $paymentId;
		$this->paymentData = $paymentData;
		$this->status = $status;
	}
}

class TransactionBucket
{


	public static function table()
	{
		global $wpdb;
		return "{$wpdb->prefix}event_transaction_bucket";
	}

	public static function get()
	{
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM {TransactionHold::table()}");
		return $wpdb->get($query);
	}

	public static function getByStatus($status = "PENDING")
	{
		global $wpdb;
		$table = TransactionBucket::table();
		if(strtolower($status) == 'all'){
			$query = $wpdb->prepare("SELECT * FROM {$table} ORDER BY id DESC", array($status));
			return $wpdb->get_results($query);
		}else{
			$query = $wpdb->prepare("SELECT * FROM {$table} WHERE status = '%s' ORDER BY id DESC", array($status));
			return $wpdb->get_results($query);
		}
	}
	
	public static function count()
	{
		global $wpdb;
		$table = TransactionBucket::table();
		$count = $wpdb->get_var("SELECT COUNT(*) FROM {$table}" );
		return $count;
	}
	
	public static function countPending($status = "Pending")
	{
		global $wpdb;
		$table = TransactionBucket::table();
		if(strtolower($status) == 'all'){
			$query = $wpdb->prepare("SELECT COUNT(*) FROM {$table}");
		}else{
			$query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE status = '%s'", array($status));
		}
		$count = $wpdb->get_var($query);
		return $count;
	}

	public static function findById($id)
	{
		global $wpdb;
		$table = TransactionBucket::table();
		$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d ORDER BY id DESC LIMIT 1", array($id));
		$result = $wpdb->get_row($query);
		return $result;
	}

	public static function findByInvoiceId($invoiceID)
	{
		global $wpdb;
		$table = TransactionBucket::table();
		$query = $wpdb->prepare("SELECT * FROM {$table} WHERE invoiceId = %s ORDER BY id DESC LIMIT 1", array($invoiceID));
		// TEMPORAL
		try {
			error_log("AZUL_processPayment_INVOICEID: {$query}");
		} catch (\Throwable $th) {}
		// END TEMPORAL
		return $wpdb->get_row($query);
	}

	public static function findByPaymentId($paymentId)
	{
		global $wpdb;
		$table = TransactionBucket::table();
		$query = $wpdb->prepare("SELECT * FROM {$table} WHERE paymentId = %s ORDER BY id DESC LIMIT 1", array($paymentId));
		return $wpdb->get_row($query);
	}

	public static function insert(Bucket $data)
	{
		global $wpdb;
		$result = $wpdb->insert(TransactionBucket::table(), (array) $data);
		if($result){ $wpdb->flush(); }
		// TEMPORAL
		try {
			error_log("AZUL_processPayment_INSERTING_BCKET: {$wpdb->insert_id}");
			error_log(json_encode($data));
		} catch (\Throwable $th) {}
		// END TEMPORAL
		return $wpdb->insert_id;
	}

	public static function update($id, $status)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("status" => $status), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}

	public static function setPending($id)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("status" => 'PENDING'), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}

	public static function setComplete($id)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("status" => 'COMPLETE'), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}

	public static function addPaymentData($id, $paymentState)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("paymentData" => serialize($paymentState)), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}

	public static function addPaymentID($id, $paymentId)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("paymentId" => $paymentId), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}

	public static function setRejected($id)
	{
		global $wpdb;
		$result = $wpdb->update(TransactionBucket::table(), array("status" => 'REJECTED'), array("id" => $id));
		if($result){ $wpdb->flush(); }
		return $result;
	}
}
