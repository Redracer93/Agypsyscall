<?php

// define('GOTO_WEBINAR_API_KEY','ab1d62f29f00fe8584cde77dfb5f8ebd');

class OAuth_En{

protected $_accessToken;
protected $_userId;
protected $_organizerKey;
protected $_refreshToken;
protected $_expiresIn;

public function getAccessToken(){
    return $this->_accessToken;
}

public function setAccessToken($token){
    $this->_accessToken = $token;
}

public function getUserId(){
    return $this->_userId;
}

public function setUserId($id){
    $this->_userId = $id;
}   

public function getOrganizerKey(){
    return $this->_organizerKey;
}

public function setOrganizerKey($key){
    $this->_organizerKey = $key;
}

public function getRefreshToken(){
    return $this->_refreshToken;
}

public function setRefreshToken($token){
    $this->_refreshToken = $token;
}

public function getExpiresIn(){
    return $this->_expiresIn;
}

public function setExpiresIn($expiresIn){
    $this->_expiresIn = $expiresIn;
}   


}

class OAuth_Db{
function getToken(){

}       
}

class OAuth{
protected $_redirectUrl;
protected $_OAuthEnObj;
protected $_curlHeader = array();
protected $_apiResponse;
protected $_apiError;
protected $_apiErrorCode;
protected $_apiRequestUrl;
protected $_apiResponseKey;
protected $_accessTokenUrl;
protected $_webinarId;
protected $_registrantInfo = array();
protected $_apiRequestType;
protected $_apiPostData;

public function __construct(OAuth_En $oAuthEn){
    $this->_OAuthEnObj = $oAuthEn;  
}

public function getOAuthEntityClone(){
    return clone $this->_OAuthEnObj;    
}

public function getWebinarId(){
    return $this->_webinarId;
}

public function setWebinarId($id){
    $id = (int)$id;
    $this->_webinarId = empty($id) ? 0 : $id;
}

public function setApiErrorCode($code){
    $this->_apiErrorCode = $code;   
}

public function getApiErrorCode(){
    return $this->_apiErrorCode;    
}   

public function getApiAuthorizationUrl(){
    $api_key = $this->getApiKey();
    return 'https://api.citrixonline.com/oauth/authorize?client_id='.$api_key.'&redirect_uri='.$this->getRedirectUrl(); 
}

public function getApiKey(){
    $plugin_options = survsnt_get_plugin_options();
    return $plugin_options['webinars']['gotowebinar']['apikey'];
    // return  GOTO_WEBINAR_API_KEY;
}

public function getApiRequestUrl(){
    return  $this->_apiRequestUrl;
}

public function setApiRequestUrl($url){
    $this->_apiRequestUrl = $url;
}

public function setRedirectUrl($url){
    $this->_redirectUrl = urlencode($url);  
}

public function getRedirectUrl(){
    return $this->_redirectUrl; 
}

public function setCurlHeader($header){
    $this->_curlHeader = $header;   
}

public function getCurlHeader(){
    return $this->_curlHeader;  
} 

public function setApiResponseKey($key){
    $this->_apiResponseKey = $key;
}

public function getApiResponseKey(){
    return $this->_apiResponseKey;
}

public function setRegistrantInfo($arrInfo){
    $this->_registrantInfo = $arrInfo;  
}

public function getRegistrantInfo(){
    return $this->_registrantInfo;  
}

public function authorizeUsingResponseKey($responseKey){
    $this->setApiResponseKey($responseKey);
    $this->setApiTokenUsingResponseKey();
}

protected function setAccessTokenUrl(){
    $url = 'https://api.citrixonline.com/oauth/access_token?grant_type=authorization_code&code={responseKey}&client_id={api_key}';
    $url = str_replace('{api_key}', $this->getApiKey(), $url);
    $url = str_replace('{responseKey}', $this->getApiResponseKey(), $url);
    $this->_accessTokenUrl = $url;
}

protected function getAccessTokenUrl(){
    return $this->_accessTokenUrl;  
}

protected function resetApiError(){
    $this->_apiError = '';  
}

public function setApiTokenUsingResponseKey(){
    //set the access token url
    $this->setAccessTokenUrl();

    //set the url where api should go for request
    $this->setApiRequestUrl($this->getAccessTokenUrl());

    //make request
    $this->makeApiRequest();

    if($this->hasApiError()){
        echo $this->getApiError();
    }else{
        //if api does not have any error set the token
        // echo $this->getResponseData();
        $responseData = json_decode($this->getResponseData());
        $this->_OAuthEnObj->setAccessToken($responseData->access_token);
        $this->_OAuthEnObj->setOrganizerKey($responseData->organizer_key);
        $this->_OAuthEnObj->setRefreshToken($responseData->refresh_token);
        $this->_OAuthEnObj->setExpiresIn($responseData->expires_in);
    }
}

function hasApiError(){
    return $this->getApiError() ? 1 : 0;
}

function getApiError(){
    return $this->_apiError;
}

function setApiError($errors){
    return $this->_apiError = $errors;
}

function getApiRequestType(){
    return $this->_apiRequestType;
}

function setApiRequestType($type){
    return $this->_apiRequestType = $type;
}   

function getResponseData(){
    return $this->_apiResponse;
}

function setApiPostData($data){
    return $this->_apiPostData = $data;
}   

function getApiPostData(){
    return $this->_apiPostData;
}   

function makeApiRequest(){
    $header = array();

    $this->getApiRequestUrl();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_URL, $this->getApiRequestUrl());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if($this->getApiRequestType()=='POST'){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getApiPostData());  
    }

    if($this->getCurlHeader()){
        $headers = $this->getCurlHeader();
    }else{
        $headers = array(
                "HTTP/1.1",
                "Content-type: application/json",
                "Accept: application/json",
                "Authorization: OAuth oauth_token=".$this->_OAuthEnObj->getAccessToken()
            );  
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

    $data = curl_exec($ch);
    $validResponseCodes = array(200,201,409);
    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

    $this->resetApiError();

    if (curl_errno($ch)) {
        $this->setApiError(array(curl_error($ch)));
    } elseif(!in_array($responseCode, $validResponseCodes)){
        if($this->isJsonString($data)){
            $data = json_decode($data);
        }

        $this->setApiError($data);
        $this->setApiErrorCode($responseCode);
    }else {
        $this->_apiResponse = $data;
        $_SESSION['gotoApiResponse'] = $this->getResponseData();
        curl_close($ch);
    }
}

