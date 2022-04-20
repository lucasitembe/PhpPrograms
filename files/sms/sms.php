<span style="display:none;">
<?php

function SendSMS($receiver = '255653183020', $message = 'No Message!'){
	$url = 'http://bulksms.vsms.net/eapi/submission/send_sms/2/2.0';
	$msisdn = $receiver;
	$data = 'username=victorslangula&password=champs12&message='.urlencode($message).'&msisdn='.urlencode($msisdn);
	$response = do_post_request($url, $data);
	//print $response;

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
		$response = @file_get_contents($url, false, $ctx);
		if ($response === false) {
			print "Problem reading data from $url, No status returned\n";
		}
		//return $response;
	}

?>
</span>

