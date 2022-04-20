<?php
ini_set('max_execution_time', 3600);
$dhis_data = array();

function setDHISopdData($data,$dataSet = 'v6wdME3ouXu'){
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

/*Variables*/
$orgUnit = "GRDYT0QagNn";
$period = "201001";

	$data1 = array(
	'Name' => 'Diarrhea With Some Dehydration',
	'dataElement' => 'zQQFpz3JT6g',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data2 = array(
	'Name' => 'Yellow Fever',
	'dataElement' => 'O9HZJ5frgiJ',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data3 = array(
	'Name' => 'Cholera in OPD',
	'dataElement' => 'CsYfybuaSgc',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data4 = array(
	'Name' => 'Influenza',
	'dataElement' => 'uAa5OgHFwud',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data5 = array(
	'Name' => 'Acute Flaccid Paralysis in OPD',
	'dataElement' => 'q3ELeLciuTh',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data6 = array(
	'Name' => 'Dysentery in OPD',
	'dataElement' => 'v6sdLtxvY1K',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data7 = array(
	'Name' => 'Measles in OPD',
	'dataElement' => 'qMHYsWwYgo6',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data8 = array(
	'Name' => 'Meningitis in OPD',
	'dataElement' => 'bC64bIily9n',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data9 = array(
	'Name' => 'Neonatal Tetanus in OPD',
	'dataElement' => 'cGVl8WkpBTL',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data10 = array(
	'Name' => 'Plague in OPD',
	'dataElement' => 'BwJsDwQayqN',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data11 = array(
	'Name' => 'Relapsing Fever - OPD',
	'dataElement' => 'ccIfQsrfWeL',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data12 = array(
	'Name' => 'Typhoid - OPD',
	'dataElement' => 'mcVhgPQtLLX',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data13 = array(
	'Name' => 'Rabies / Suspected Rabies bites',
	'dataElement' => 'Wa3cm09YbsP',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data14 = array(
	'Name' => 'Onchoceriasis - OPD',
	'dataElement' => 'k3TGMJ3ru5y',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data15 = array(
	'Name' => 'Trypanosomiasis',
	'dataElement' => 'RppK9y0dY08',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data16 = array(
	'Name' => 'Viral haemorrhagic fevers',
	'dataElement' => 'xCl76XUXHb9',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data17 = array(
	'Name' => 'Diarrhea with no dehydration',
	'dataElement' => 'NLBkuHemCAx',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data18 = array(
	'Name' => 'Diarrhea with severe dehydration',
	'dataElement' => 'f8yQ5FUAIx0',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);


	$data19 = array(
	'Name' => 'Schistosomiasis',
	'dataElement' => 'ctT1j57B2OL',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data20 = array(
	'Name' => 'Malaria BS +ve',
	'dataElement' => 'DWWNT5pcrWf',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data21 = array(
	'Name' => 'Malaria mRDT +ve',
	'dataElement' => 'NSYWPEpZBuY',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data22 = array(
	'Name' => 'Malaria Clinical (No test)',
	'dataElement' => 'kmpnqbSQLBl',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data23 = array(
	'Name' => 'Malaria In Pregnancy',
	'dataElement' => 'MwnLlVZZJkU',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data24 = array(
	'Name' => 'STI Genital Discharge Syndrome (GDS)',
	'dataElement' => 'W5GuP81V3Zf',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data25 = array(
	'Name' => 'STI Genital Ulcer Diseases (GUD)',
	'dataElement' => 'aruodm4tcnY',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data26 = array(
	'Name' => 'STI Pelvic Inflammatory Diseases (PID)',
	'dataElement' => 'WSaSCvJTnfQ',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data27 = array(
	'Name' => 'Sexually Transmitted Infections, Other',
	'dataElement' => 'Q5AQcGLeh7y',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data28 = array(
	'Name' => 'Tuberculosis',
	'dataElement' => 'ACM4BHrKZNQ',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data29 = array(
	'Name' => 'Leprosy',
	'dataElement' => 'l1GL5Tmn22E',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data30 = array(
	'Name' => 'Intestinal Worms',
	'dataElement' => 'GQwVaLxM9Gs',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);




$data31 = array(
	'Name' => 'Anaemia, Mild/Moderate',
	'dataElement' => 'X0TXADJv7GA',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data32 = array(
	'Name' => 'Sickle cell Disease',
	'dataElement' => 'fVzXb5qPrCp',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data33 = array(
	'Name' => 'Anaemia, Severe',
	'dataElement' => 'avzBnVwWlV9',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data34 = array(
	'Name' => 'Ear Infection, Chronic',
	'dataElement' => 'f6Q9p6uSWtS',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data35 = array(
	'Name' => 'Ear Infection, Acute',
	'dataElement' => 'n611GaZn5Xr',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data36 = array(
	'Name' => 'Ear Infection, Chronic',
	'dataElement' => 'f6Q9p6uSWtS',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data37 = array(
	'Name' => 'Ear Infection, Acute',
	'dataElement' => 'n611GaZn5Xr',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data38 = array(
	'Name' => 'Eye disease, Non Infectious',
	'dataElement' => 'anYwhLJV58B',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data39 = array(
	'Name' => 'Eye disease, Injuries',
	'dataElement' => 'fYO2JUHPdul',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data40 = array(
	'Name' => 'Plague in OPD',
	'dataElement' => 'BwJsDwQayqN',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data41 = array(
	'Name' => 'Skin Infection, Non-Fungal',
	'dataElement' => 'xsoXkeM69KC',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data42 = array(
	'Name' => 'Skin Infection,Fungal',
	'dataElement' => 'wULlcm4Qj5S',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data43 = array(
	'Name' => 'Skin Diseases, Non-Infectious',
	'dataElement' => 'UlFUBEpJsSs',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data44 = array(
	'Name' => '	Fungal Infection, Non-skin',
	'dataElement' => 'gNQ5NYT8SCz',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data45 = array(
	'Name' => 'Osteomyelitis',
	'dataElement' => 'NwzMLHAFMSC',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data46 = array(
	'Name' => 'Neonatal sepsis',
	'dataElement' => 'IJAImvSE7P6',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data47 = array(
	'Name' => '	Low birth weight and Prematurity Complication',
	'dataElement' => 'Y7upeLGM36C',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data48 = array(
	'Name' => 'Birth asphyxia',
	'dataElement' => 'CWXG9lBSI7Y',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);


	$data49 = array(
	'Name' => '	Pneumonia, Non Severe',
	'dataElement' => 'fLjZYZB3tuB',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data50 = array(
	'Name' => 'Pneumonia, Severe',
	'dataElement' => 'sr87SW2uEmt',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data51 = array(
	'Name' => 'Upper Respiratory Infections (Pharyngitis, Tonsillitis, Rhinitis)',
	'dataElement' => 'pZr0OzykmJB',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data52 = array(
	'Name' => 'Cerebral palsy',
	'dataElement' => 'QPCEJmazWgv',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data53 = array(
	'Name' => 'Urinary Tract Infections (UTI)',
	'dataElement' => 'QtBqSDM3YCN',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data54 = array(
	'Name' => 'Gynaecological Diseases, Other',
	'dataElement' => 'kzj3RYX536Y',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data55 = array(
	'Name' => 'STI Genital Ulcer Diseases (GUD)',
	'dataElement' => 'aruodm4tcnY',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data56 = array(
	'Name' => '	Kwashiorkor',
	'dataElement' => 'RlEchOC92Yr',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data57 = array(
	'Name' => 'Marasmus',
	'dataElement' => 'SAD8J9zO6MF',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data58 = array(
	'Name' => 'Marasmic Kwashiokor',
	'dataElement' => 'RlEchOC92Yr',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data59 = array(
	'Name' => '	Moderate Malnutrition',
	'dataElement' => 'CNzWVlVeOdx',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data60 = array(
	'Name' => 'Vitamin A Deficiency',
	'dataElement' => 'cUHWPSXirUl',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);




	$data61 = array(
	'Name' => 'Other Nutritional Disorders',
	'dataElement' => 'Lcj8osNjKQx',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data62 = array(
	'Name' => 'Caries',
	'dataElement' => 'iP9wSaCAZl5',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data63 = array(
	'Name' => '	Periodontal Diseases',
	'dataElement' => 'Qhi9QXHzP9b',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data64 = array(
	'Name' => 'Dental Emergency Care',
	'dataElement' => 'QhpE8F5apCj',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data65 = array(
	'Name' => 'Dental Conditions, Other',
	'dataElement' => 'Ivd9opj8WCi',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data66 = array(
	'Name' => 'Fractures / Dislocations',
	'dataElement' => 'o0KObJuu9Yu',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data67 = array(
	'Name' => 'Burn',
	'dataElement' => 'zqaHIXl6j7c',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data68 = array(
	'Name' => 'Poisoning',
	'dataElement' => 'ShxnDczlruP',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data69 = array(
	'Name' => 'Road Traffic Accidents',
	'dataElement' => 'Kpa6sheYah0',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data70 = array(
	'Name' => 'Pregnancy complications',
	'dataElement' => 'YzWIMlVnfxq',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data71 = array(
	'Name' => 'Abortion',
	'dataElement' => 'MG98iWgxXNT',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data72 = array(
	'Name' => 'Snake and Insect Bites',
	'dataElement' => 'Fuwwc9CgYUN',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data73 = array(
	'Name' => 'Animal bite (suspected Rabies)',
	'dataElement' => 'QqqAeR0wrwS',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data74 = array(
	'Name' => '	Emergencies Other',
	'dataElement' => 'nWRerupXUoy',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data75 = array(
	'Name' => 'Surgical Conditions, Other',
	'dataElement' => 'QvexV259cj2',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data76 = array(
	'Name' => 'Epilepsy	',
	'dataElement' => 'x5cswY9qs7m',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data77 = array(
	'Name' => '	Psychoses',
	'dataElement' => 'LD4thW4OmXi',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data78 = array(
	'Name' => '	Neurosis',
	'dataElement' => 'eTOV59Rcv4F',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);


	$data79 = array(
	'Name' => '	Substance abuse',
	'dataElement' => 'tM1ecc8qcsJ',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data80 = array(
	'Name' => 'Hypertension',
	'dataElement' => 'eoZtkUbfrmF',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data81 = array(
	'Name' => '	Rheumatic Fever',
	'dataElement' => 'NlXYR3IJWCl',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data82 = array(
	'Name' => 'Cardiovascular Diseases, Other',
	'dataElement' => 'qwFz1atKnbC',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data83 = array(
	'Name' => '	Bronchial Asthma',
	'dataElement' => 'HWZmyu3j4NX',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data84 = array(
	'Name' => 'Peptic Ulcers',
	'dataElement' => 'qoeOTJT0x6o',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data85 = array(
	'Name' => 'GIT Diseases, Other Non-Infectious',
	'dataElement' => 'zx2fEoXul5W',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data86 = array(
	'Name' => '	Diabetes Mellitus',
	'dataElement' => 'zfhmMA4HeJn',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data87 = array(
	'Name' => 'Rheumatoid and Joint Diseases',
	'dataElement' => 'c4ZuqcOCyix',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data88 = array(
	'Name' => 'Thyroid Diseases',
	'dataElement' => 'pP6BsR5KiRM',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data89 = array(
	'Name' => '	Neoplasms',
	'dataElement' => 'EEeh0pyQISB',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);

	$data90 = array(
	'Name' => 'Vitamin A Deficiency',
	'dataElement' => 'cUHWPSXirUl',
	'orgUnit' => $orgUnit,
	'period' => $period,
	'dataValues' => array('1','2','3','4','5','6','7','8','9','10'),
	);


for($i=1;$i<=90;$i++){
	$data = 'data';
	array_push($dhis_data, setDHISopdData(${$data.$i}));
}


//echo  json_encode($dhis_data);


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
		echo $server_output;
		//if ($ch) { echo 'Successfully Imported'; } else { echo 'Sorry! Fail to import.';}
}
/*}*/
//echo json_encode($dhis_data[1]);s

include 'includes/connection.php';


foreach ($dhis_data as $data) {
	//print_r($data['dataValues'][0]['dataElement']);exit;
	$dhis_element_id = $data['dataValues'][0]['dataElement'];
	$disease_group_name = strtolower($data['Name']);
	mysqli_query($conn,"update tbl_disease_group set dhis_element_id='$dhis_element_id' where disease_group_name like '%$disease_group_name%'") or die(mysqli_error($conn));
	//sendDataToDHIS($data);
}
				