<?php
function getCode($lastCode){
	$i = strlen($lastCode)-1;
	while($i>=0){
		$inc = Increment($lastCode[$i]);
		$lastCode[$i] = $inc;

		if($inc===0){
			$i --;
		}else{
			break;
		}
	}
	return $lastCode;
}

function Increment($possition){
	$alphabets = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	if(in_array($possition,$alphabets)){
		$key = array_search($possition,$alphabets);
		if($key===25){
			return 0;
		}
		else{
			$key++;
			return $alphabets[$key];
		}
	}else{
		if($possition<9){
			$possition++;
			return $possition;
		}elseif($possition==9){
			return 'A';
		}
	}
}

/*
Not Complete
function getNCode($N){
	$output = '';
	$possition = $N;
	do{
		$possition = floor($possition/35);
		$reminder = $N-($possition*35);

		$output = $output.getNVal($possition);
		$possition = $reminder;

		if($reminder==0){
			break;	
		}
	}while($reminder>=0);
}

function getNVal($nth){
	$alphabets = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	$n = $nth%35;
	if((9-$n)>=0 && $nth !=35){
		return $n;
	}elseif($nth==35){
		return 'Z';
	}else{
		return $alphabets[($n-9)];
	}
}
*/
?>