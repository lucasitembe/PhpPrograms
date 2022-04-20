<script type="text/javascript" src="min.js"></script>
<script src="ui2\jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="ui2\jquery-ui.css">
<script type='text/javascript'>

$(function() {

	//modal box

	$( "#dialog-1" ).dialog({
               autoOpen: false,
            });

	$("#hudhurio1").show();

	$("#hudhurio2").hide();

	$("#hudhurio3").hide();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();


	$("#b2").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();

	$("#hudhurio3").fadeIn();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();
	});


	$("#b3prev").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").fadeIn();

	$("#hudhurio3").hide();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();
	});


	$("#b3").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();

	$("#hudhurio3").hide();

	$("#hudhurio4").fadeIn();

	$("#hudhurio5").hide();
	});



	$("#b4prev").click(function(){
        $("#hudhurio1").hide();
        $("#hudhurio2").hide();

	$("#hudhurio3").fadeIn();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();
	});


	$("#b4").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();

	$("#hudhurio3").hide();

	$("#hudhurio4").hide();

	$("#hudhurio5").fadeIn();
	});


	$("#b5").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();

	$("#hudhurio3").hide();

	$("#hudhurio4").fadeIn();

	$("#hudhurio5").hide();
	});


	$("#b1").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").fadeIn();

	$("#hudhurio3").hide();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();
	});


	$("#b2prev").click(function(){

	    $("#hudhurio1").fadeIn();
	    $("#hudhurio2").hide();

	$("#hudhurio3").hide();

	$("#hudhurio4").hide();

	$("#hudhurio5").hide();

	});
		//Cur status

});
</script>

<?php

include("./includes/connection.php");

