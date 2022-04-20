<?php
	$operation = $_POST['operation'];

//	 $fing_p_host = $_SERVER['REMOTE_ADDR'];
	$fing_p_host = '192.168.43.4';
	//$card_port = 9195;
	$finger_port = 9196;

	switch($operation){
		case 'capture':{
			$message = '*BIOFINGER#'.$_POST['instruction'].'#';
			if($_POST['instruction'] == 'CAPTURE2'){
				$message = '*BIOFINGER#'.$_POST['instruction'].'#'.$_POST['sign1'].'#';
			}else if($_POST['instruction'] =='ENROLL'){
				$message = '*BIOFINGER#'.$_POST['instruction'].'#'.$_POST['sign1'].'#'.$_POST['sign2'].'#';
			}
			
			$socket = socket_create(AF_INET, SOCK_STREAM, 0);
			if ($socket) {
				$result = socket_connect($socket, $fing_p_host, $finger_port);
				if ($result) {
					if (socket_write($socket, $message, strlen($message))){
						$result = socket_read($socket, 1024);
						if ($result){
							$response['data'] = $result;
							if(strpos($result,'SUCCESS') !== false){
								$response['code'] = 200;
								$response['data'] = str_replace('SUCCESS - ','',$result);
							}else{
								$response['data'] = $result;
								$response['code'] = 400;
							}
							socket_close($socket);
						}
					}
				} else {
					$response['code'] = 300;
				}
			}	
			//$response['code'] = 200;
			//$response['data'] = $massage;
			
			echo json_encode($response);
		};break;
		case 'verify':{
			include("./includes/connection.php");
			$Registration_ID = $_POST['Registration_ID'];
			$finger_print_result = mysqli_query($conn,"SELECT finger_data FROM tbl_finger_print_details WHERE Registration_ID = $Registration_ID");
			$finger_data = mysqli_fetch_assoc($finger_print_result)['finger_data'];
			$message = "*BIOFINGER#VERIFY#".$finger_data."#";
							
			
			$socket = socket_create(AF_INET, SOCK_STREAM, 0);
			if ($socket) {
				 $result = socket_connect($socket, $fing_p_host, $finger_port)or die ("fail to connect");;
				if ($result) {
					if (socket_write($socket, $message, strlen($message))){
						 $result = socket_read($socket, 1024);
						if ($result){
							if(strpos($result,'SUCCESS') !== false){
								//verification successed
								$response['code'] = 200;
								//$response['data'] = str_replace('SUCCESS - ','',$result);
							}else{
								//$response['data'] = $result;
								// 400 verification fails
								$response['code'] = 400;
							}
							socket_close($socket);
						}
					}
				} else {
					// connection fails
					$response['code'] = 300;
				}
			}	
			echo json_encode($response);
			
		};break;
		case 'save':{
			
		};break;
                case 'verify_for_payment':{
                        include("./includes/connection.php");
                        $Registration_ID = $_POST['Registration_ID'];
			$finger_print_result = mysqli_query($conn,"SELECT finger_data FROM tbl_finger_print_details WHERE Registration_ID = $Registration_ID") or die(mysqli_error($conn));
			
                        $finger_data = mysqli_fetch_assoc($finger_print_result)['finger_data'];
			$message = "*BIOFINGER#VERIFY#".$finger_data."#";
							
			
			$socket = socket_create(AF_INET, SOCK_STREAM, 0);
			if ($socket) {
				 $result = socket_connect($socket, $fing_p_host, $finger_port)or die ("fail to connect");;
				if ($result) {
					if (socket_write($socket, $message, strlen($message))){
						 $result = socket_read($socket, 1024);
						if ($result){
							if(strpos($result,'SUCCESS') !== false){
								//verification successed
								echo "verification_successfull";
							}else{
								//$response['data'] = $result;
								echo "verification_fail";
							}
							socket_close($socket);
						}
					}
				} else {
					// connection fails
					echo "connection_fail";
				}
			}
                }
	}
?>
