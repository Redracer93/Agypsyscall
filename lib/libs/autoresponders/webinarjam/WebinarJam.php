<?php
/**
 * Class WebinarJam
 * Implements the WebinarJam API as documented
 * https://s3.amazonaws.com/webinarjam/files/WebinarJamAPI.pdf
 */
class WebinarJam {
    public static $API_URL = 'https://app.webinarjam.com/api/v2/';
    public static $CURL_OPTIONS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
    );
    private $_apiKey;
    public function __construct($apiKey) {
        $this->_apiKey = $apiKey;
    }
    public function getWebinars() {
        return $this->authenticatedCall('webinars');
    }
    public function getWebinar($webinarId) {
        return $this->authenticatedCall('webinar', ['webinar_id' => $webinarId]);
    }
    public function addToWebinar($webinarId, $name, $email, $schedule, $ipAddress=null, $countryCode=null, $phone=null) {
        $params = ['webinar_id' => $webinarId, 'name' => $name, 'email' => $email, 'schedule' => $schedule];

        if ($ipAddress != null) {
            $params['ip_address'] = $ipAddress;
        }

        if($countryCode != null) {
            $params['country_code'] = $countryCode;
        }

        if($phone != null) {
            $params['phone'] = $phone;
        }
        return $this->authenticatedCall('register', $params);
    }
    private function authenticatedCall($url, $params = array()) {
        $ch = curl_init(self::$API_URL . $url);
        $opts = self::$CURL_OPTIONS;
        if(empty($this->_apiKey)) {
            throw new Exception('Must specify valid API key');
        }
        $params['api_key'] = $this->_apiKey;
        curl_setopt_array($ch, $opts);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception($error);
        }
        curl_close($ch);
        $isReturnArray = true;
        $jsonResults = json_decode($result, $isReturnArray);
        if(!is_array($jsonResults)) {
            throw new Exception($result);
        }
        return $jsonResults;
    }
}
?>