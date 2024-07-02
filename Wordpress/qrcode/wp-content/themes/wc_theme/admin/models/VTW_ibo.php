<?php
class WCibo
{

    public $source = "";
    public $payload = "";
    public $at = "";
    public $event_payment_id = 0;
    public $hash = "";
    public $log = "";

    public static function table_name()
    {
        global $wpdb;
        return "{$wpdb->prefix}ibo";
    }

    public static function getAll()
    {
        global $wpdb;
        $table_name = WCibo::table_name();
        return $wpdb->get_results("SELECT * FROM {$table_name}");
    }

    public static function countPetPending()
    {
        global $wpdb;
        $table_name = WCibo::table_name();
        return $wpdb->get_row("SELECT count(id) FROM {$table_name}");
    }

    public static function getById($id)
    {
        global $wpdb;
        $table_name = WCibo::table_name();
        return $wpdb->get_row("SELECT * FROM {$table_name} WHERE id = {$id}");
    }

    public static function getByIBO($ibo)
    {
        global $wpdb;
        $table_name = WCibo::table_name();
        return $wpdb->get_row("SELECT * FROM {$table_name} WHERE abo_num = {$ibo}");
    }

    public static function insert(IBOData $data)
    {
        global $wpdb;
        $toInsert = array(
            "abo_entry_date" => $data->abo_entry_date,
            "abo_expire_date" => $data->abo_expire_date,
            "abo_num" => $data->abo_num,
            "loa_name" => $data->loa_name,
            "account_name" => $data->account_name,
            "contry_code" => $data->country_code,
            "currency_code" => $data->currency_code,
            "sponsor" => $data->sponsor,
            "status_code" => $data->status_code,
        );
        $wpdb->insert(WCibo::table_name(), $toInsert, null);
        return $wpdb->insert_id;
    }

    public static function update(IBOData $data, $id)
    {
        global $wpdb;

        $toUpdate = array(
            "abo_entry_date" => $data->abo_entry_date,
            "abo_expire_date" => $data->abo_expire_date,
            "abo_num" => $data->abo_num,
            "loa_name" => $data->loa_name,
            "account_name" => $data->account_name,
            "contry_code" => $data->country_code,
            "currency_code" => $data->currency_code,
            "sponsor" => $data->sponsor,
            "status_code" => $data->status_code,
        );

        $wpdb->update(
            WCibo::table_name(),
            $toUpdate,
            array('id' => $id),
            null
        );

        return $id;
    }
}


class IBOData
{
    public $abo_entry_date = '';
    public $abo_expire_date = '';
    public $abo_num = '';
    public $loa_name = '';
    public $account_name = '';
    public $country_code = '';
    public $currency_code = '';
    public $sponsor = '';
    public $status_code = '';
}
