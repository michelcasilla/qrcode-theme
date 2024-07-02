<?php
include_once(TEMPLATEPATH . "/admin/models/VTW_Event_Payment_Zync.php");

class EntryInterface
{
    public $productId;
    public $iboId;
    public $quantity;
}

class WcAppApiConnect
{

    public static $HOST_URL = 'https://us-central1-worldcrowns-dev.cloudfunctions.net/wcApiGen2';

    public static function logFromEventPayment(EventPayment $payment)
    {
        
        $entry = new EntryInterface();
        $entry->productId = $payment->post_id;
        $entry->iboId = $payment->ibo;
        $entry->quantity = $payment->quantity;
        $value = get_field("correlatio_id", $entry->productId);
        if ($value) {
            $entry->productId = $value;
        } else {
            return false;
        }
        return WcAppApiConnect::addEntry($entry);
    }

    public static function addEntry(EntryInterface $data)
    {
        $response = new stdClass();
        $response->start_at = new DateTime();
        try {
            $payload = array(
                'productId' => $data->productId,
                'iboId' => $data->iboId,
                'quantity' => $data->quantity,
            );

            $host_url = WcAppApiConnect::$HOST_URL;
            $url = "$host_url/wctv/entry";
            $rsp = wp_remote_post($url, array(
                'body' => json_encode($payload),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    // 'Authorization' => 'Bearer ' . WcAppApiConnect::getAccessToken()
                ),
                'timeout' => 5
            ));

            if (is_wp_error($rsp)) {
                throw new Exception($rsp->get_error_message());
            }
            $response->status = 'SUCCESS';
            $response->message = 'Entry added';
            $response->data = json_decode(wp_remote_retrieve_body($rsp), true);
        } catch (Exception $e) {
            $response->status = 'ERROR';
            $response->message = $e->getMessage();
        } finally {
            $response->end_at = new DateTime();
            $model = new VTW_Event_Payment_Zync();
            $model->source = $url;
            $model->payload = json_encode($payload);
            $model->payment_id = $data->productId;
            $model->status = $response->status;
            $model->response = json_encode($response);
            $model->at = date('Y-m-d H:i:s');
            VTW_Event_Payment_Zync::add($model);
            return $response;
        }
    }
}