if(isset($_POST['d1']))
{

  $date1 = $_POST['d1'];
  $date2 = $_POST['d2'];

	// Waliohudhuria ndani ya saa 48 age < 20
  $hudhurioageless=0;
	$year = date("Y");
	$sql_hudhurioageless = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_less' FROM `tbl_postnal` WHERE Hudhurio = 'Masaa 48' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioageless = mysqli_fetch_assoc($sql_hudhurioageless)['hudhurio_less'];

	// Waliohudhuria ndani ya saa 48 age > 20
  $hudhurioagegreater=0;
	$sql_hudhurioagegreater = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_greater' FROM `tbl_postnal` WHERE Hudhurio = 'Masaa 48' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioagegreater = mysqli_fetch_assoc($sql_hudhurioagegreater)['hudhurio_greater'];
	$totalhudhurio24hrs= $hudhurioageless + $hudhurioagegreater;


	// Waliohudhuria kati ya siku ya 3-7 age < 20
  $hudhurioageless3days=0;
	$sql_hudhurioageless3days = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_less' FROM `tbl_postnal` WHERE Hudhurio = 'Siku 3-7' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioageless3days = mysqli_fetch_assoc($sql_hudhurioageless3days)['hudhurio_less'];

	// Waliohudhuria kati ya siku ya 3-7 age > 20
  $hudhurioagegreater3days=0;
	$sql_hudhurioagegreater3days = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_greater' FROM `tbl_postnal` WHERE Hudhurio = 'Siku 3-7' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioagegreater3days = mysqli_fetch_assoc($sql_hudhurioagegreater3days)['hudhurio_greater'];
	$totalhudhurio3days= $hudhurioageless3days + $hudhurioagegreater3days;

	// Waliomaliza mahudhurio yote (saa48, siku 3-7, siku 8-28,siku 29-42) age < 20
  $mahudhurioyoteLessage=0;
	$sql_mahudhurioyoteLessage = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_less' FROM `tbl_postnal` WHERE (YEAR(Tarehe_ya_kujifungua)-$year) < 20  AND Hudhurio ='Siku 29-42' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$mahudhurioyoteLessage = mysqli_fetch_assoc($sql_mahudhurioyoteLessage)['hudhurio_less'];


	// Waliomaliza mahudhurio yote (saa48, siku 3-7, siku 8-28,siku 29-42) age > 20
  $mahudhurioyoteGreaterage=0;
	$sql_mahudhurioyoteGreaterage = mysqli_query($conn,"SELECT COUNT(Hudhurio) as 'hudhurio_greater' FROM `tbl_postnal` WHERE (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND Hudhurio ='Siku 29-42' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$mahudhurioyoteGreaterage = mysqli_fetch_assoc($sql_mahudhurioyoteGreaterage)['hudhurio_greater'];
	$mahudhurioyoteTotal= $mahudhurioyoteLessage + $mahudhurioyoteGreaterage;

	// Wenye upungufu mkubwa wa damu (HB <8.5g/dL) age < 20
  $damuLessage=0;
	$sql_damuLessage = mysqli_query($conn,"SELECT COUNT(HB) as 'damu_less' FROM `tbl_postnal` WHERE HB < 8.5 AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$damuLessage = mysqli_fetch_assoc($sql_damuLessage)['damu_less'];

  // Wenye upungufu mkubwa wa damu (HB <8.5g/dL) age > 20
  $damuGreaterage=0;
	$sql_damuGreaterage = mysqli_query($conn,"SELECT COUNT(HB) as 'damu_greater' FROM `tbl_postnal` WHERE HB < 8.5 AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$damuGreaterage = mysqli_fetch_assoc($sql_damuGreaterage)['damu_greater'];
  $damuLessTotal= $damuLessage + $damuGreaterage;

	// Waliopata matatizo ya akili age < 20
  $akiliLessage=0;
	$sql_akiliLessage  = mysqli_query($conn,"SELECT COUNT(Akili_Timamu) as 'akili_less' FROM `tbl_postnal` WHERE Akili_Timamu ='H'  AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$akiliLessage = mysqli_fetch_assoc($sql_akiliLessage)['akili_less'];

  // Waliopata matatizo ya akili age > 20
  $akiliGreaterage=0;
	$sql_akiliGreaterage  = mysqli_query($conn,"SELECT COUNT(Akili_Timamu) as 'akili_greater' FROM `tbl_postnal` WHERE Akili_Timamu ='H'  AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$akiliGreaterage = mysqli_fetch_assoc($sql_akiliGreaterage)['akili_greater'];
	$akiliLessTotal = $akiliLessage + $akiliGreaterage;


	// Waliopata Vit A age < 20
  $vitaminLessage=0;
	$sql_vitaminLessage  = mysqli_query($conn,"SELECT COUNT(Idadi_Vitamin) as 'vitamin_less' FROM `tbl_postnal` WHERE Idadi_Vitamin !='' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$vitaminLessage = mysqli_fetch_assoc($sql_vitaminLessage)['vitamin_less'];

  // Waliopata Vit A age > 20
  $vitaminGreaterage=0;
	$sql_vitaminGreaterage  = mysqli_query($conn,"SELECT COUNT(Idadi_Vitamin) as 'vitamin_greater' FROM `tbl_postnal` WHERE Idadi_Vitamin !='' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$vitaminGreaterage = mysqli_fetch_assoc($sql_vitaminGreaterage)['vitamin_greater'];
  $vitaminTotal= $vitaminLessage + $vitaminGreaterage;


	// Wenye msamba ulioambukizwa/Ulioachia  age < 20
  $msambaLessage=0;
	$sql_msambaLessage  = mysqli_query($conn,"SELECT COUNT(Msamba_hali) as 'msamba_less' FROM `tbl_postnal` WHERE Msamba_hali ='Umeachia' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$msambaLessage = mysqli_fetch_assoc($sql_msambaLessage)['msamba_less'];

	// Wenye msamba ulioambukizwa/Ulioachia  age > 20
  $msambaGreaterage=0;
	$sql_msambaGreaterage  = mysqli_query($conn,"SELECT COUNT(Msamba_hali) as 'msamba_greater' FROM `tbl_postnal` WHERE Msamba_hali ='Umeachia' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$msambaGreaterage = mysqli_fetch_assoc($sql_msambaGreaterage)['msamba_greater'];
  $msambaTotal= $msambaLessage + $msambaGreaterage;

	// Wenye fistula age < 20
  $fistulaLessage=0;
	$sql_fistulaLessage   = mysqli_query($conn,"SELECT COUNT(Fistula) as 'fistula_less' FROM `tbl_postnal` WHERE Fistula ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$fistulaLessage = mysqli_fetch_assoc($sql_fistulaLessage)['fistula_less'];

  // Wenye fistula age > 20
  $fistulaGreaterage=0;
	$sql_fistulaGreaterage    = mysqli_query($conn,"SELECT COUNT(Fistula) as 'fistula_greater' FROM `tbl_postnal` WHERE Fistula ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$fistulaGreaterage = mysqli_fetch_assoc($sql_fistulaGreaterage)['fistula_greater'];
  $fistulaTotal= $fistulaLessage + $fistulaGreaterage;

	// Waliojifungua kabla ya kufika kituo cha kutolea huduma za afya (BBA) age < 20
  $BBALessage=0;
	$sql_BBALessage = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'bba_less' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='BBA' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$BBALessage = mysqli_fetch_assoc($sql_BBALessage)['bba_less'];

	// Waliojifungua kabla ya kufika kituo cha kutolea huduma za afya (BBA) age > 20
  $BBAGreaterage=0;
	$sql_BBAGreaterage = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'bba_greater' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='BBA' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$BBAGreaterage = mysqli_fetch_assoc($sql_BBAGreaterage)['bba_greater'];
  $BBATotal= $BBALessage + $BBAGreaterage;

	// Waliojifungulia kwa wakunga wa jadi (TBA) age < 20
  $TBALessage=0;
	$sql_TBALessage = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'tba_less' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='TBA' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$TBALessage = mysqli_fetch_assoc($sql_TBALessage)['tba_less'];

	// Waliojifungulia kwa wakunga wa jadi (TBA) age > 20
  $TBAGreaterage=0;
	$sql_TBAGreaterage = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'tba_greater' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='TBA' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$TBAGreaterage = mysqli_fetch_assoc($sql_TBAGreaterage)['tba_greater'];
  $TBATotal= $TBALessage + $TBAGreaterage;

	// .Waliojifungulia nyumbani age < 20
  $HLessage=0;
	$sql_HLessage  = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'home_less' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='H' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HLessage = mysqli_fetch_assoc($sql_HLessage)['home_less'];

	// .Waliojifungulia nyumbani age < 20
  $HGreaterage=0;
	$sql_HGreaterage  = mysqli_query($conn,"SELECT COUNT(Mahali_Alipojifungulia) as 'home_greater' FROM `tbl_postnal` WHERE Mahali_Alipojifungulia ='H' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HGreaterage = mysqli_fetch_assoc($sql_HGreaterage)['home_greater'];
  $HTotal= $HLessage + $HGreaterage;

	// Idadi ya wateja waliopata ushauri nasa mara moja age < 20
  $FPLessage=0;
	$sql_FPLessage  = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'family_less' FROM `tbl_postnal` WHERE Family_planing ='Ushauri umetolewa' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPLessage = mysqli_fetch_assoc($sql_FPLessage)['family_less'];

  // Idadi ya wateja waliopata ushauri nasa mara moja age < 20
  $FPGreaterage=0;
	$sql_FPGreaterage  = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'family_greater' FROM `tbl_postnal` WHERE Family_planing ='Ushauri umetolewa' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPGreaterage = mysqli_fetch_assoc($sql_FPGreaterage)['family_greater'];
  $FPTotal= $FPLessage + $FPGreaterage;

	// Amepata njia ya uzazi wa mpango wakati wa hudhurio la postnatal age < 20
  $FPIECLessage=0;
	$sql_FPIECLessage   = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fpie_less' FROM `tbl_postnal` WHERE Family_planing ='Amepatiwa kielelezo(IEC material)' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPIECLessage = mysqli_fetch_assoc($sql_FPIECLessage)['fpie_less'];

	// Amepata njia ya uzazi wa mpango wakati wa hudhurio la postnatal age < 20
  $FPIECGreaterage=0;
	$sql_FPIECGreaterage   = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fpie_greater' FROM `tbl_postnal` WHERE Family_planing ='Amepatiwa kielelezo(IEC material)' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPIECGreaterage = mysqli_fetch_assoc($sql_FPIECGreaterage)['fpie_greater'];
  $FPIECTotal= $FPIECLessage + $FPIECGreaterage;

	// Waliopata njia ya uzazi wa mpango baada ya mimba kuharibika age < 20
  $FPPPCLessage=0;
	$sql_FPPPCLessage   = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fppp_less' FROM `tbl_postnal` WHERE Family_planing ='Amepata njia ya uzazi wa mpango wakati wa PPC' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPPPCLessage = mysqli_fetch_assoc($sql_FPPPCLessage)['fppp_less'];

  // Waliopata njia ya uzazi wa mpango baada ya mimba kuharibika age > 20
  $FPPPCGreaterage=0;
	$sql_FPPPCGreaterage   = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fppp_greater' FROM `tbl_postnal` WHERE Family_planing ='Amepata njia ya uzazi wa mpango wakati wa PPC' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPPPCGreaterage = mysqli_fetch_assoc($sql_FPPPCGreaterage)['fppp_greater'];
  $FPPPCTotal= $FPPPCLessage + $FPPPCGreaterage;

	// Waliopata rufaa kupata njia uzazi wa mpango age < 20
  $FPrufaaLessage=0;
	$sql_FPrufaaLessage   = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fprufaa_less' FROM `tbl_postnal` WHERE Family_planing ='Amepata rufaa kupata njia ya uzazi wa mpango' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPrufaaLessage = mysqli_fetch_assoc($sql_FPrufaaLessage)['fprufaa_less'];

	// Waliopata rufaa kupata njia uzazi wa mpango age > 20
  $FPrufaaGreaterage=0;
	$sql_FPrufaaGreaterage  = mysqli_query($conn,"SELECT COUNT(Family_planing) as 'fprufaa_greater' FROM `tbl_postnal` WHERE Family_planing ='Amepata rufaa kupata njia ya uzazi wa mpango' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$FPrufaaGreaterage = mysqli_fetch_assoc($sql_FPrufaaGreaterage)['fprufaa_greater'];
  $FPrufaaTotal= $FPrufaaLessage + $FPrufaaGreaterage;


	// Waliokuja postnatal wakiwa positive age < 20
	$HPVVULessage=0;
	$sql_HPVVULessage  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'hpvvu_less' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HPVVULessage = mysqli_fetch_assoc($sql_HPVVULessage)['hpvvu_less'];

  // Waliokuja postnatal wakiwa positive age > 20
  $HPVVUGreaterage=0;
	$sql_HPVVUGreaterage   = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'hpvvu_greater' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HPVVUGreaterage = mysqli_fetch_assoc($sql_HPVVUGreaterage)['hpvvu_greater'];
  $HPVVUTotal= $HPVVULessage + $HPVVUGreaterage;

	// Waliopima VVU wakati wa postnatal age < 20
  $KVVULessage=0;
	$sql_KVVULessage  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'kvvu_less' FROM `tbl_postnal` WHERE Kipimo_VVU !='' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KVVULessage = mysqli_fetch_assoc($sql_KVVULessage)['kvvu_less'];

	// Waliopima VVU wakati wa postnatal age > 20
  $KVVUGreaterage=0;
	$sql_KVVUGreaterage  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'kvvu_greater' FROM `tbl_postnal` WHERE Kipimo_VVU !='' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KVVUGreaterage = mysqli_fetch_assoc($sql_KVVUGreaterage)['kvvu_greater'];
  $KVVUTotal= $KVVULessage + $KVVUGreaterage;

	// Waliogundulika wana VVU wakati wa postnatal age < 20
  $KPVVULessage=0;
	$sql_KPVVULessage  = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'kpvvu_less' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KPVVULessage = mysqli_fetch_assoc($sql_KPVVULessage)['kpvvu_less'];

	// Waliogundulika wana VVU wakati wa postnatal age > 20
  $KPVVUGreaterage=0;
	$sql_KPVVUGreaterage  = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'kpvvu_greater' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KPVVUGreaterage = mysqli_fetch_assoc($sql_KPVVUGreaterage)['kpvvu_greater'];
  $KPVVUTotal= $KPVVULessage + $KPVVUGreaterage;

	// Wenye VVU waliochagua EBF age < 20
  $VVUEBFLessage=0;
	$sql_VVUEBFLessage1  = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'vvuebf_less1' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVUEBFLessage1 = mysqli_fetch_assoc($sql_VVUEBFLessage1)['vvuebf_less1'];

	$sql_VVUEBFLessage2  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'vvuebf_less2' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVUEBFLessage2 = mysqli_fetch_assoc($sql_VVUEBFLessage2)['vvuebf_less2'];
	$VVUEBFLessage = $VVUEBFLessage1 + $VVUEBFLessage2;

	// Wenye VVU waliochagua EBF age > 20
  $VVUEBFGreaterage=0;
	$sql_VVUEBFGreaterage1  = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'vvuebf_greater1' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVUEBFGreaterage1 = mysqli_fetch_assoc($sql_VVUEBFGreaterage1)['vvuebf_greater1'];

	$sql_VVUEBFGreaterage2  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'vvuebf_greater2' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVUEBFGreaterage2 = mysqli_fetch_assoc($sql_VVUEBFGreaterage2)['vvuebf_greater2'];
	$VVUEBFGreaterage = $VVUEBFGreaterage1 + $VVUEBFGreaterage2;

  $VVUEBFTotal= $VVUEBFLessage + $VVUEBFGreaterage;

	// Wenye VVU waliochagua RF age < 20
  $VVURFLessage=0;
	$sql_VURFLessage1  = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'vvurf_less1' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVURFLessage1 = mysqli_fetch_assoc($sql_VURFLessage1)['vvurf_less1'];

	$sql_VURFLessage2  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'vvurf_less2' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20   AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVURFLessage2 = mysqli_fetch_assoc($sql_VURFLessage2)['vvurf_less2'];
  $VVURFLessage = $VVURFLessage1 + $VVURFLessage2;

	// Wenye VVU waliochagua RF age > 20
  $VVURFGreaterage=0;
	$sql_VVURFGreaterage1 = mysqli_query($conn,"SELECT COUNT(Hali_ya_VVU) as 'vvurf_greater1' FROM `tbl_postnal` WHERE Hali_ya_VVU ='P' AND Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20  AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVURFGreaterage1 = mysqli_fetch_assoc($sql_VVURFGreaterage1)['vvurf_greater1'];

	$sql_VVURFGreaterage2  = mysqli_query($conn,"SELECT COUNT(Kipimo_VVU) as 'vvurf_greater2' FROM `tbl_postnal` WHERE Kipimo_VVU ='P' AND Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20   AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$VVURFGreaterage2 = mysqli_fetch_assoc($sql_VVURFGreaterage2)['vvurf_greater2'];
  $VVURFGreaterage = $VVURFGreaterage1 + $VVURFGreaterage2;
  $VVURFTotal = $VVURFLessage + $VVURFGreaterage;

	// Idadi ya watoto waliohudhuria ndani ya masaa 48 age <  20
  $hudhurioMtotoLessage=0;
	$sql_hudhurioMtotoLessage  = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtotoh_less' FROM `tbl_postnal` WHERE Hudhurio_la_mtoto = 'Masaa 48' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioMtotoLessage = mysqli_fetch_assoc($sql_hudhurioMtotoLessage)['mtotoh_less'];

	// Idadi ya watoto waliohudhuria ndani ya masaa 48 age >  20
  $hudhurioMtotoGreaterage=0;
	$sql_hudhurioMtotoGreaterage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtotoh_greater' FROM `tbl_postnal` WHERE Hudhurio_la_mtoto = 'Masaa 48' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhurioMtotoGreaterage = mysqli_fetch_assoc($sql_hudhurioMtotoGreaterage)['mtotoh_greater'];
  $hudhurioMtotoTotal= $hudhurioMtotoLessage + $hudhurioMtotoGreaterage;

	// Idadi ya watoto waliohudhuria kati ya siku 3-7 age < 20
  $hudhuriosiku3MtotoLessage=0;
	$sql_hudhuriosiku3MtotoLessage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtotoh3_less' FROM `tbl_postnal` WHERE Hudhurio_la_mtoto = 'Siku 3-7' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhuriosiku3MtotoLessage = mysqli_fetch_assoc($sql_hudhuriosiku3MtotoLessage)['mtotoh3_less'];

	// Idadi ya watoto waliohudhuria kati ya siku 3-7 age > 20
  $hudhuriosiku3MtotoGreaterage=0;
	$sql_hudhuriosiku3MtotoGreaterage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtotoh3_greater' FROM `tbl_postnal` WHERE Hudhurio_la_mtoto = 'Siku 3-7' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$hudhuriosiku3MtotoGreaterage = mysqli_fetch_assoc($sql_hudhuriosiku3MtotoGreaterage)['mtotoh3_greater'];
  $hudhuriosiku3MtotoTotal = $hudhuriosiku3MtotoLessage + $hudhuriosiku3MtotoGreaterage;

	// Waliomaliza mahudhurio yote (saa48, siku 3-7, siku 8-28,siku 29-42) age < 20
	$mahudhurioyotemtotoLessage=0;
	$sql_mahudhurioyotemtotoLessage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtthy_less' FROM `tbl_postnal`	WHERE (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND Hudhurio_la_mtoto ='Siku 29-42' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$mahudhurioyotemtotoLessage = mysqli_fetch_assoc($sql_mahudhurioyotemtotoLessage)['mtthy_less'];

	// Waliomaliza mahudhurio yote (saa48, siku 3-7, siku 8-28,siku 29-42) age > 20
  $mahudhurioyotemtotoGreaterage=0;
	$sql_mahudhurioyotemtotoGreaterage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'mtthy_greater' FROM `tbl_postnal`	WHERE (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND Hudhurio_la_mtoto ='Siku 29-42' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$mahudhurioyotemtotoGreaterage = mysqli_fetch_assoc($sql_mahudhurioyotemtotoGreaterage)['mtthy_greater'];
  $mahudhurioyotemtotoTotal= $mahudhurioyotemtotoLessage + $mahudhurioyotemtotoGreaterage;

	// Idadi ya watoto wailiopewa BCG age < 20
  $BCGLessage=0;
	$sql_BCGLessage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'bcg_less' FROM `tbl_postnal` WHERE Chanjo = 'BCG' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$BCGLessage = mysqli_fetch_assoc($sql_BCGLessage)['bcg_less'];

	// Idadi ya watoto wailiopewa BCG age > 20
  $BCGGreaterage=0;
	$sql_BCGGreaterage = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto) as 'bcg_greater' FROM `tbl_postnal` WHERE Chanjo = 'BCG' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$BCGGreaterage = mysqli_fetch_assoc($sql_BCGGreaterage)['bcg_greater'];
  $BCGTotal= $BCGLessage + $BCGGreaterage;

	// idadi ya watoto waliopewa OPV 0 age < 20
  $OPVLessage=0;
	$sql_OPVLessage  = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto)  as 'opv_less' FROM `tbl_postnal` WHERE Chanjo = 'OPV 0' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$OPVLessage = mysqli_fetch_assoc($sql_OPVLessage)['opv_less'];

	// idadi ya watoto waliopewa OPV 0 age > 20
  $OPVGreaterage=0;
	$sql_OPVGreaterage  = mysqli_query($conn,"SELECT COUNT(Hudhurio_la_mtoto)  as 'opv_greater' FROM `tbl_postnal` WHERE Chanjo = 'OPV 0' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$OPVGreaterage = mysqli_fetch_assoc($sql_OPVGreaterage)['opv_greater'];
  $OPVTotal= $OPVLessage + $OPVGreaterage;

	// Idadi ya watoto waliozaliwa na uzito <2.5kg wakapatiwa KMC age < 20
  $KMCLessage=0;
	$sql_KMCLessage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'kmc_less' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND KMC = 'Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KMCLessage = mysqli_fetch_assoc($sql_KMCLessage)['kmc_less'];

	// Idadi ya watoto waliozaliwa na uzito <2.5kg wakapatiwa KMC age > 20
  $KMCGreaterage=0;
	$sql_KMCGreaterage   = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'kmc_greater' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND KMC = 'Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$KMCGreaterage = mysqli_fetch_assoc($sql_KMCGreaterage)['kmc_greater'];
  $KCMTotal=0;

	// Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg age < 20
  $uzitoLessage=0;
	$sql_uzitoLessage   = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'uzitoh_less' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND Mahali_Alipojifungulia = 'H' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$uzitoLessage = mysqli_fetch_assoc($sql_uzitoLessage)['uzitoh_less'];

	// Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg age > 20
  $uzitoGreaterage=0;
	$sql_uzitoGreaterage   = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'uzitoh_greaater' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND Mahali_Alipojifungulia = 'H' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$uzitoGreaterage = mysqli_fetch_assoc($sql_uzitoGreaterage)['uzitoh_greaater'];
  $uzitoTotal= $uzitoLessage + $uzitoGreaterage;

	//Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg walioanzishiwa huduma ya KMC age < 20
  $HomeKMCLessage=0;
	$sql_HomeKMCLessage    = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'homekmc_less' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND Mahali_Alipojifungulia = 'H' AND KMC = 'Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HomeKMCLessage = mysqli_fetch_assoc($sql_HomeKMCLessage)['homekmc_less'];

	//Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg walioanzishiwa huduma ya KMC age > 20
  $HomeKMCGreaterage=0;
	$sql_HomeKMCGreaterage    = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'homekmc_greater' FROM `tbl_postnal` WHERE Uzito_wa_mtoto < 2.5 AND Mahali_Alipojifungulia = 'H' AND KMC = 'Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HomeKMCGreaterage = mysqli_fetch_assoc($sql_HomeKMCGreaterage)['homekmc_greater'];
  $HomeKMCTotal= $HomeKMCLessage + $HomeKMCGreaterage;

	// Idadi ya watoto wenye upungufu mkubwa wa damu (Hb <10g/dl) age < 20
  $HbLessage=0;
	$sql_HbLessage = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'hb_less' FROM `tbl_postnal` WHERE mtoto_HB < 10 AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HbLessage = mysqli_fetch_assoc($sql_HbLessage)['hb_less'];

	// Idadi ya watoto wenye upungufu mkubwa wa damu (Hb <10g/dl) age > 20
  $HbGreaterage=0;
	$sql_HbGreaterage = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'hb_greater' FROM `tbl_postnal` WHERE mtoto_HB < 10 AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$HbGreaterage = mysqli_fetch_assoc($sql_HbGreaterage)['hb_greater'];
  $HbTotal= $HbLessage + $HbGreaterage;

	// Idadi ya watoto wenye uambukizo mkali (Septicaemia/Sepsis) age < 20
  $septLessage=0;
	$sql_septLessage = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'sept_less' FROM `tbl_postnal` WHERE Uambukizo_Mkali ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$septLessage = mysqli_fetch_assoc($sql_septLessage)['sept_less'];

	// Idadi ya watoto wenye uambukizo mkali (Septicaemia/Sepsis) age > 20
  $septGreaterage=0;
	$sql_septGreaterage = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'sept_greater' FROM `tbl_postnal` WHERE Uambukizo_Mkali ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$septGreaterage = mysqli_fetch_assoc($sql_septGreaterage)['sept_greater'];
  $septTotal= $septLessage + $septGreaterage;

	// Idadi ya watoto wenye uambukizo kwenye kitovu age < 20
  $kitovuLessage=0;
	$sql_kitovuLessage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'kitovu_less' FROM `tbl_postnal` WHERE Kitovu ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$kitovuLessage = mysqli_fetch_assoc($sql_kitovuLessage)['kitovu_less'];

	// Idadi ya watoto wenye uambukizo kwenye kitovu age > 20
  $kitovuGreaterage=0;
	$sql_kitovuGreaterage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'kitovu_greater' FROM `tbl_postnal` WHERE Kitovu ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$kitovuGreaterage = mysqli_fetch_assoc($sql_kitovuGreaterage)['kitovu_greater'];
  $kitovuTotal= $kitovuLessage + $kitovuGreaterage;

	// Idadi ya watoto wenye uambukizo kwenye ngozi age < 20
  $NgoziLessage=0;
	$sql_NgoziLessage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'ngozi_less' FROM `tbl_postnal` WHERE Ngozi ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$NgoziLessage = mysqli_fetch_assoc($sql_NgoziLessage)['ngozi_less'];

 	// Idadi ya watoto wenye uambukizo kwenye ngozi age > 20
  $NgoziGreaterage=0;
	$sql_goziGreaterage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'ngozi_greater' FROM `tbl_postnal` WHERE Ngozi ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$NgoziGreaterage = mysqli_fetch_assoc($sql_goziGreaterage)['ngozi_greater'];
  $NgoziTotal= $NgoziLessage + $NgoziGreaterage;

	// Idadi ya watoto wenye Jaundice age < 20
  $jauLessage=0;
	$sql_jauLessage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'jau_less' FROM `tbl_postnal` WHERE Jaundice ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$jauLessage = mysqli_fetch_assoc($sql_jauLessage)['jau_less'];

	// Idadi ya watoto wenye Jaundice age > 20
  $jauGreaterage=0;
	$sql_jauGreaterage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'jau_greater' FROM `tbl_postnal` WHERE Jaundice ='Y' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$jauGreaterage = mysqli_fetch_assoc($sql_jauGreaterage)['jau_greater'];
  $jauTotal= $jauLessage + $jauGreaterage;

	// Vifoo vya watoto wachanga waliozaliwa nyumbani (perinatal death) age < 20
  $vifoLessage=0;
	$sql_vifoLessage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'vifoh_less' FROM `tbl_postnal` WHERE Hali_ya_mtoto = 'A' AND Mahali_Alipojifungulia = 'H'  AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$vifoLessage = mysqli_fetch_assoc($sql_vifoLessage)['vifoh_less'];

	// Vifoo vya watoto wachanga waliozaliwa nyumbani (perinatal death) age > 20
  $vifoGreaterage=0;
	$sql_vifoGreaterage   = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua) as 'vifoh_greater' FROM `tbl_postnal` WHERE Hali_ya_mtoto = 'A' AND Mahali_Alipojifungulia = 'H'  AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$vifoGreaterage = mysqli_fetch_assoc($sql_vifoGreaterage)['vifoh_greater'];
  $vifoTotal= $vifoLessage + $vifoGreaterage;

	// Waliopewa dawa za ARV age < 20
  $ARVtotoLessage=0;
	$sql_ARVtotoLessage   = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua)  as 'arv_less' FROM `tbl_postnal` WHERE ARV != ''  AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$ARVtotoLessage = mysqli_fetch_assoc($sql_ARVtotoLessage)['arv_less'];

	// Waliopewa dawa za ARV age > 20
  $ARVtotoGreaterage=0;
	$sql_ARVtotoGreaterage  = mysqli_query($conn,"SELECT COUNT(Tarehe_ya_kujifungua)  as 'arv_greater' FROM `tbl_postnal` WHERE ARV != ''  AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$ARVtotoGreaterage = mysqli_fetch_assoc($sql_ARVtotoGreaterage)['arv_greater'];
  $ARVtotoTotal= $ARVtotoLessage + $ARVtotoGreaterage;

	// Watoto wachanga wanaonyonya maziwa ya mama pekee (EBF) age < 20
  $EFBtotoLessage=0;
	$sql_EFBtotoLessage  = mysqli_query($conn,"SELECT COUNT(Mother_ID) as 'efbt_less' FROM `tbl_postnal` WHERE Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$EFBtotoLessage = mysqli_fetch_assoc($sql_EFBtotoLessage)['efbt_less'];

  // Watoto wachanga wanaonyonya maziwa ya mama pekee (EBF) age > 20
  $EFBtotoGreaterage=0;
	$sql_EFBtotoGreaterage   = mysqli_query($conn,"SELECT COUNT(Mother_ID) as 'efbt_greater' FROM `tbl_postnal` WHERE Ulishaji_wa_mtoto = 'EBF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$EFBtotoGreaterage = mysqli_fetch_assoc($sql_EFBtotoGreaterage)['efbt_greater'];
  $EFBtotoTotal= $EFBtotoLessage + $EFBtotoGreaterage;

	// Watato wachanga wanaonyweshwa maziwa mbadala (RF) age < 20
  $RFtotoLessage=0;
	$sql_RFtotoLessage   = mysqli_query($conn,"SELECT COUNT(Mother_ID) as 'rf_less' FROM `tbl_postnal` WHERE Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$RFtotoLessage = mysqli_fetch_assoc($sql_RFtotoLessage)['rf_less'];

	// Watato wachanga wanaonyweshwa maziwa mbadala (RF) age > 20
  $RFtotoGreaterage=0;
	$sql_RFtotoGreaterage   = mysqli_query($conn,"SELECT COUNT(Mother_ID) as 'rf_greater' FROM `tbl_postnal` WHERE Ulishaji_wa_mtoto = 'RF' AND (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$RFtotoGreaterage = mysqli_fetch_assoc($sql_RFtotoGreaterage)['rf_greater'];
  $RFtotoTotal= $RFtotoLessage + $RFtotoGreaterage;

	// Watoto wachanga wanaonyonya maziwa ya mama na kupatiwa chakula kingine (MF) age < 20
  $MFtotoLessage=0;
	$sql_MFtotoLessage    = mysqli_query($conn,"SELECT COUNT(Ulishaji_wa_mtoto) as 'mf_less' FROM `tbl_postnal` WHERE (YEAR(Tarehe_ya_kujifungua)-$year) < 20 AND Ulishaji_wa_mtoto = 'MF' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$MFtotoLessage = mysqli_fetch_assoc($sql_MFtotoLessage)['mf_less'];

	// Watoto wachanga wanaonyonya maziwa ya mama na kupatiwa chakula kingine (MF) age > 20
  $MFtotoGreaterage=0;
	$sql_FtotoGreaterage = mysqli_query($conn,"SELECT COUNT(Ulishaji_wa_mtoto) as 'mf_greater' FROM `tbl_postnal` WHERE (YEAR(Tarehe_ya_kujifungua)-$year) > 20 AND Ulishaji_wa_mtoto = 'MF' AND DATE(Hudhurio_Date) BETWEEN '$date1' AND '$date2'");
	$MFtotoGreaterage = mysqli_fetch_assoc($sql_FtotoGreaterage)['mf_greater'];
  $MFtotoTotal= $MFtotoLessage + $MFtotoGreaterage;

	// umla Waliohudhuria ndani ya siku 7 (1a +1b)
	$ndanidaysless =  $hudhurioageless + $hudhurioageless3days;
	$ndanidaysgreater = $hudhurioagegreater + $hudhurioagegreater3days;
	$Total7days = $ndanidaysless + $ndanidaysgreater;


	// Jumla ya Waliohudhuria ndani ya siku 7(11a+11b)
  $TotalmtotoLess = $hudhurioMtotoLessage + $hudhuriosiku3MtotoLessage;
	$TotalmtotoGreater = $hudhurioMtotoGreaterage +  $hudhuriosiku3MtotoGreaterage;
	$grandMtotohudhurio = $TotalmtotoLess + $TotalmtotoGreater;

  echo "<div id='all'>";
  echo "<div id='hudhurio1'>";
    echo "<table style='width:100%'>";
    echo "<tr>
						<th>Namba</th>
						<th>Maelezo</th>
						<th>Umri < 20</th>
						<th>Umri Miaka 20 na zaidi</th>
						<th>Total</th></tr>";

     echo "<tr>
		 <td style='text-align:center'>1a</td>
		 <td>Waliohudhuria ndani ya saa 48</td>
		 <td><input type='text' readonly value='$hudhurioageless'></td>
		 <td><input type='text' readonly value='$hudhurioagegreater'></td>
		 <td><input type='text' readonly value='$totalhudhurio24hrs'></td>

     <tr>
		 <td style='text-align:center'>1b</td>
		 <td>Waliohudhuria kati ya siku 3-7</td>
		 <td><input type='text' readonly value='$hudhurioageless3days'></td>
		 <td><input type='text' readonly value='$hudhurioagegreater3days'></td>
		 <td><input type='text' readonly value='$totalhudhurio3days'></td>

     <tr>
		 <td style='text-align:center'></td>
		 <td>Jumla Waliohudhuria ndani ya siku 7 (1a +1b)</td>
		 <td><input type='text' readonly value='$ndanidaysless'></td>
		 <td><input type='text' readonly value='$ndanidaysgreater'></td>
		 <td><input type='text' readonly value='$Total7days'></td>


    <tr>
		<td style='text-align:center'>2</td>
		<td>Waliomaliza mahudhurio yote(saa 48,siku 3-7,siku 8-28,siku 29-42)</td>
		<td><input type='text' readonly value='$mahudhurioyoteLessage'></td>
		<td><input type='text' readonly value='$mahudhurioyoteGreaterage'></td>
		<td><input type='text' readonly value='$mahudhurioyoteTotal'></td>

    <tr>
		<td style='text-align:center'>3</td>
		<td>Wenye upungufu mkubwa wa damu(Hb<8.5g/dl)</td>
		<td><input type='text' readonly value='$damuLessage'></td>
		<td><input type='text' readonly value='$damuGreaterage'></td>
		<td><input type='text' readonly value='$damuLessTotal'></td>
    <tr><td style='text-align:center'>4</td>
		<td>Waliopata matatizo ya akili</td>
		<td><input type='text' readonly value='$akiliLessage'></td>
		<td><input type='text' readonly value='$akiliGreaterage'></td>
		<td><input type='text' readonly value='$akiliLessTotal'></td>

    <tr>
		<td style='text-align:center'>5</td>
		<td>Walopata Vit. A</td>
		<td><input type='text' readonly value='$vitaminLessage'></td>
		<td><input type='text' readonly value='$vitaminGreaterage'></td>
		<td><input type='text' readonly value='$vitaminTotal'></td>

    <tr><td style='text-align:center'>6</td>
		<td>Wenye msamba ulioambukizwa/Ulioachia</td>
		<td><input type='text' readonly value='$msambaLessage'></td>
		<td><input type='text' readonly value='$msambaGreaterage'></td>
		<td><input type='text' readonly value='$msambaTotal'></td>

    <tr>
		<td style='text-align:center'>7</td>
		<td>Wenye fistula</td>
		<td><input type='text' readonly value='$fistulaLessage'>
		</td><td><input type='text' readonly value='$fistulaGreaterage'>
		</td><td><input type='text' readonly value='$fistulaTotal'></td>



 </tr>";

     echo "<tr>
		 <th style='text-align:center'>8</th>
		 <th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Waliojifungulia Nje ya kituo cha kutolea huduma za afya</th>
		 </tr>";


    echo "<tr>
		<td style='text-align:center'>8a</td>
		<td>Waliojifungua kabla ya kufika kituo cha kutolea huduma za afya (BBA)</td>
		<td><input type='text' readonly value='$BBALessage'></td>
		<td><input type='text' readonly value='$BBAGreaterage'></td>
    <td><input type='text' readonly value='$BBATotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>8b</td>
		<td>Waliojifungulia kwa wakunga wa jadi (TBA)</td>
		<td><input type='text' readonly value='$TBALessage'></td>
		<td><input type='text' readonly value='$TBAGreaterage'></td>
    <td><input type='text' readonly value='$TBATotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>8c</td>
		<td>Waliojifungulia nyumbani</td>
		<td><input type='text' readonly value='$HLessage'></td>
		<td><input type='text' readonly value='$HGreaterage'></td>
    <td><input type='text' readonly value='$HTotal'></td>
		</tr>";

    echo "<tr>
		<th style='text-align:center'>9</th>
		<th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Uzazi wa Mpango</th>
		</tr>";

    echo "<tr>
		<td style='text-align:center'>9a</td>
		<td>Idadi ya wateja waliopata ushauri nasaha mara moja</td>
		<td><input type='text' readonly value='$FPLessage'></td>
		<td><input type='text' readonly value='$FPGreaterage'></td>
    <td><input type='text' readonly value='$FPTotal'></td>
		</tr>


    <tr>
		<td style='text-align:center'>9b</td>
		<td>Amepata njia ya Uzazi wa mpango wakati wa hudhurio ya Postnatal</td>
		<td><input type='text' readonly value='$FPIECLessage'></td>
		<td><input type='text' readonly value='$FPIECGreaterage'></td>
    <td><input type='text' readonly value='$FPIECTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>9c</td>
		<td>Waliopata njia ya Uzazi wa mpango baada ya mimba kuharibika</td>
		<td><input type='text' readonly value='$FPPPCLessage'></td>
		<td><input type='text' readonly value='$FPPPCGreaterage'></td>
    <td><input type='text' readonly value='$FPPPCTotal'></td>
		</tr>

     <tr><td style='text-align:center'>9d</td>
		 <td>Waliopata rufaa kupata njia ya uzazi wa mpango</td>
		 <td><input type='text' readonly value='$FPrufaaLessage'></td>
		 <td><input type='text' readonly value='$FPrufaaGreaterage'></td>
     <td><input type='text' readonly value='$FPrufaaTotal'></td>
		 </tr>";

    echo "<tr>
		<th style='text-align:center'>10</th>
		<th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>PMTCT</th>
		</tr>";
    echo "<tr>
		<td style='text-align:center'>10a</td>
		<td>Waliokuja postnatal wakiwa positive</td>
		<td><input type='text' readonly value='$HPVVULessage'></td>
		<td><input type='text' readonly value='$HPVVUGreaterage'></td>
     <td><input type='text' readonly value='$HPVVUTotal'></td>
		 </tr>

     <tr>
		 <td style='text-align:center'>10b</td>
		 <td>Waliopima VVU wakati wa postnatal(ndani ya siku 42 tangu ya kujifungua)</td>
		 <td><input type='text' readonly value='$KVVULessage'></td>
		 <td><input type='text' readonly value='$KVVUGreaterage'></td>
     <td><input type='text' readonly value='$KVVUTotal'></td></tr>

    <tr>
		<td style='text-align:center'>10c</td>
		<td>Waliogundulika wana VVU wakati wa postnatal(ndani ya siku 42 tangu kujifungua)</td>
		<td><input type='text' readonly value='$KPVVULessage'></td>
		<td><input type='text' readonly value='$KPVVUGreaterage'></td>
     <td><input type='text' readonly value='$KPVVUTotal'></td>
		 </tr>

     <tr>
		 <td style='text-align:center'>10d</td>
		 <td>Wenye VVU waliochagua kunyonyesha maziwa ya mama pekee(EBF)</td>
		 <td><input type='text' readonly value='$VVUEBFLessage'></td>
		 <td><input type='text' readonly value='$VVUEBFGreaterage'></td>
     <td><input type='text' readonly value='$VVUEBFTotal'></td>
		 </tr>

     <tr>
		 <td style='text-align:center'>10e</td>
		 <td>Wenye VVU waliochagua kunywesha maziwa mbadala (RF)</td>
		 <td><input type='text' readonly value='$VVURFLessage'></td>
		 <td><input type='text' readonly value='$VVURFGreaterage'></td>
     <td><input type='text' readonly value='$VVURFTotal'></td>
		 </tr>";

     echo "<tr>
		 <th style='text-align:center'>11</th>
		 <th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>MTOTO</th>
		 </tr>";
     echo "<tr>
		 <td style='text-align:center'>11a</td>
		 <td>Idadi ya watoto waliohudhuria Ndani ya saa 48</td>
		 <td><input type='text' readonly value='$hudhurioMtotoLessage'></td>
		 <td><input type='text' readonly value='$hudhurioMtotoGreaterage'></td>
     <td><input type='text' readonly value='$hudhurioMtotoTotal'></td>
		 </tr>


    <tr>
		<td style='text-align:center'>11b</td>
		<td>Idadi ya watoto waliohudhuria kati ya siku 3-7</td>
		<td><input type='text' readonly value='$hudhuriosiku3MtotoLessage'></td>
		<td><input type='text' readonly value='$hudhuriosiku3MtotoGreaterage'></td>
    <td><input type='text' readonly value='$hudhuriosiku3MtotoTotal'></td>
		</tr>";

    echo "<tr>
		<td style='text-align:center'></td>
		<td style='font-weight:bold;font-style:italic;'>Jumla ya Waliohudhuria ndani ya siku 7(11a+11b)</td>
		<td><input type='text' style='font-weight:bold' readonly value='$TotalmtotoLess'></td>
		<td><input type='text' style='font-weight:bold' readonly value='$TotalmtotoGreater'></td>
    <td><input type='text' style='font-weight:bold' readonly value='$grandMtotohudhurio'></td>
		</tr>

    <tr><td style='text-align:center'>11c</td>
		<td>Waliomaliza mahudhurio yote (saa 48, siku 3-7,siku 8-28,siku 29-42)</td>
		<td><input type='text' readonly value='$mahudhurioyotemtotoLessage'></td>
		<td><input type='text' readonly value='$mahudhurioyotemtotoGreaterage'></td>
    <td><input type='text' readonly value='$mahudhurioyotemtotoTotal'></td>
		</tr>";

    echo "<tr>
		<th style='text-align:center'>12</th>
		<th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>HUDUMA KWA MTOTO</th>
		</tr>";

    echo "<tr>
		<td style='text-align:center'>12a</td>
		<td>Idadi ya watoto waliopewa BCG</td>
		<td><input type='text' readonly value='$BCGLessage'></td>
		<td><input type='text' readonly value='$BCGGreaterage'></td>
    <td><input type='text' readonly value='$BCGTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>12b</td>
		<td>Idadi ya watoto waliopewa OPV O</td>
		<td><input type='text' readonly value='$OPVLessage'></td>
		<td><input type='text' readonly value='$OPVGreaterage'></td>
    <td><input type='text' readonly value='$OPVTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>12c</td>
		<td>Idadi ya watoto waliozaliwa na uzito wa < 2.5kg wakati wa KMC</td>
		<td><input type='text' readonly value='$KMCLessage'></td>
		<td><input type='text' readonly value='$KMCGreaterage'></td>
    <td><input type='text' readonly value='$KCMTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>12d</td>
		<td>Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg</td>
		<td><input type='text' readonly value='$uzitoLessage'></td>
		<td><input type='text' readonly value='$uzitoGreaterage'></td>
    <td><input type='text' readonly value='$uzitoTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>12e</td>
		<td>Idadi ya watoto waliozaliwa nyumbani walioanzishiwa huduma ya kangaroo (KMC)</td>
		<td><input type='text' readonly value='$HomeKMCLessage'></td>
		<td><input type='text' readonly value='$HomeKMCGreaterage'></td>
    <td><input type='text' readonly value='$HomeKMCTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>12f</td>
		<td>Idadi ya watoto wenye upungufu mkubwa wa damu (Hb < 10g/dl au viganja vyeupe sana)</td>
		<td><input type='text' readonly value='$HbLessage'></td>
		<td><input type='text' readonly value='$HbGreaterage'></td>
    <td><input type='text' readonly value='$HbTotal'></td>
		</tr>";

    echo "<tr>
		<th style='text-align:center'>13</th>
		<th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>UAMBUKIZO WA MTOTO</th>
		</tr>";

    echo "<tr>
		<td style='text-align:center'>13a</td>
		<td>Idadi ya watoto wenye uambukizo mkali(septicaemia)</td>
		<td><input type='text' readonly value='$septLessage'></td>
		<td><input type='text' readonly value='$septGreaterage'></td>
    <td><input type='text' readonly value='$septTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>13b</td>
		<td>Idadi ya watoto wenye uambukizo kwenye kitovu</td>
		<td><input type='text' readonly value='$kitovuLessage'></td>
		<td><input type='text' readonly value='$kitovuGreaterage'></td>
    <td><input type='text' readonly value='$kitovuTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>13c</td>
		<td>Idadi ya watoto wenye uambukizo kwenye ngozi</td>
		<td><input type='text' readonly value='$NgoziLessage'></td>
		<td><input type='text' readonly value='$NgoziGreaterage'></td>
    <td><input type='text' readonly value='$NgoziTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>13d</td>
		<td>Idadi ya watoto wenye jaundice</td>
		<td><input type='text' readonly value='$jauLessage'></td>
		<td><input type='text' readonly value='$jauGreaterage'></td>
    <td><input type='text' readonly value='$jauTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>14</td>
		<td>Vifo vya watoto wachanga waliozaliwa nyumbani (perinatal;neonatal)</td>
		<td><input type='text' readonly value='$vifoLessage'></td>
		<td><input type='text' readonly value='$vifoGreaterage'></td>
    <td><input type='text' readonly value='$vifoTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>15</td>
		<td>Waliopewa dawa ya ARV</td><td>
		<input type='text' readonly value='$ARVtotoLessage'></td>
		<td><input type='text' readonly value='$ARVtotoGreaterage'></td>
    <td><input type='text' readonly value='$ARVtotoTotal'></td>
		</tr>";

    echo "<tr>
		<th style='text-align:center'>16</th>
		<th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>ULISHAJI WA MTOTO</th>
		</tr>";

    echo "<tr>
		<td style='text-align:center'>16a</td>
		<td>Watoto wachanga wanaonyonya maziwa ya mama pekee</td>
		<td><input type='text' readonly value='$EFBtotoLessage'></td>
		<td><input type='text' readonly value='$EFBtotoGreaterage'></td>
    <td><input type='text' readonly value='$EFBtotoTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>16b</td>
		<td>Watoto wachanga wanaonyweshwa maziwa maziwa mbadala (RF)</td>
		<td><input type='text' readonly value='$RFtotoLessage'></td>
		<td><input type='text' readonly value='$RFtotoGreaterage'></td>
    <td><input type='text' readonly value='$RFtotoTotal'></td>
		</tr>

    <tr>
		<td style='text-align:center'>16c</td>
		<td>Watoto wachanga wanaonyonyeshwa maziwa ya mama na kupatiwa chakula kingine (MF)</td>
		<td><input type='text' readonly value='$MFtotoLessage'></td>
		<td><input type='text' readonly value='$MFtotoGreaterage'></td>
    <td><input type='text' readonly value='$MFtotoTotal'></td>
		</tr>    ";
    echo "</table>";
    echo "</div>";

  } ?>
