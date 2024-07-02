<?php
include_once(TEMPLATEPATH . "/admin/models/VTW_ibo.php");

class AmwayGateWay
{

    private $AMWAY_API = "https://prodapi.apigtwy.amer.amway.net";
    private $AMWAY_AUTHORIZATION = "S19KRUNTdDVvQ2hyeGV5TjR5TDZTcm45aG5zYTpCX3d0OXBqU1VYSUpYNFlnQ2ptcVAzWHdyamNh";
    private $AMWAY_LOA = "loa-3d245aeb";
    private $AMWAY_AFF = "010";
    private static $instance = null;
    private AccessTokenImp $accessToken;
    private WCIbo $WCIbo;


    public function __construct(string $AMWAY_AUTHORIZATION = null)
    {
        if ($AMWAY_AUTHORIZATION) {
            $this->AMWAY_AUTHORIZATION = $AMWAY_AUTHORIZATION;
        }
        $this->WCIbo = new WCIbo();
    }

    function searchIBO($ibo)
    {
        if (!$ibo) return false;
        $match = $this->WCIbo->getById($ibo);
    }

    function getToken()
    {
        $response = new stdClass();
        $params = array(
            'grant_type' => 'client_credentials',
            'scope' => 'openid'
        );
        $postFields = http_build_query($params);
        try {
            $CURLResponse = $this->__callApi("{$this->AMWAY_API}/token", $postFields, "POST");
            if ($CURLResponse->error) {
                throw new Exception($CURLResponse->error);
            } else {
                $this->accessToken = new AccessTokenImp(
                    $CURLResponse->data->access_token,
                    $CURLResponse->data->expires_in,
                    $CURLResponse->data->id_token,
                    $CURLResponse->data->scope,
                    $CURLResponse->data->token_type
                );
                $response->data = $CURLResponse->data;
            }
        } catch (Exception $e) {
            $response->error = $e;
        }

        return $response;
    }

    function getIBOInfo($ibo)
    {

        $response = new stdClass();
        $match = $this->WCIbo->getByIBO($ibo);

        if ($match) {
            $response->data = $match;
        } elseif (!$match) {
            // Si no encontramos los datos localmente, hacemos la llamada a la API
            if (!isset($this->accessToken)) {
                $this->getToken();
            }

            $params = array(
                'reqAbo' => $ibo,
                'reqAff' => 10,
                'detailLevelCd' => 'FullDetail'
            );
            $postFields = http_build_query($params);

            try {
                $CURLResponse = $this->__callApi("{$this->AMWAY_API}/mdms-accounts/3.0.0/accounts/010-{$ibo}?" . $postFields, "", "GET");

                $response = new stdClass(); // Create response object

                if (isset($CURLResponse->data->errorMessage) || isset($CURLResponse->data->error)) {
                    if (isset($CURLResponse->data->errorMessage)) {
                        // Handle error message
                        $response->error = $CURLResponse->data->errorMessage;
                    } else {
                        // Handle error
                        $response->error = array(
                            "message" => $CURLResponse->data->error,
                            "code" => "UNKNOWN"
                        );
                    }
                } else {
                    // No error, process data
                    $account = isset($CURLResponse->data->account) ? $CURLResponse->data->account : null;
                    if ($account) {
                        $this->saveToLocal($account, $ibo);
                        $response->data = $this->WCIbo->getByIBO($ibo);
                    } else {
                        // Handle missing account
                        $response->error = array(
                            "message" => "Account data not found",
                            "code" => "ACCOUNT_NOT_FOUND"
                        );
                    }
                }
            } catch (Exception $e) {
                error_log("AMWAY_GATE_WAY_ERROR: {$e}");
                $response->error = $e;
            }
        }
        return $response;
    }

    private function saveToLocal($account, $ibo)
    {

        $iboInfo = $account->accountMst;
        $iboData = new IBOData();
        $iboData->abo_entry_date = $iboInfo->aboEntryDate;
        $iboData->abo_expire_date = $iboInfo->aboExpireDate;
        $iboData->abo_num = $ibo;
        $iboData->loa_name = $iboInfo->loaName;
        $iboData->account_name = $iboInfo->accountName;
        $iboData->country_code = $iboInfo->cntryCd;
        $iboData->currency_code = $iboInfo->currencyCd;
        $iboData->sponsor = $iboInfo->regdSpnIboNo;
        $iboData->status_code = $iboInfo->statusCd;
        return $this->WCIbo->insert($iboData);
    }

