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

if(isSet($_POST['d']))
{
  $date1 = $_POST['d'];
  $date2 = $_POST['d2']; 
//  sindano
  $sindano10=0;
  $sindano15=0;
  $sindano20=0;
  $sindano25=0;
  $marudiosindano=0;
//  vidonge kituoni
  $vidongekituo10=0;
  $vidongekituo15=0;
  $vidongekituo20=0;
  $vidongekituo25=0;
  $marudiovidongekituo=0;
  
//  vidonge home
//  $vidongekituo10=0;
//  $vidongekituo15=0;
//  $vidongekituo20=0;
//  $vidongekituo25=0;
//  $marudiovidongekituo=0;
//  
//  
  
//  Huduma baada ya mimba kuharibika
  $hudumaMimbaKuharibika10=0;
  $hudumaMimbaKuharibika15=0;
  $hudumaMimbaKuharibika20=0;
  $hudumaMimbaKuharibika25=0;
  $marudiohudumaMimbaKuharibika=0;
  
  
//Waliochunguzwa titi
  $chunguzwatiti10=0;
  $chunguzwatiti15=0;
  $chunguzwatiti20=0;
  $chunguzwatiti25=0;
  $marudiochunguzwatiti=0;
  
  //Wenye matatizo ya titi
  $matatizotiti10=0;
  $matatizotiti15=0;
  $matatizotiti20=0;
  $matatizotiti25=0;
  $marudiomatatizotiti=0;
  
  //Waliochunguzwa saratani
  $saratani10=0;
  $saratani15=0;
  $saratani20=0;
  $saratani25=0;
  $marudiosaratani=0;
  
  
  //Wenye matatizo ya saratani
  $matatizosaratani10=0;
  $matatizosaratani15=0;
  $matatizosaratani20=0;
  $matatizosaratani25=0;
  $marudiomatatizosaratani=0;
  //Waliodhaniwa kuwa na saratani
  $VIAsaratani10=0;
  $VIAsaratani15=0;
  $VIAsaratani20=0;
  $VIAsaratani25=0;
  $marudioVIAsaratani=0;
  //Tiyari wana VVU
  $VVUtiyari10=0;
  $VVUtiyari15=0;
  $VVUtiyari20=0;
  $VVUtiyari25=0;
  $marudioVVUtiyari=0;
  //Waliopima VVU
  $WaliopimaVVU10=0;
  $WaliopimaVVU15=0;
  $WaliopimaVVU20=0;
  $WaliopimaVVU25=0;
  $marudioWaliopimaVVU=0;
  //Wenye VVU
  $WenyeVVUve10=0;
  $WenyeVVUve15=0;
  $WenyeVVUve20=0;
  $WenyeVVUve25=0;
  $marudioWenyeVVUve=0;
   //Wenza waliopima VVU
  $WenzaWaliopimaVVU10=0;
  $WenzaWaliopimaVVU15=0;
  $WenzaWaliopimaVVU20=0;
  $WenzaWaliopimaVVU25=0;
  $marudioWenzaWaliopimaVVU=0;
  //Wenza Wenye VVU
  $WenzaWenyeVVUve10=0;
  $WenzaWenyeVVUve15=0;
  $WenzaWenyeVVUve20=0;
  $WenzaWenyeVVUve25=0;
  $marudioWenzaWenyeVVUve=0;
  //Matokeo yanayotofautiana
  $TofautiVVU10=0;
  $TofautiVVU15=0;
  $TofautiVVU20=0;
  $TofautiVVU25=0;
  $marudioTofautiVVU=0;
  
  //Wanaume kondom kituoni
  $mekondomkituon10=0;
  $mekondomkituon15=0;
  $mekondomkituon20=0;
  $mekondomkituon25=0;
  $marudiomekondomkituon=0;
  
  //Wanawake kondom kituoni
  $kekondomkituon10=0;
  $kekondomkituon15=0;
  $kekondomkituon20=0;
  $kekondomkituon25=0;
  $marudiokekondomkituon=0;
  
  //Kufunga uzazi mama
  $Uzazimamakituon10=0;
  $Uzazimamakituon15=0;
  $Uzazimamakituon20=0;
  $Uzazimamakituon25=0;
  $marudioUzazimamakituon=0;
  
  //Kufunga uzazi Baba
  $Uzazibabakituon10=0;
  $Uzazibabakituon15=0;
  $Uzazibabakituon20=0;
  $Uzazibabakituon25=0;
  $marudioUzazibabakituon=0;
  
  //Mimba kuharibika
  $mimbakuharibika10=0;
  $mimbakuharibika15=0;
  $mimbakuharibika20=0;
  $mimbakuharibika25=0;
  $marudiomimbakuharibika=0;
  
   //FP 42hrs
  $fp42hrs10=0;
  $fp42hrs15=0;
  $fp42hrs20=0;
  $fp42hrs25=0;
  $marudiofp42hrs=0;
  
  //Kuweka vipandikizi
  $kuwekavipandikizi10=0;
  $kuwekavipandikizi15=0;
  $kuwekavipandikizi20=0;
  $kuwekavipandikizi25=0;
  $marudiokuwekavipandikizi=0;

   //Kuondoa vipandikizi
  $kuondoavipandikizi10=0;
  $kuondoavipandikizi15=0;
  $kuondoavipandikizi20=0;
  $kuondoavipandikizi25=0;
  $marudiokuondoavipandikizi=0;
  
   //Kuweka Kitanzi
  $kuwekakitanzi10=0;
  $kuwekakitanzi15=0;
  $kuwekakitanzi20=0;
  $kuwekakitanzi25=0;
  $marudiokuwekakitanzi=0;
  
   //Kuondoa Kitanzi
  $kuondoakitanzi10=0;
  $kuondoakitanzi15=0;
  $kuondoakitanzi20=0;
  $kuondoakitanzi25=0;
  $marudiokuondoakitanzi=0;
  
    //Njia nyingine
  $njianyingine10=0;
  $njianyingine15=0;
  $njianyingine20=0;
  $njianyingine25=0;
  $marudionjianyingine=0;

  $firstphase=mysqli_query($conn,"SELECT * FROM tbl_family_planing fp JOIN tbl_patient_registration tpr ON fp.Patient_ID=tpr.Registration_ID WHERE Visiting_Date BETWEEN '$date1' AND '$date2' GROUP BY tpr.Registration_ID");
  $Today = Date("Y-m-d");
  while($result= mysqli_fetch_assoc($firstphase)){
    $Date_Of_Birth = $result['Date_Of_Birth'];
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y;

    if($result['Uzazi_njia']=='Sindano' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $sindano10++;  
    }elseif ($result['Uzazi_njia']=='Sindano' &&  ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $sindano15++;       
    }elseif($result['Uzazi_njia']=='Sindano' &&  ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $sindano20++;   
    }elseif($result['Uzazi_njia']=='Sindano' &&  $age>=25 && $result['Patient_type']=='Mpya'){
     $sindano25++;   
    }
    
    if($result['Uzazi_njia']=='Sindano' && $result['Patient_type']=='Marudio'){
     $marudiosindano++;   
    }
    
// Vidonge kituoni
   if($result['Uzazi_njia']=='Sindano' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $vidongekituo10++;  
    }elseif ($result['Uzazi_njia']=='Sindano' &&  ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $vidongekituo15++;       
    }elseif($result['Uzazi_njia']=='Sindano' &&  ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $vidongekituo20++;   
    }elseif($result['Uzazi_njia']=='Sindano' &&  $age>=25 && $result['Patient_type']=='Mpya'){
     $vidongekituo25++;   
    }
    
    if($result['Uzazi_njia']=='Sindano' && $result['Patient_type']=='Marudio'){
     $marudiovidongekituo++;   
    }
    
    $TotalVidongeKituoni=$vidongekituo10+$vidongekituo15+$vidongekituo20+$vidongekituo25+$marudiovidongekituo; 
//    End here
    
    
    
    //  Waliochunguzwa titi
    if($result['Uchunguzi_matiti']=='N' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $chunguzwatiti10++;  
    }elseif ($result['Uchunguzi_matiti']=='N' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
      $chunguzwatiti15++;       
    }elseif($result['Uchunguzi_matiti']=='N' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
      $chunguzwatiti20++;   
    }elseif($result['Uchunguzi_matiti']=='N' && $age>=25 && $result['Patient_type']=='Mpya'){
     $chunguzwatiti25++;   
    }
    
    if($result['Uchunguzi_matiti']=='N' && $result['Patient_type']=='Marudio'){
     $marudiochunguzwatiti++;   
    }
    
    //Wenye matatizo ya matiti
    
    if(($result['Buje']=='N' || $result['Kidonda']=='N' || $result['Kutoka_damu']=='N' || $result['Jipu']=='N' ) &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $matatizotiti10++;  
    }elseif (($result['Buje']=='N' || $result['Kidonda']=='N' || $result['Kutoka_damu']=='N' || $result['Jipu']=='N' ) && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
      $matatizotiti15++;       
    }elseif(($result['Buje']=='N' || $result['Kidonda']=='N' || $result['Kutoka_damu']=='N' || $result['Jipu']=='N' ) && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
      $matatizotiti20++;   
    }elseif(($result['Buje']=='N' || $result['Kidonda']=='N' || $result['Kutoka_damu']=='N' || $result['Jipu']=='N' ) && $age>=25 && $result['Patient_type']=='Mpya'){
     $matatizotiti25++;   
    }
    
    if(($result['Buje']=='N' || $result['Kidonda']=='N' || $result['Kutoka_damu']=='N' || $result['Jipu']=='N' ) && $result['Patient_type']=='Marudio'){
     $marudiomatatizotiti++;   
    }
    
  
    //Waliochunguzwa saratani
    
    if($result['Uchunguzi_saratani']=='N' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $saratani10++;  
    }elseif ($result['Uchunguzi_saratani']=='N' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
      $saratani15++;       
    }elseif($result['Uchunguzi_saratani']=='N' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
      $saratani20++;   
    }elseif($result['Uchunguzi_saratani']=='N' && $age>=25 && $result['Patient_type']=='Mpya'){
     $saratani25++;   
    }
    
    if($result['Uchunguzi_saratani']=='N' && $result['Patient_type']=='Marudio'){
     $marudiosaratani++;   
    }
    
    
  //Wenye matatizo ya saratani
    if(($result['Uchafu_ukeni']=='N' || $result['Uvimbe_kizazi']=='N' || $result['Mchubuko_kizazi']=='N' || $result['Damu_ukeni']=='N' ) &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $matatizosaratani10++;  
    }elseif (($result['Uchafu_ukeni']=='N' || $result['Uvimbe_kizazi']=='N' || $result['Mchubuko_kizazi']=='N' || $result['Damu_ukeni']=='N' ) && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
      $matatizosaratani15++;       
    }elseif(($result['Uchafu_ukeni']=='N' || $result['Uvimbe_kizazi']=='N' || $result['Mchubuko_kizazi']=='N' || $result['Damu_ukeni']=='N' ) && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
      $matatizosaratani20++;   
    }elseif(($result['Uchafu_ukeni']=='N' || $result['Uvimbe_kizazi']=='N' || $result['Mchubuko_kizazi']=='N' || $result['Damu_ukeni']=='N' ) && $age>=25 && $result['Patient_type']=='Mpya'){
     $matatizosaratani25++;   
    }
    
    if(($result['Uchafu_ukeni']=='N' || $result['Uvimbe_kizazi']=='N' || $result['Mchubuko_kizazi']=='N' || $result['Damu_ukeni']=='N') && $result['Patient_type']=='Marudio'){
     $marudiomatatizosaratani++;   
    }
    
    
    
  //Waliodhaniwa kuwa na saratani  VIA
    if($result['VIA']=='N' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
      $VIAsaratani10++;  
    }elseif ($result['VIA']=='N' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
      $VIAsaratani15++;       
    }elseif($result['VIA']=='N' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
      $VIAsaratani20++;   
    }elseif($result['VIA']=='N' && $age>=25 && $result['Patient_type']=='Mpya'){
     $VIAsaratani25++;   
    }
    
    if($result['VIA']=='N' && $result['Patient_type']=='Marudio'){
     $marudioVIAsaratani++;   
    }
  
   //Tiyari wana VVU
    if($result['Ameambukizwa']=='N' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $VVUtiyari10++;  
    }elseif ($result['Ameambukizwa']=='N' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $VVUtiyari15++;       
    }elseif($result['Ameambukizwa']=='N' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $VVUtiyari20++;   
    }elseif($result['Ameambukizwa']=='N' && $age>=25 && $result['Patient_type']=='Mpya'){
    $VVUtiyari25++;   
    }

    if($result['Ameambukizwa']=='N' && $result['Patient_type']=='Marudio'){
    $marudioVVUtiyari++;   
    }
    
    
   //Waliopima VVU
    if(($result['Mama_matokeo']=='N' || $result['Mama_matokeo']=='P') &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $WaliopimaVVU10++;  
    }elseif (($result['Mama_matokeo']=='N' || $result['Mama_matokeo']=='P') && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $WaliopimaVVU15++;       
    }elseif(($result['Mama_matokeo']=='N' || $result['Mama_matokeo']=='P') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $WaliopimaVVU20++;   
    }elseif(($result['Mama_matokeo']=='N' || $result['Mama_matokeo']=='P') && $age>=25 && $result['Patient_type']=='Mpya'){
    $WaliopimaVVU25++;   
    }

    if(($result['Mama_matokeo']=='N' || $result['Mama_matokeo']=='P') && $result['Patient_type']=='Marudio'){
    $marudioWaliopimaVVU++;   
    }
    
  //Wenye VVU
    if($result['Mama_matokeo']=='P' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $WenyeVVUve10++;  
    }elseif ($result['Mama_matokeo']=='P' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $WenyeVVUve15++;       
    }elseif($result['Mama_matokeo']=='P' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $WenyeVVUve20++;   
    }elseif($result['Mama_matokeo']=='P' && $age>=25 && $result['Patient_type']=='Mpya'){
    $WenyeVVUve25++;   
    }

    if($result['Mama_matokeo']=='P' && $result['Patient_type']=='Marudio'){
    $marudioWenyeVVUve++;   
    }
    
    
    //Wenza waliopima VVU
    if(($result['Mwenza_matokeo']=='N' || $result['Mwenza_matokeo']=='P') &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $WenzaWaliopimaVVU10++;  
    }elseif (($result['Mwenza_matokeo']=='N' || $result['Mwenza_matokeo']=='P') && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $WenzaWaliopimaVVU15++;       
    }elseif(($result['Mwenza_matokeo']=='N' || $result['Mwenza_matokeo']=='P') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $WenzaWaliopimaVVU20++;   
    }elseif(($result['Mwenza_matokeo']=='N' || $result['Mwenza_matokeo']=='P') && $age>=25 && $result['Patient_type']=='Mpya'){
    $WenzaWaliopimaVVU25++;   
    }

    if(($result['Mwenza_matokeo']=='N' || $result['Mwenza_matokeo']=='P') && $result['Patient_type']=='Marudio'){
    $marudioWenzaWaliopimaVVU++;   
    }
  
  
  //Wenza Wenye VVU
   if($result['Mwenza_matokeo']=='P' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $WenzaWenyeVVUve10++;  
    }elseif ($result['Mwenza_matokeo']=='P' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $WenzaWenyeVVUve15++;       
    }elseif($result['Mwenza_matokeo']=='P' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $WenzaWenyeVVUve20++;   
    }elseif($result['Mwenza_matokeo']=='P' && $age>=25 && $result['Patient_type']=='Mpya'){
    $WenzaWenyeVVUve25++;   
    }

    if($result['Mwenza_matokeo']=='P' && $result['Patient_type']=='Marudio'){
    $marudioWenzaWenyeVVUve++;   
    } 

  //Matokeo yanayotofautiana
     if(($result['Mama_matokeo']=='N' && $result['Mama_matokeo']=='P') &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $TofautiVVU10++;  
    }elseif (($result['Mama_matokeo']=='N' && $result['Mama_matokeo']=='P') && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $TofautiVVU15++;       
    }elseif(($result['Mama_matokeo']=='N' && $result['Mama_matokeo']=='P') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $TofautiVVU20++;   
    }elseif(($result['Mama_matokeo']=='N' && $result['Mama_matokeo']=='P') && $age>=25 && $result['Patient_type']=='Mpya'){
    $TofautiVVU25++;   
    }

    if(($result['Mama_matokeo']=='N' && $result['Mama_matokeo']=='P') && $result['Patient_type']=='Marudio'){
    $marudioTofautiVVU++;   
    }
    
 
  //Wanaume kondom kituoni
   if(($result['Idadi_Kondom_Me']>0 && $result['Uzazi_njia']=='Kondomu')&&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $mekondomkituon10++;  
    }elseif (($result['Idadi_Kondom_Me']>0 && $result['Uzazi_njia']=='Kondomu')&& ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $mekondomkituon15++;       
    }elseif(($result['Idadi_Kondom_Me']>0 && $result['Uzazi_njia']=='Kondomu') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $mekondomkituon20++;   
    }elseif(($result['Idadi_Kondom_Me']>0 && $result['Uzazi_njia']=='Kondomu') && $age>=25 && $result['Patient_type']=='Mpya'){
    $mekondomkituon25++;   
    }

    if(($result['Idadi_Kondom_Me']>0 && $result['Uzazi_njia']=='Kondomu') && $result['Patient_type']=='Marudio'){
    $marudiomekondomkituon++;   
    } 

    
  if(($result['Idadi_Kondom_Ke']>0 && $result['Uzazi_njia']=='Kondomu') &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $kekondomkituon10++;  
    }elseif (($result['Idadi_Kondom_Ke']>0 && $result['Uzazi_njia']=='Kondomu') && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $kekondomkituon15++;       
    }elseif(($result['Idadi_Kondom_Ke']>0 && $result['Uzazi_njia']=='Kondomu') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $kekondomkituon20++;   
    }elseif(($result['Idadi_Kondom_Ke']>0&& $result['Uzazi_njia']=='Kondomu') && $age>=25 && $result['Patient_type']=='Mpya'){
    $kekondomkituon25++;   
    }

    if(($result['Idadi_Kondom_Ke']>0 && $result['Uzazi_njia']=='Kondomu') && $result['Patient_type']=='Marudio'){
    $marudiokekondomkituon++;   
    } 
    
   

      //Kufunga kizazi mama
   if($result['Uzazi_njia']=='Kufunga Kizazi (Ke)' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $Uzazimamakituon10++;  
    }elseif ($result['Uzazi_njia']=='Kufunga Kizazi (Ke)' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $Uzazimamakituon15++;       
    }elseif($result['Uzazi_njia']=='Kufunga Kizazi (Ke)' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $Uzazimamakituon20++;   
    }elseif($result['Uzazi_njia']=='Kufunga Kizazi (Ke)' && $age>=25 && $result['Patient_type']=='Mpya'){
    $Uzazimamakituon25++;   
    }

    if($result['Uzazi_njia']=='Kufunga Kizazi (Ke)' && $result['Patient_type']=='Marudio'){
    $marudioUzazimamakituon++;   
    }
    
 
    //Kufunga uzazi Baba
   if($result['Uzazi_njia']=='Kufunga Kizazi (Me)' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $Uzazibabakituon10++;  
    }elseif ($result['Uzazi_njia']=='Kufunga Kizazi (Me)' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $Uzazibabakituon15++;       
    }elseif($result['Uzazi_njia']=='Kufunga Kizazi (Me)' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $Uzazibabakituon20++;   
    }elseif($result['Uzazi_njia']=='Kufunga Kizazi (Me)' && $age>=25 && $result['Patient_type']=='Mpya'){
    $Uzazibabakituon25++;   
    }

    if($result['Uzazi_njia']=='Kufunga Kizazi (Me)' && $result['Patient_type']=='Marudio'){
    $marudioUzazibabakituon++;   
    }
    
//    //Mimba kuharibika
    
    if($result['Baada_Kujifungua']=='Mimba Kuharibika' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $mimbakuharibika10++;  
    }elseif ($result['Baada_Kujifungua']=='Mimba Kuharibika' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $mimbakuharibika15++;       
    }elseif($result['Baada_Kujifungua']=='Mimba Kuharibika' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $mimbakuharibika20++;   
    }elseif($result['Baada_Kujifungua']=='Mimba Kuharibika' && $age>=25 && $result['Patient_type']=='Mpya'){
    $mimbakuharibika25++;   
    }

    if($result['Baada_Kujifungua']=='Mimba Kuharibika' && $result['Patient_type']=='Marudio'){
    $marudiomimbakuharibika++;   
    }
    
    
    //FP 42hrs

    if(($result['FP_after_matibabu']=='Vidonge' || $result['FP_after_matibabu']=='Sindano' || $result['FP_after_matibabu']=='Vipandikizi' || $result['FP_after_matibabu']=='Kitanzi' || $result['FP_after_matibabu']=='Kufunga' || $result['FP_after_matibabu']=='Kondom') &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $fp42hrs10++;  
    }elseif (($result['FP_after_matibabu']=='Vidonge' || $result['FP_after_matibabu']=='Sindano' || $result['FP_after_matibabu']=='Vipandikizi' || $result['FP_after_matibabu']=='Kitanzi' || $result['FP_after_matibabu']=='Kufunga' || $result['FP_after_matibabu']=='Kondom') && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $fp42hrs15++;       
    }elseif(($result['FP_after_matibabu']=='Vidonge' || $result['FP_after_matibabu']=='Sindano' || $result['FP_after_matibabu']=='Vipandikizi' || $result['FP_after_matibabu']=='Kitanzi' || $result['FP_after_matibabu']=='Kufunga' || $result['FP_after_matibabu']=='Kondom') && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $fp42hrs20++;   
    }elseif(($result['FP_after_matibabu']=='Vidonge' || $result['FP_after_matibabu']=='Sindano' || $result['FP_after_matibabu']=='Vipandikizi' || $result['FP_after_matibabu']=='Kitanzi' || $result['FP_after_matibabu']=='Kufunga' || $result['FP_after_matibabu']=='Kondom') && $age>=25 && $result['Patient_type']=='Mpya'){
    $fp42hrs25++;   
    }

    if(($result['FP_after_matibabu']=='Vidonge' || $result['FP_after_matibabu']=='Sindano' || $result['FP_after_matibabu']=='Vipandikizi' || $result['FP_after_matibabu']=='Kitanzi' || $result['FP_after_matibabu']=='Kufunga' || $result['FP_after_matibabu']=='Kondom') && $result['Patient_type']=='Marudio'){
    $marudiofp42hrs++;   
    }
    
      //Kuweka vipandikizi
  if($result['Uzazi_njia']=='Kuweka Vipandikizi' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $kuwekavipandikizi10++;  
    }elseif ($result['Uzazi_njia']=='Kuweka Vipandikizi' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $kuwekavipandikizi15++;       
    }elseif($result['Uzazi_njia']=='Kuweka Vipandikizi' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $kuwekavipandikizi20++;   
    }elseif($result['Uzazi_njia']=='Kuweka Vipandikizi' && $age>=25 && $result['Patient_type']=='Mpya'){
    $kuwekavipandikizi25++;   
    }

    if($result['Uzazi_njia']=='Kuweka Vipandikizi' && $result['Patient_type']=='Marudio'){
    $marudiokuwekavipandikizi++;   
    }
    
    
       //Kuondoa vipandikizi

  if($result['Kuondoa']=='Kipandikizi' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $kuondoavipandikizi10++;  
    }elseif ($result['Kuondoa']=='Kipandikizi' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $kuondoavipandikizi15++;       
    }elseif($result['Kuondoa']=='Kipandikizi' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $kuondoavipandikizi20++;   
    }elseif($result['Kuondoa']=='Kipandikizi' && $age>=25 && $result['Patient_type']=='Mpya'){
    $kuondoavipandikizi25++;   
    }

    if($result['Kuondoa']=='Kipandikizi' && $result['Patient_type']=='Marudio'){
    $marudiokuondoavipandikizi++;   
    }
    
    //Kuweka Kitanzi
   if($result['Uzazi_njia']=='Kuweka Kitanzi' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $kuwekakitanzi10++;  
    }elseif ($result['Uzazi_njia']=='Kuweka Kitanzi' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $kuwekakitanzi15++;       
    }elseif($result['Uzazi_njia']=='Kuweka Kitanzi' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $kuwekakitanzi20++;   
    }elseif($result['Uzazi_njia']=='Kuweka Kitanzi' && $age>=25 && $result['Patient_type']=='Mpya'){
    $kuwekakitanzi25++;   
    }

    if($result['Uzazi_njia']=='Kuweka Kitanzi' && $result['Patient_type']=='Marudio'){
    $marudiokuwekakitanzi++;   
    }
    
    
    //Kuondoa Kitanzi
  
    if($result['Kuondoa']=='Kitanzi' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $kuondoakitanzi10++;  
    }elseif ($result['Kuondoa']=='Kitanzi' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $kuondoakitanzi15++;       
    }elseif($result['Kuondoa']=='Kitanzi' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $kuondoakitanzi20++;   
    }elseif($result['Kuondoa']=='Kitanzi' && $age>=25 && $result['Patient_type']=='Mpya'){
    $kuondoakitanzi25++;   
    }

    if($result['Kuondoa']=='Kitanzi' && $result['Patient_type']=='Marudio'){
    $marudiokuondoakitanzi++;   
    }
    
  //Njia nyingine
  
  
  
  
  
  
  
  if($result['Uzazi_njia']=='Njia nyingine' &&  ($age>=10 && $age<=14) && $result['Patient_type']=='Mpya'){
     $njianyingine10++;  
    }elseif ($result['Uzazi_njia']=='Njia nyingine' && ($age>=15 && $age<=19) && $result['Patient_type']=='Mpya') {
     $njianyingine15++;       
    }elseif($result['Uzazi_njia']=='Njia nyingine' && ($age>=20 && $age<=24) && $result['Patient_type']=='Mpya'){
     $njianyingine20++;   
    }elseif($result['Uzazi_njia']=='Njia nyingine' && $age>=25 && $result['Patient_type']=='Mpya'){
    $njianyingine25++;   
    }

    if($result['Uzazi_njia']=='Njia nyingine' && $result['Patient_type']=='Marudio'){
    $marudionjianyingine++;   
    }
    


 }
 
 
    $sindanoTotal=$sindano10+$sindano15+$sindano20+$sindano25;
    $chunguzwatitiTotal=$chunguzwatiti10+$chunguzwatiti15+$chunguzwatiti20+$chunguzwatiti25+$marudiochunguzwatiti;
    $matatizotitiTotal=$matatizotiti10+$matatizotiti15+$matatizotiti20+$matatizotiti25+$marudiomatatizotiti;
    $sarataniTotal=$saratani10+$saratani15+$saratani20+$saratani25+$marudiosaratani;
    $matatizosarataniTotal=$matatizosaratani10+$matatizosaratani15+$matatizosaratani20+$matatizosaratani25+$marudiomatatizosaratani;
    $VIAsarataniTotal=$VIAsaratani10+$VIAsaratani15+$VIAsaratani20+$VIAsaratani25+$marudioVIAsaratani;
    $VVUtiyariTotal=$VVUtiyari10+$VVUtiyari15+$VVUtiyari20+$VVUtiyari25+$marudioVVUtiyari;
    $WaliopimaVVUTotal=$WaliopimaVVU10+$WaliopimaVVU15+$WaliopimaVVU20+$WaliopimaVVU25+$marudioWaliopimaVVU;
    $WenyeVVUveTotal=$WenyeVVUve10+$WenyeVVUve15+$WenyeVVUve20+$WenyeVVUve25+$marudioWenyeVVUve;
    $WenzaWaliopimaVVUTotal=$WenzaWaliopimaVVU10+$WenzaWaliopimaVVU15+$WenzaWaliopimaVVU20+$WenzaWaliopimaVVU25+$marudioWenzaWaliopimaVVU;
    $WenzaWenyeVVUveTotal=$WenzaWenyeVVUve10+$WenzaWenyeVVUve15+$WenzaWenyeVVUve20+$WenzaWenyeVVUve25+$marudioWenzaWenyeVVUve;
    $TofautiVVUTotal=$TofautiVVU10+$TofautiVVU15+$TofautiVVU20+$TofautiVVU25+$marudioTofautiVVU;
    $TotalmeKondomKituoni=$mekondomkituon10+$mekondomkituon15+$mekondomkituon20+$mekondomkituon25+$marudiomekondomkituon;
    $TotalkeKondomKituoni=$kekondomkituon10+$kekondomkituon15+$kekondomkituon20+$kekondomkituon25+$marudiokekondomkituon;
    $TotalUzazimama=$Uzazimamakituon10+$Uzazimamakituon15+$Uzazimamakituon20+$Uzazimamakituon25+$marudioUzazimamakituon;
    $Totaluzazibaba=$Uzazibabakituon10+$Uzazibabakituon15+$Uzazibabakituon20+$Uzazibabakituon25+$marudioUzazibabakituon;
    $TotalmimbaKuharibika=$mimbakuharibika10+$mimbakuharibika15+$mimbakuharibika20+$mimbakuharibika25+$marudiomimbakuharibika;
    $Totalfp42hrs=$fp42hrs10+$fp42hrs15+$fp42hrs20+$fp42hrs25+$marudiofp42hrs;
    $TotalKuwekavipandikizi=$kuwekavipandikizi10+$kuwekavipandikizi15+$kuwekavipandikizi20+$kuwekavipandikizi25+$marudiokuwekavipandikizi;
    $TotalKuondoavipandikizi=$kuondoavipandikizi10+$kuondoavipandikizi15+$kuondoavipandikizi20+$kuondoavipandikizi25+$marudiokuondoavipandikizi;
    $Totalkuwekakitanzi=$kuwekakitanzi10+$kuwekakitanzi15+$kuwekakitanzi20+$kuwekakitanzi25+$marudiokuwekakitanzi;
    $TotalKuondoakitanzi=$kuondoakitanzi10+$kuondoakitanzi15+$kuondoakitanzi20+$kuondoakitanzi25+$marudiokuondoakitanzi;
    $TotalNjianyingine=$njianyingine10+$njianyingine15+$njianyingine20+$njianyingine25+$marudionjianyingine;

    
    
    
  
     
     
 
    echo "<div id='all'>";
    echo "<div id='hudhurio1'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Na</th><th>Aina ya Huduma</th><th>Aina ya Wateja</th></tr>";
    echo "<tr><th></th><th>Maelezo</th><th>Wateja wapya</th><th>Marudio</th><th>Jumla</th></tr>";
    echo "<tr><th></th><th></th><th><table><tr><th style='width:50px;text-align:center'>Miaka<br />10-14</th><th style='width:50px;text-align:center'>Miaka<br />15-19</th><th style='width:50px;text-align:center'>Miaka<br />20-24</th><th style='text-align:center'>Miaka<br />25 na Zaidi</th></tr></table></th></tr>";
    
    
     echo "<tr><th style='text-align:center;background-color:#006400;color:white;padding:2px'>1</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Idadi ya wateja waliopatiwa unasihi (MPYA)</th></tr>";
     echo "<tr><th style='text-align:center;background-color:#006400;color:white;padding:2px'>1</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Sindano</th></tr>";
     
     
     echo "<tr><td style='text-align:center'>1a</td><td>Idadi ya Wateja wa Sindano</td><td><table><tr><td style='width:60px;text-align:center'>$sindano10</td><td style='width:60px;text-align:center'>$sindano15</td><td style='width:60px;text-align:center'>$sindano20</td><td style='width:60px;text-align:center'>$sindano25</td></tr></table></td><td><center>$marudiosindano</center></td><td><center>$sindanoTotal</center></td></tr>";
     
     echo "<tr><th style='text-align:center;background-color:#006400;color:white;padding:2px'>2</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Vidonge</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>2a</td><td>Idadi ya wateja wa Vidonge Kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$sindano10</td><td style='width:60px;text-align:center'>$sindano15</td><td style='width:60px;text-align:center'>$sindano20</td><td style='width:60px;text-align:center'>$sindano25</td></tr></table></td><td></td>
     
     <td></td></tr>
     
    <tr><td style='text-align:center'>2b</td><td>Idadi ya wateja wa Vidonge wa CBD</td><td></td><td></td>
     
     <td></td></tr>";
        
      echo "<tr><th style='background-color:#006400;color:white;padding:2px;text-align:center'>3</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Kondom</th></tr>";

      echo "<tr><td style='text-align:center'>3a</td><td>Idadi ya wateja waliochukua kondom Kituoni Me</td><td></td><td></td>
     
     <td></td></tr>
  
     
     <tr><td style='text-align:center'>3b</td><td>Idadi ya wateja waliochukua kondom Kituoni Ke</td><td></td><td></td>
     
     <td></td></tr>

 
     <tr><td style='text-align:center'>3c</td><td>Idadi ya wateja waliochukua kondom CBD Me</td><td></td><td></td>
     
     <td></td></tr>
   
     <tr><td style='text-align:center'>3d</td><td>Idadi ya wateja waliochukua kondom CBD Ke</td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'></td><td><b>Jumla ya Wateja waliochukua njia za muda mfupi(1a+2+3)<b /></td><td></td><td></td>
     
     <td></td></tr>
    
      ";

    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>4</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Njia za muda Mrefu na za kudumu za Uzazi wa Mpango</th></tr>";
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>4</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Kufunga Uzazi</th></tr>";

    echo "<tr><td style='text-align:center'>4a</td><td>Kufunga uzazi mama (ML/LA)-kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$Uzazimamakituon10</td><td style='width:60px;text-align:center'>$Uzazimamakituon15</td><td style='width:60px;text-align:center'>$Uzazimamakituon20</td><td style='width:60px;text-align:center'>$Uzazimamakituon25</td></tr></table></td><td><center>$marudioUzazimamakituon</center></td>
     
     <td><center>$TotalUzazimama</center></td></tr>

     <tr><td style='text-align:center'>4b</td><td>Kufunga uzazi mama (ML/LA)-outreach</td><td></td><td></td>
     
     <td></td></tr>

    <tr><td style='text-align:center'>4c</td><td>Kufunga uzazi baba (NSV)-kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$Uzazibabakituon10</td><td style='width:60px;text-align:center'>$Uzazibabakituon15</td><td style='width:60px;text-align:center'>$Uzazibabakituon20</td><td style='width:60px;text-align:center'>$Uzazibabakituon25</td></tr></table></td><td><center>$marudioUzazibabakituon</center></td>
     
     <td><center>$Totaluzazibaba</center></td></tr>
         
     <tr><td style='text-align:center'>4d</td><td>Kufunga uzazi baba (NSV)-outreach</td><td></td><td></td>
     
     <td></td></tr>
";
    
      echo "<tr><th style='background-color:#006400;color:white;text-align:center'>5</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Vipandikizi</th></tr>";
      echo "<tr><td style='text-align:center'>5a</td><td>Kuweka vipandikizi-kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$kuwekavipandikizi10</td><td style='width:60px;text-align:center'>$kuwekavipandikizi15</td><td style='width:60px;text-align:center'>$kuwekavipandikizi20</td><td style='width:60px;text-align:center'>$kuwekavipandikizi25</td></tr></table></td><td><center>$marudiokuwekavipandikizi</center></td>
     
     <td><center>$TotalKuwekavipandikizi</center></td></tr>

    <tr><td style='text-align:center'>5b</td><td>Kuweka vipandikizi-outreach</td><td></td><td></td>
     
     <td></td></tr>

     <tr><td style='text-align:center'>5c</td><td>Kuondoa vipandikizi</td><td><table><tr><td style='width:60px;text-align:center'>$kuondoavipandikizi10</td><td style='width:60px;text-align:center'>$kuondoavipandikizi15</td><td style='width:60px;text-align:center'>$kuondoavipandikizi20</td><td style='width:60px;text-align:center'>$kuondoavipandikizi25</td></tr></table></td><td><center>$marudiokuondoavipandikizi</center></td>
     
     <td><center>$TotalKuondoavipandikizi</center></td></tr>
    
   
    ";

     
     echo "<tr><th style='background-color:#006400;color:white;text-align:center'>6</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Kitanzi(IUCD)</th></tr>";

     echo "<tr><td style='text-align:center'>6a</td><td>Kuweka Kitanzi-kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$kuwekakitanzi10</td><td style='width:60px;text-align:center'>$kuwekakitanzi15</td><td style='width:60px;text-align:center'>$kuwekakitanzi20</td><td style='width:60px;text-align:center'>$kuwekakitanzi25</td></tr></table></td><td><center>$marudiokuwekakitanzi</center></td>
     
     <td><center>$Totalkuwekakitanzi</center></td></tr>
     
     <tr><td style='text-align:center'>6b</td><td>Kuweka Kitanzi-outreach</td><td></td><td></td><td></td></tr>
    
     <tr><td style='text-align:center'>6c</td><td>Kuondoa Kitanzi-kituoni</td><td><table><tr><td style='width:60px;text-align:center'>$kuondoakitanzi10</td><td style='width:60px;text-align:center'>$kuondoakitanzi15</td><td style='width:60px;text-align:center'>$kuondoakitanzi20</td><td style='width:60px;text-align:center'>$kuondoakitanzi25</td></tr></table></td><td><center></center></td>
     
     <td><center>$TotalKuondoakitanzi</center></td></tr>
     
     <tr><td style='text-align:center'>6d</td><td>Kuondoa Kitanzi-outreach</td><td></td><td></td><td></td></tr>

     <tr><td style='text-align:center'></td><td><b>Jumla ya Wateja Waliochukua Njia za Muda Mrefu na za Kudumu za Uzazi wa Mpango(4a+4b+4c+4d+5a+5b+6a+6b)</b></td><td></td><td></td>
     
     <td></td></tr>
    ";
     
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>7</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Njia Nyinginezo</th></tr>";
    
    echo "<tr><td style='text-align:center'>7a</td><td>Njia za maumbile mfano (LAM)</td><td></td><td></td>
     
     <td></td></tr>

     <tr><td style='text-align:center'>7b</td><td>Njia nyingine</td><td><table><tr><td style='width:60px;text-align:center'>$njianyingine10</td><td style='width:60px;text-align:center'>$njianyingine15</td><td style='width:60px;text-align:center'>$njianyingine20</td><td style='width:60px;text-align:center'>$njianyingine25</td></tr></table></td><td><center>$marudionjianyingine</center></td>
     
     <td><center>$TotalNjianyingine</center></td></tr>
   
     <tr><td style='text-align:center'>7c</td><td>Njia ya dharura ya uzazi wa mpango</td><td></td><td></td><td></td></tr>
    

    ";
     
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'></th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Jumla ya Aina zote za Uzazi wa Mpango</th></tr>";
    echo "<tr><td style='text-align:center'></td><td>Jumla ya Wateja wa njia zote za Uzazi wa Mpango</td><td></td><td></td>
     
     <td></td></tr>
     
    ";
    
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>8</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Jumla ya Mizunguko ya Vidonge Iliyotolewa</th></tr>";
    echo "<tr><td style='text-align:center'>8a</td><td>Jumla ya mizunguko iliyotolewa</td><td></td><td></td>
     
     <td></td></tr>
     
    <tr><td style='text-align:center'>8b</td><td>Idadi ya mizunguko iliyotolewa (cycles distributed) kituoni</td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'>8c</td><td>Idadi ya mizunguko iliyotolewa (cycles distributed) CBD</td><td></td><td></td>
     
     <td></td></tr>
     
    ";
       
  
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>9</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Kondomu zilizotolewa</th></tr>";
    echo "<tr><td style='text-align:center'>9a</td><td>Idadi ya kondomu zilizogawiwa Kituoni Me</td><td><table><tr><td style='width:60px;text-align:center'>$mekondomkituon10</td><td style='width:60px;text-align:center'>$mekondomkituon15</td><td style='width:60px;text-align:center'>$mekondomkituon20</td><td style='width:60px;text-align:center'>$mekondomkituon25</td></tr></table></td><td><center>$marudiomekondomkituon</center></td>
     
     <td><center>$TotalmeKondomKituoni</center></td></tr>
     
    <tr><td style='text-align:center'>9b</td><td>Idadi ya kondomu zilizogawiwa Kituoni Ke</td><td><table><tr><td style='width:60px;text-align:center'>$kekondomkituon10</td><td style='width:60px;text-align:center'>$kekondomkituon15</td><td style='width:60px;text-align:center'>$kekondomkituon20</td><td style='width:60px;text-align:center'>$kekondomkituon25</td></tr></table></td><td><center>$marudiokekondomkituon</center></td>
     
     <td><center>$TotalkeKondomKituoni</center></td></tr>
     
     <tr><td style='text-align:center'>9c</td><td>Idadi ya kondomu zilizogawiwa CBD Me</td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'>9d</td><td>Idadi ya kondomu zilizogawiwa CBD Ke</td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'></td><td><b>Jumla ya wateja waliopatiwa huduma kituoni(1a+2a+3a+3b+4a+4c+5a+6a)</b></td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'></td><td><b>Jumla ya wateja waliopatiwa huduma outreach(1a+2a+3a+3b+4a+4c+5a+6a)</b></td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><b><td style='text-align:center'></td><td><b>Jumla ya wateja waliopatiwa huduma na CBD(2b+3c+3b)</b></td><td></td><td></td>
     
     <td></td></b></tr>
     
    ";
    
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>10</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Huduma baada ya mimba kuharibika(cPAC)/ Baada ya kujifungua</th></tr>";

    echo "<tr><td style='text-align:center'>10a</td><td>Waliopata huduma baada ya mimba kuharibika</td><td><table><tr><td style='width:60px;text-align:center'>$mimbakuharibika10</td><td style='width:60px;text-align:center'>$mimbakuharibika15</td><td style='width:60px;text-align:center'>$mimbakuharibika20</td><td style='width:60px;text-align:center'>$mimbakuharibika25</td></tr></table></td><td><center>$marudiomimbakuharibika</center></td>
     
     <td><center>$TotalmimbaKuharibika</center></td></tr>
     
    <tr><td style='text-align:center'>10b</td><td>Waliopata uzazi wa mpango baada ya mimba kuharibika</td><td><table><tr><td style='width:60px;text-align:center'>$mimbakuharibika10</td><td style='width:60px;text-align:center'>$mimbakuharibika15</td><td style='width:60px;text-align:center'>$mimbakuharibika20</td><td style='width:60px;text-align:center'>$mimbakuharibika25</td></tr></table></td><td><center>$marudiomimbakuharibika</center></td>
     
     <td><center>$TotalmimbaKuharibika</center></td></tr>
         
    
     <tr><td style='text-align:center'>10c</td><td>Waliopata njia ya uzazi wa mpango siku 42 baada ya kujifungua</td><td><table><tr><td style='width:60px;text-align:center'>$fp42hrs10</td><td style='width:60px;text-align:center'>$fp42hrs15</td><td style='width:60px;text-align:center'>$fp42hrs20</td><td style='width:60px;text-align:center'>$fp42hrs25</td></tr></table></td><td><center>$marudiofp42hrs</center></td>
     
     <td><center>$Totalfp42hrs</center></td></tr>";
    
    
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>11</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Huduma baada ya mimba kuharibika(cPAC)/ Baada ya kujifungua</th></tr>";
    echo "<tr><td style='text-align:center'>11a</td><td>Waliochunguzwa Titi</td><td><table><tr><td style='width:60px;text-align:center'>$chunguzwatiti10</td><td style='width:60px;text-align:center'>$chunguzwatiti15</td><td style='width:60px;text-align:center'>$chunguzwatiti20</td><td style='width:60px;text-align:center'>$chunguzwatiti25</td></tr></table></td><td><center>$marudiochunguzwatiti</center></td>
     
     <td><center>$chunguzwatitiTotal</center></td></tr>
     
    <tr><td style='text-align:center'>11b</td><td>Waliogundulika na matatizo ya matiti(<i>mfano:kutoka damu kwenye chuchu au uvimbe wa matiti</i>)</td><td><table><tr><td style='width:60px;text-align:center'>$matatizotiti10</td><td style='width:60px;text-align:center'>$matatizotiti15</td><td style='width:60px;text-align:center'>$matatizotiti20</td><td style='width:60px;text-align:center'>$matatizotiti25</td></tr></table></td><td><center>$marudiomatatizotiti</center></td>
     
     <td><center>$matatizotitiTotal</center></td></tr>
      
    
     <tr><td style='text-align:center'>11c</td><td>Waliochunguzwa Shingo ya mfuko wa kizazi</td><td><table><tr><td style='width:60px;text-align:center'>$saratani10</td><td style='width:60px;text-align:center'>$saratani15</td><td style='width:60px;text-align:center'>$saratani20</td><td style='width:60px;text-align:center'>$saratani25</td></tr></table></td><td><center>$marudiosaratani</center></td>
     
     <td><center>$sarataniTotal</center></td></tr>
     
 

     <tr><td style='text-align:center'>11d</td><td>Waliogundulika na matatizo ya shingo ya mfuko wa kizazi(<i>mfano:mchubuko au kidonda)</i></td><td><table><tr><td style='width:60px;text-align:center'>$matatizosaratani10</td><td style='width:60px;text-align:center'>$matatizosaratani15</td><td style='width:60px;text-align:center'>$matatizosaratani20</td><td style='width:60px;text-align:center'>$matatizosaratani25</td></tr></table></td><td><center>$marudiomatatizosaratani</center></td>
     
     <td><center>$matatizosarataniTotal</center></td></tr>
     

     <tr><td style='text-align:center'>11d</td><td>Waliodhaniwa wana saratani ya shingo ya mfuko wa kizazi</td><td><table><tr><td style='width:60px;text-align:center'>$VIAsaratani10</td><td style='width:60px;text-align:center'>$VIAsaratani15</td><td style='width:60px;text-align:center'>$VIAsaratani20</td><td style='width:60px;text-align:center'>$VIAsaratani25</td></tr></table></td><td><center>$marudioVIAsaratani</center></td>
     
     <td><center>$VIAsarataniTotal</center></td></tr>
     ";
    
    
     
    echo "<tr><th style='background-color:#006400;color:white;text-align:center'>12</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>PITC</th></tr>";
    echo "<tr><td style='text-align:center'>12a</td><td>Tayari wana uambukizo wa VVU</td><td><table><tr><td style='width:60px;text-align:center'>$VVUtiyari10</td><td style='width:60px;text-align:center'>$VVUtiyari15</td><td style='width:60px;text-align:center'>$VVUtiyari20</td><td style='width:60px;text-align:center'>$VVUtiyari25</td></tr></table></td><td><center>$marudioVVUtiyari</center></td>
     
     <td><center>$VVUtiyariTotal</center></td></tr>
    
    <tr><td style='text-align:center'>12b</td><td>Wateja waliopima VVU</td><td><table><tr><td style='width:60px;text-align:center'>$WaliopimaVVU10</td><td style='width:60px;text-align:center'>$WaliopimaVVU15</td><td style='width:60px;text-align:center'>$WaliopimaVVU20</td><td style='width:60px;text-align:center'>$WaliopimaVVU25</td></tr></table></td><td><center>$marudioWaliopimaVVU</center></td>
     
     <td><center>$WaliopimaVVUTotal</center></td></tr>
       
  
     <tr><td style='text-align:center'>12c</td><td>Wateja waliogundulika Positive (+)</td><td><table><tr><td style='width:60px;text-align:center'>$WenyeVVUve10</td><td style='width:60px;text-align:center'>$WenyeVVUve15</td><td style='width:60px;text-align:center'>$WenyeVVUve20</td><td style='width:60px;text-align:center'>$WaliopimaVVU25</td></tr></table></td><td><center>$marudioWenyeVVUve</center></td>
     
     <td><center>$WenyeVVUveTotal</center></td></tr>
     
     <tr><td style='text-align:center'>12d</td><td>Wenza waliopima VVU</td><td><table><tr><td style='width:60px;text-align:center'>$WenzaWaliopimaVVU10</td><td style='width:60px;text-align:center'>$WenzaWaliopimaVVU15</td><td style='width:60px;text-align:center'>$WenzaWaliopimaVVU20</td><td style='width:60px;text-align:center'>$WenzaWaliopimaVVU25</td></tr></table></td><td><center>$marudioWenzaWaliopimaVVU</center></td>
     
     <td><center>$WenzaWaliopimaVVUTotal</center></td></tr>
         
    
  
  
     <tr><td style='text-align:center'>12e</td><td>Wenza waliogundulika Positive (+)</td><td><table><tr><td style='width:60px;text-align:center'>$WenzaWenyeVVUve10</td><td style='width:60px;text-align:center'>$WenzaWenyeVVUve15</td><td style='width:60px;text-align:center'>$WenzaWenyeVVUve20</td><td style='width:60px;text-align:center'>$WenzaWenyeVVUve25</td></tr></table></td><td><center>$marudioWenzaWenyeVVUve</center></td>
     
     <td><center>$WenzaWenyeVVUveTotal</center></td></tr>
     <tr><td style='text-align:center'>12f</td><td>Wateja ambao matokeo ya vipimo vya VVU yanatofautiana</td><td><table><tr><td style='width:60px;text-align:center'>$TofautiVVU10</td><td style='width:60px;text-align:center'>$TofautiVVU15</td><td style='width:60px;text-align:center'>$TofautiVVU20</td><td style='width:60px;text-align:center'>$TofautiVVU25</td></tr></table></td><td><center>$marudioTofautiVVU</center></td>
     
     <td><center>$TofautiVVUTotal</center></td></tr>
       
  
     <tr><td style='text-align:center'>12g</td><td>Wateja waliopata rufaa kwenda CTC</td><td></td><td></td>
     
     <td></td></tr>
     
     <tr><td style='text-align:center'>12h</td><td>Wenza waliopata rufaa kwenda CTC</td><td></td><td></td>
     
     <td></td></tr>
     ";
     echo "<tr><th style='background-color:#006400;color:white;text-align:center'></th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Maudhui Madogo madogo/madhara ya dawa za Uzazi wa Mpango</th></tr>";
    
    echo "</table>";
    echo "</div>";
    
  } ?>
  
  