function isAuthorizationRequiredAgain(){
    $arrAuthorizationRequiredCodes = array(400,401,403,500);
    $isAuthRequired = 0;
    $error = $this->getApiError();
    $responseCode = $this->getApiErrorCode();

    //we might have to add more exception in this condition
    if(in_array($responseCode, $arrAuthorizationRequiredCodes)){
        if($responseCode==400 && is_object($error)){    //because for 400 error sometime one needs to authenticate again
            foreach($error as $single){
                $pos = strpos($single,'Authorization');
                if($pos!==false){
                    $isAuthRequired = 1;
                }
            }
        }else{
            $isAuthRequired = 1;    
        }
    }

    return $isAuthRequired;
}

function getWebinars(){
    $url = 'https://api.citrixonline.com/G2W/rest/organizers/'.$this->_OAuthEnObj->getOrganizerKey().'/webinars';
    $this->setApiRequestUrl($url);
    $this->setApiRequestType('GET');
    $this->makeApiRequest();

    if($this->hasApiError()){
        return null;    
    }

    $webinars = json_decode($this->getResponseData());

    return $webinars;
}

function getWebinar(){
    if(!$this->getWebinarId()){
        $this->setApiError(array('Webinar id not provided'));               
        return null;
    }

    $this->setApiRequestType('GET');
    $url = 'https://api.citrixonline.com/G2W/rest/organizers/'.$this->_OAuthEnObj->getOrganizerKey().'/webinars/'.$this->getWebinarId();
    $this->setApiRequestUrl($url);
    $this->makeApiRequest();

    if($this->hasApiError()){
        return null;    
    }

    $webinar = json_decode($this->getResponseData());

    return $webinar;
}

function getUpcomingWebinars(){
    $url = 'https://api.citrixonline.com/G2W/rest/organizers/'.$this->_OAuthEnObj->getOrganizerKey().'/upcomingWebinars';
    $this->setApiRequestUrl($url);
    $this->setApiRequestType('GET');
    $this->makeApiRequest();

    if($this->hasApiError()){
        return null;    
    }

    $webinars = json_decode($this->getResponseData());

    return $webinars;       
}

function createRegistrant(){
    if(!$this->getWebinarId()){
        $this->setApiError(array('Webinar id not provided'));               
        return null;
    }

    if(!$this->getRegistrantInfo()){
        $this->setApiError(array('Registrant info not provided'));              
        return null;
    }

    $this->setApiRequestType('POST');   
    $this->setApiPostData(json_encode($this->getRegistrantInfo())); 
    $url = 'https://api.citrixonline.com/G2W/rest/organizers/'.$this->_OAuthEnObj->getOrganizerKey().'/webinars/'.$this->getWebinarId().'/registrants';

    $this->setApiRequestUrl($url);
    $this->makeApiRequest();

    if($this->hasApiError()){
        return null;    
    }

    $webinar = json_decode($this->getResponseData());

    return $webinar;
}

function getWebinarRegistrantsFields(){
    if(!$this->getWebinarId()){
        $this->setApiError(array('Webinar id not provided'));               
        return null;
    }
    $url = 'https://api.citrixonline.com/G2W/rest/organizers/'.$this->_OAuthEnObj->getOrganizerKey().'/webinars/'.$this->getWebinarId().'/registrants/fields';
    $this->setApiRequestUrl($url);
    $this->setApiRequestType('GET');
    $this->makeApiRequest();

    if($this->hasApiError()){
        return null;    
    }

    $registrantFields = json_decode($this->getResponseData());

    return $registrantFields;   

}

function isJsonString($string){
    $isJson = 0;
    $decodedString = json_decode($string);
    if(is_array($decodedString) || is_object($decodedString))
        $isJson = 1;    

    return $isJson;
}
}