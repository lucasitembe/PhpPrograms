<?php
	session_start();
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Control = 0;

	$initial = '';
	$temp = 0;
	$code = '01';
	for ($a=0; $a < 10; $a++) {
		$initial = (($a*$a*$a)%11);

		for ($b=0; $b < 10; $b++) {
			$code = $initial.(($b*$b*$b)%11);
			for($c=0; $c < 10; $c++){
				$final = $code.(($c*$c*$c)%11);
				for($d = 0; $d < 20; $d++){
					if(strlen($d) == 1){
						$default = $final.'0'.$d;
					}else{
						$default = $final.$d;
					}
					for($e=0; $e<10; $e++){
						$last = $default.(($e*$e*$e)%11);
						for($f=0; $f<10; $f++){
							$num = '01'.$last.(($f*$f*$f)%11);
						}
						$result = mysqli_query($conn,"insert into tbl_crdb_invoice_numbers(Invoice_Number) values('$num')");
						if($result){
							$Control += 1;
						}
					}
				}
			}
		}
	}
	echo $Control.' Invoice Numbers Created';
?>