    private function __callApi(string $url, $postFields, string $method = 'POST')
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->__getHeaders());

        $rsp = curl_exec($curl);
        $error = curl_error($curl);

        $response = new StdClass();
        $response->data = json_decode($rsp);
        $response->error = $error || false;

        curl_close($curl);

        return $response;
    }

    private function __getHeaders(string $contentType = 'application/x-www-form-urlencoded')
    {
        if (isset($this->accessToken)) {
            return array(
                "Authorization: {$this->accessToken->getTokenType()} {$this->accessToken->getAccessToken()}",
                'Content-Type: application/json',
                'id_token: ' . $this->accessToken->getIdToken()
            );
        } else {
            return array(
                "Authorization: Basic {$this->AMWAY_AUTHORIZATION}",
                'Content-Type: ' . $contentType
            );
        }
    }

    public static function getInstance(string $AMWAY_AUTHORIZATION = null)
    {
        if (self::$instance == null) {
            self::$instance = new AmwayGateWay($AMWAY_AUTHORIZATION);
        }

        return self::$instance;
    }

    public function verifyIBO($ibo, $eventId)
    {
        $response = null;

        try {
            $iboInfo = $this->getIBOInfo($ibo);

            if (isset($iboInfo->error)) {
                $error = $iboInfo->error;
                throw new Exception($error->message, "-9006");
            }

            // all dates lower than 31 of augost of the current (year - 1) should be excluded
            $excludAfter = new DateTime(date('Y') - 1 . '-08-31');
            $entreDate = new DateTime($iboInfo->data->abo_entry_date);
            $isLowerThanMinDate = $entreDate < $excludAfter;
            $year = $entreDate->format('Y');
            $expirationDate = new DateTime($iboInfo->data->abo_expire_date);
            $expirationDate->setDate($isLowerThanMinDate ? $year : $year + 1, 12, 31);
            $formattedDate = $expirationDate->format('F j, Y');
            $currentDate = new DateTime();
            $payload = new stdClass();

            $payload->data = $iboInfo->data;

            if ($isLowerThanMinDate) {
                $payload->status = true;
                $payload->code = "EXPIRED";
                $payload->msg =
                    'El periodo gratis para este IBO ha caducádo ' . $formattedDate;
                $payload->validUntil = $expirationDate;
            } else {

                $payload->statusCode = 200;


                if ($expirationDate > $currentDate) {
                    $payload->status = true;
                    $payload->code = "VALID";
                    $payload->msg = "El IBO es válido hasta $formattedDate";
                    $payload->validUntil = $expirationDate;
                } else {
                    $payload->status = false;
                    $payload->code = "EXPIRED";
                    $payload->msg = 'El periodo gratis para este IBO ha caducádo ' . $formattedDate;
                    $payload->validUntil = $expirationDate;
                }
            }

            $response = $payload;
        } catch (Exception $e) {

            $response = array(
                "status" => false,
                "code" => $e->getCode() == "-9006" ? "IBO_NOT_FOUND" : "ERROR",
                "msg" => 'Amway returned and error: ' . $e->getMessage()
            );
        }

        return $response;
    }
}


interface AccessToken
{
    public function getAccessToken(): string;
    public function getExpiresIn(): int;
    public function getIdToken(): string;
    public function getScope(): string;
    public function getTokenType(): string;
}

class AccessTokenImp implements AccessToken
{
    private $access_token;
    private $expires_in;
    private $id_token;
    private $scope;
    private $token_type;

    public function __construct($access_token, $expires_in, $id_token, $scope, $token_type)
    {
        $this->access_token = $access_token;
        $this->expires_in = $expires_in;
        $this->id_token = $id_token;
        $this->scope = $scope;
        $this->token_type = $token_type;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

    public function getIdToken(): string
    {
        return $this->id_token;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getTokenType(): string
    {
        return $this->token_type;
    }
}
