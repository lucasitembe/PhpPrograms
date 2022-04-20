<?php

function SendSMS($receiver = '255653183020', $message = 'No Message!'){
	$url = 'http://bulksms.vsms.net/eapi/submission/send_sms/2/2.0';
	$msisdn = $receiver;
	$data = 'username=victorslangula&password=champs12&message='.urlencode($message).'&msisdn='.urlencode($msisdn);
	$response = do_post_request($url, $data);
	//print $response;
	
	echo $response;
}

	function do_post_request($url, $data, $optional_headers = 'Content-type:application/x-www-form-urlencoded') {
		$params = array('http'      => array(
			'method'       => 'POST',
			'content'      => $data,
			));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
		$ctx = stream_context_create($params);
		$return = @file_get_contents($url, false, $ctx);
		if ($return === false) {
			//print "Problem reading data from $url, No status returned\n";
			print "<div style='background-color:#037DB0;padding:6px;text-align:center;color:yellow;margin-bottom:10px;'>An error has occured.Check if you have internet connection and try again.</div>";
		}else{
		
		$ret = substr($return, 0, 7);//0|IN_PR  //24|inva
		if($ret == '0|IN_PR'){
			return "<div style='background-color:#037DB0;padding:6px;text-align:center;color:yellow;margin-bottom:10px;'>SMS sent successfully</div>";
		} elseif($ret == '24|inva') {
			return "<div style='background-color:#037DB0;padding:6px;text-align:center;color:yellow;margin-bottom:10px;'>SMS not sent, Wrong Number</div>";
		} else {
			return "<div style='background-color:#037DB0;padding:6px;text-align:center;color:yellow;margin-bottom:10px;'>Error</div>";			
		}
		}
	}

?>


