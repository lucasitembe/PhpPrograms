<?php
	include '../includes/connection.php';
	$month = $_POST['month'];
	$year = $_POST['year'];
	$FirstFolio = $_POST['FirstFolio'];
	$LastFolio = $_POST['LastFolio'];

	/*
		PART ONE
		THIS PART GENERATES CLAIM PDF FILES
	*/

	//The name of the folder.
	$folder = 'temporary_files';

	//Get previous generated pdf files
	$previousFiles = glob('../PDF_CLAIMS/*');

	foreach($previousFiles as $file){
	    //Make sure that this is a file and not a directory.
	    if(is_file($file)){
		//Delete file
		unlink('../PDF_CLAIMS/'.$file);
	    }
	}

	/*Getting all files using the bill number*/
	$query = mysqli_query($conn,"SELECT Bill_ID from tbl_claim_folio WHERE claim_month = '$month' AND claim_year = '$year' AND Folio_No BETWEEN '$FirstFolio' AND '$LastFolio' ");

	while($row = mysqli_fetch_assoc($query))
	{
		$q = $row['Bill_ID'];
		//Read the encoded file contencts
		$conts=  file_get_contents("../NHIF_FILE/".$q."_claim_file.txt");

		//Decode the base64 encoded contents
		$data = base64_decode($conts);

		//Generating pdf file and save it to PDF_CLAIMS folder
		$claim = fopen('../PDF_CLAIMS/'.$q.'.pdf','wb') or die("Unable to open file!");;
		fwrite($claim,  $data);
		fclose($claim);


		//Gathering the files with error
		if($conts == ''){
			$claim_error = fopen('../PDF_CLAIMS_ERROR/'.$q.'.pdf','wb') or die("Unable to open file!");;
			fwrite($claim_error,  $data);
			fclose($claim_error);
			unlink('../PDF_CLAIMS/'.$q.'.pdf');
		}
	}

	/*
		END OF PART ONE
	*/

	/*
		PART TWO
		THIS PART COMBINE MULTIPLE PDF FILES INTO ONE FILE
	*/
	unlink("../MERGED/merged_claim_file.pdf");
	$fileList = glob('../PDF_CLAIMS/*');
	$allFiles = "";
	//Loop through the array that glob returned.
	foreach($fileList as $filename){

		$allFiles .=$filename ." ";

	}

	//Generate single pdf file
	$result = shell_exec("pdftk ".$allFiles." cat output ../MERGED/merged_claim_file.pdf
	");

	/*
		END OF PART TWO
	*/


?>
