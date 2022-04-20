<?php
ini_set('max_execution_time', 3600);
$orgUnit = '';//$_SESSION['configData']['OrganUnitDHIS2'];//"GRDYT0QagNn";
$dataSet='';

$configs=mysqli_query($conn,"select * from tbl_config where configname IN ('OrganUnitDHIS2','OpdDataSetIdDHIS2')") or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($configs)) {
   if($row['configname']=='OrganUnitDHIS2'){
       $orgUnit=$row['configvalue'];
   } elseif($row['configname']=='OpdDataSetIdDHIS2'){
       $dataSet=$row['configvalue'];
   }
}


$dhis_data_array = array();

function setDHISopdData($data,$data_type = array('report_type'=>'','dataset'=>'')){ //'v6wdME3ouXu'
   
    $dataSet =$data_type['dataset'];
	/*
	parameter format example
	$data = array(
	'Name' => '',
	'dataElement' => ,
	'period' => '',
	'dataValues' => array('','','','','','','','','','');
	);*/

	$dataElement = array(
	'Name' => $data['Name'], 
	'period' => $data['period'],
	'orgUnit' => $data['orgUnit'],
	'dataSet' => $dataSet,
	'dataValues' => array(
		array(
			'categoryOptionComboName' => 'Umri chini ya mwezi 1, ME, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "xo7RVsgBkpT",
			'value' => $data['dataValues'][0],
		),
		array(
			'categoryOptionComboName' => 'Umri chini ya mwezi 1, KE, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "Ak7SvGtlnWp",
			'value' => $data['dataValues'][1],
		),
		array(
			'categoryOptionComboName' => 'Umri mwezi 1 hadi umri chini ya mwaka 1, ME, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "n15V8E7ZLT9",
			'value' => $data['dataValues'][2],
		),
		array(
			'categoryOptionComboName' => 'Umri mwezi 1 hadi umri chini ya mwaka 1, KE, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "R9Ka4Hd70Wh",
			'value' => $data['dataValues'][3],
		),
		array(
			'categoryOptionComboName' => 'Umri mwaka 1 hadi umri chini ya miaka 5, ME, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "PSQ2DsGun8c",
			'value' => $data['dataValues'][4],
		),
		array(
			'categoryOptionComboName' => 'Umri mwaka 1 hadi umri chini ya miaka 5, KE, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "kSxbMW8Lmsd",
			'value' => $data['dataValues'][5],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 5 hadi umri chini ya miaka 60, ME, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "ddmrU8qJa7L",
			'value' => $data['dataValues'][6],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 5 hadi umri chini ya miaka 60, KE, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "sgG28zSclZm",
			'value' => $data['dataValues'][7],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 60 au zaidi, ME, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "aYgz5mh2cbx",
			'value' => $data['dataValues'][8],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 60 au zaidi, KE, OPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "ayVLc9to6xY",
			'value' => $data['dataValues'][9],
		),
	),
);
	return $dataElement;
}



function setDHISipdData($data,$data_type = array('report_type'=>'','dataset'=>'')){ //'v6wdME3ouXu'
   
    $dataSet =$data_type['dataset'];
	/*
	parameter format example
	$data = array(
	'Name' => '',
	'dataElement' => ,
	'period' => '',
	'dataValues' => array('','','','','','','','','','');
	);*/

	$dataElement = array(
	'Name' => $data['Name'], 
	'period' => $data['period'],
	'orgUnit' => $data['orgUnit'],
	'dataSet' => $dataSet,
	'dataValues' => array(
		array(
			'categoryOptionComboName' => 'Umri chini ya mwezi 1, ME, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "oliDewHdUdd",
			'value' => $data['dataValues'][0],
		),
		array(
			'categoryOptionComboName' => 'Umri chini ya mwezi 1, KE, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "uuidY4WdJml",
			'value' => $data['dataValues'][1],
		),
		array(
			'categoryOptionComboName' => 'Umri mwezi 1 hadi umri chini ya mwaka 1, ME, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "iaPGviZZIky",
			'value' => $data['dataValues'][2],
		),
		array(
			'categoryOptionComboName' => 'Umri mwezi 1 hadi umri chini ya mwaka 1, KE, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "e0RdqwlP1Xj",
			'value' => $data['dataValues'][3],
		),
		array(
			'categoryOptionComboName' => 'Umri mwaka 1 hadi umri chini ya miaka 5, ME, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "R33m4bJ5OcC",
			'value' => $data['dataValues'][4],
		),
		array(
			'categoryOptionComboName' => 'Umri mwaka 1 hadi umri chini ya miaka 5, KE, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "dtdut9EncYH",
			'value' => $data['dataValues'][5],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 5 hadi umri chini ya miaka 60, ME, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "MQKsgFxCtJ7",
			'value' => $data['dataValues'][6],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 5 hadi umri chini ya miaka 60, KE, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "awkJLkKHr7a",
			'value' => $data['dataValues'][7],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 60 au zaidi, ME, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "o9Oj5Cjekej",
			'value' => $data['dataValues'][8],
		),
		array(
			'categoryOptionComboName' => 'Umri miaka 60 au zaidi, KE, IPD',
			'dataElement' => $data['dataElement'],
			'categoryOptionCombo' => "ZU3sKDB9i2o",
			'value' => $data['dataValues'][9],
		),
	),
);
	return $dataElement;
}

function sendDataToDHIS($data){
$url = "http://41.217.202.50:8080/dhis/api/dataValueSets";	
// foreach ($dhis_data as $data) {
// 	$data = json_encode($data);
	try {	
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_USERPWD, "gpitg:Gpitg@2017");
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					));
				$server_output = curl_exec ($ch);
				curl_close ($ch);				
				
		}catch (Exception $e) {
				echo 'Sorry! Caught exception: ',  $e->getMessage(), "\n";
		}
			
		// further processing ....
		return json_decode($server_output);
		//if ($ch) { echo 'Successfully Imported'; } else { echo 'Sorry! Fail to import.';}
}