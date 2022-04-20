<?php
require("constants.php"); 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceManager
 *
 * @author arashid
 */
class ServiceManager
{

public function AuthorizeCard($CardNo,$VisitTypeID,$ReferralNo, $Remarks)
    {

        $authorizationHeader=$this->getAuthenticationHeader(AUTHORIZATION_TOKEN_END_POINT, username,password);
        $request=AUTHORIZATION_SERVICE_BASE_URL.'verification/AuthorizeCard?CardNo='.$CardNo.'&VisitTypeID='.$VisitTypeID.'&ReferralNo='.$ReferralNo.'&Remarks='.$Remarks;

        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }

        public function Authenticate($cardNo,$facility_code,$userLoggedIn)  //cardno,facility,user_logged,
    {

        $request=base_url.'verification/authorizecard?CardNo='.$cardNo.'&FacilityCode='.$facility_code.'&UserName='.$userLoggedIn;

        //echo $request;exit;
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=public_key.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, private_key,$raw=true);
        $signature = base64_encode($hash);
        $amx=public_key.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        //$StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }
    public function getAuthenticationHeader($tokenUrl,$username, $password)
    {

        // Construct the body for the STS request
        $authenticationRequestBody = 'grant_type=password&username='.$username.'&password='.$password;

        //Using curl to post the information to STS and get back the authentication response
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // Get the response back as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // Mark as Post request
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the parameters for the request
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $authenticationRequestBody);

        // By default, HTTPS does not work with curl.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // read the output from the post request
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        // decode the response from sts using json decoder
        $tokenOutput = json_decode($output);
        //echo('Amour testing')
        //echo($tokenOutput);
        return 'Authorization:' . $tokenOutput->{'token_type'}.' '.$tokenOutput->{'access_token'};

    }
        public function GetDetails($CardNo)
    {
            //AUTHORIZATION_SERVICE_BASE_URL
        $request=base_url.'verification/getverificationdetails?CardNo='.$CardNo;
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=public_key.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, private_key,$raw=true);
        $signature = base64_encode($hash);
        $amx=public_key.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }
	public function SubmitFolios($data_string)
    {
        $request=TEST_CLAIMS_SERVICE_BASE_URL.'claims/SubmitFolios';
        $authorizationHeader=$this->getAuthenticationHeader(TEST_CLAIMS_TOKEN_END_POINT,username,password);
        
        
        // $request=CLAIM_SUBMISSION_END_POINT;
        // $authorizationHeader=$this->getAuthenticationHeader(CLAIM_TOKEN_END_POINT,username,password);
        
        $ch = curl_init($request);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  $authorizationHeader,
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string)
           ));
        $result = curl_exec($ch);
        $result = trim($result,"\"");
        $StatusCode =  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $array_data = array();
        $array_data['StatusCode'] = $StatusCode;
        $array_data['Message'] = $result;
        $result = json_encode($array_data);

        curl_close($ch);

        return $result;
    }

    public function GetPricePackage($FacilityCode)
    {
        $request=CLAIMS_SERVICE_BASE_URL.'packages/GetPricepackage?FacilityCode='.$FacilityCode;

        $authorizationHeader=$this->getAuthenticationHeader(CLAIMS_TOKEN_END_POINT,username,password);
        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }
    public function ServiceVerification($CardNo,$RefferenceNo,$ItemCode)
        {

            $authorizationHeader=$this->getAuthenticationHeader(AUTHORIZATION_TOKEN_END_POINT, username,password);

            //$request=AUTHORIZATION_SERVICE_BASE_URL.'verification/AuthorizeCard?CardNo='.$CardNo.'&VisitTypeID='.$VisitTypeID.'&ReferralNo='.$ReferralNo;
            $request = SERVICE_VERIFICATION_END_POINT.'?CardNo='.$CardNo.'&ReferenceNo='.$RefferenceNo.'&ItemCode='.$ItemCode;

            $ch = curl_init($request);
            $request_method = 'GET';
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            $authorizationHeader,
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
                ));
            $result = curl_exec($ch);
            $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

            if($StatusCode == 200){
                $array_data = json_decode($result,true);
                $array_data['StatusCode'] = $StatusCode;
                $result = json_encode($array_data);
            }else{
                $array_data = array();
                $array_data['StatusCode'] = $StatusCode;
                $array_data['Message'] = $result;
                $result = json_encode($array_data);
            }

            curl_close($ch);
            return $result;
        }

public function GetPricePackageExcluded($FacilityCode)
    {
        $request=CLAIMS_SERVICE_BASE_URL.'packages/GetPricepackage?FacilityCode='.$FacilityCode;
        // $request = TEST_CLAIMS_SERVICE_BASE_URL.'/Packages/GetPricePackageWithExcludedServices?FacilityCode='.$FacilityCode;

        $authorizationHeader=$this->getAuthenticationHeader(CLAIMS_TOKEN_END_POINT,username,password);
        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }

    public function GetFacilities()
    {
        $request=CLAIMS_SERVICE_BASE_URL.'facilities/GetFacilities';

        $authorizationHeader=$this->getAuthenticationHeader(CLAIMS_TOKEN_END_POINT,username,password);
        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }

    public function GetDiseases()
    {
        $request=CLAIMS_SERVICE_BASE_URL.'reference/GetDiseases';

        $authorizationHeader=$this->getAuthenticationHeader(CLAIMS_TOKEN_END_POINT,username,password);
        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }

	    function GetSubmittedClaims($Hospital_Details){

        $authorizationHeader=$this->getAuthenticationHeader(TEST_CLAIMS_TOKEN_END_POINT, username,password);
        //return $authorizationHeader;
        $request=TEST_CLAIMS_SERVICE_BASE_URL.'claims/GetSubmittedClaims?'.$Hospital_Details;
        //return $request;
        $ch = curl_init($request);
        $request_method = 'GET';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

           $authorizationHeader,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
             ));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }
}
