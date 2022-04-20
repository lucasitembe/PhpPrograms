<?php
    include("includes/connection.php");
    $from_date=mysqli_real_escape_string($conn,$_GET['from_date']);
    $to_date=mysqli_real_escape_string($conn,$_GET['to_date']);
          
  $date1 = $_POST['d'];
  $date2 = $_POST['d2'];
  $hudhurioageless=0;
  $hudhurioagegreater=0;
  $hudhurioageless3days=0;
  $hudhurioagegreater3days=0;
  $ndanidaysless=0;
  $ndanidaysgreater=0;
  $Total7days=0;
  $totalhudhurio24hrs=0;
  $totalhudhurio3days=0;
  $damuLessage=0;
  $damuGreaterage=0;
  $damuLessTotal=0;
  $akiliLessage=0;
  $akiliGreaterage=0;
  $akiliLessTotal=0;
  $vitaminLessage=0;
  $vitaminGreaterage=0;
  $vitaminTotal=0;
  $msambaLessage=0;
  $msambaGreaterage=0;
  $msambaTotal=0;
  $fistulaLessage=0;
  $fistulaGreaterage=0;
  $fistulaTotal=0;
  $BBALessage=0;
  $BBAGreaterage=0;
  $BBATotal=0;
  $TBALessage=0;
  $TBAGreaterage=0;
  $TBATotal=0;
  $HLessage=0;
  $HGreaterage=0;
  $HTotal=0;
  $FPLessage=0;
  $FPGreaterage=0;
  $FPTotal=0;
  $FPIECLessage=0;
  $FPIECGreaterage=0;
  $FPIECTotal=0;
  $FPPPCLessage=0;
  $FPPPCGreaterage=0;
  $FPPPCTotal=0;
  $FPrufaaLessage=0;
  $FPrufaaGreaterage=0;
  $FPrufaaTotal=0;
  $KVVULessage=0;
  $KVVUGreaterage=0;
  $KVVUTotal=0;
  $KPVVULessage=0;
  $KPVVUGreaterage=0;
  $KPVVUTotal=0;
  $HPVVULessage=0;
  $HPVVUGreaterage=0;
  $HPVVUTotal=0;
  $VVUEBFLessage=0;
  $VVUEBFGreaterage=0;
  $VVUEBFTotal=0;
  $VVURFLessage=0;
  $VVURFGreaterage=0;
  $VVURFTotal=0;
  $hudhurioMtotoLessage=0;
  $hudhurioMtotoGreaterage=0;
  $hudhurioMtotoTotal=0;
  $hudhuriosiku3MtotoLessage=0;
  $hudhuriosiku3MtotoGreaterage=0;
  $hudhuriosiku3MtotoTotal=0;
  
  $BCGLessage=0;
  $BCGGreaterage=0;
  $BCGTotal=0;
  $OPVLessage=0;
  $OPVGreaterage=0;
  $OPVTotal=0;
  $KMCLessage=0;
  $KMCGreaterage=0;
  $KCMTotal=0;
  $uzitoLessage=0;
  $uzitoGreaterage=0;
  $uzitoTotal=0;
  $HomeKMCLessage=0;
  $HomeKMCGreaterage=0;
  $HomeKMCTotal=0;
  $HbLessage=0;
  $HbGreaterage=0;
  $HbTotal=0;
  $septLessage=0;
  $septGreaterage=0;
  $septTotal=0;
  $kitovuLessage=0;
  $kitovuGreaterage=0;
  $kitovuTotal=0;
  $NgoziLessage=0;
  $NgoziGreaterage=0;
  $NgoziTotal=0;
  $jauLessage=0;
  $jauGreaterage=0;
  $jauTotal=0;
  $vifoLessage=0;
  $vifoGreaterage=0;
  $vifoTotal=0;
  $ARVtotoLessage=0;
  $ARVtotoGreaterage=0;
  $ARVtotoTotal=0;
  $EFBtotoLessage=0;
  $EFBtotoGreaterage=0;
  $EFBtotoTotal=0;
  $RFtotoLessage=0;
  $RFtotoGreaterage=0;
  $RFtotoTotal=0;
  $MFtotoLessage=0;
  $MFtotoGreaterage=0;
  $MFtotoTotal=0;
  $firstphase=mysqli_query($conn,"SELECT * FROM tbl_postnal tp JOIN tbl_patient_registration tpr ON tp.Mother_ID=tpr.Registration_ID WHERE Hudhurio_Date BETWEEN '$from_date' AND '$to_date'");
  $Today = Date("Y-m-d");
  while($result=  mysqli_fetch_assoc($firstphase)){
    $Date_Of_Birth = $result['Date_Of_Birth'];
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y;

    
     if ($result['Hudhurio']=='Masaa 48' && $age < 20){
       $hudhurioageless++;   
     
      } else if($result['Hudhurio']=='Masaa 48'&& $age>=20){
        $hudhurioagegreater++;  
      };
      
      
      if ($result['Hudhurio']=='Siku 3-7' && $age < 20){
       $hudhurioageless3days++;
   
      } else if($result['Hudhurio']=='Siku 3-7'&& $age>=20){
        $hudhurioagegreater3days++;  
      };
      
      if($result['HB']<8.5 && $age < 20 ){
         $damuLessage++; 
      }elseif($result['HB']<8.5 && $age>=20){
         $damuGreaterage++; 
      }
   
      if($result['Akili_Timamu']=='H' && $age < 20){
          $akiliLessage++;
      }elseif ($result['Akili_Timamu']=='H' && $age>=20) {
          $akiliGreaterage++;  
        }
  
        if($result['Idadi_Vitamin']!='' && $age < 20){
          $vitaminLessage++;
         }elseif ($result['Idadi_Vitamin']!='' && $age>=20) {
          $vitaminGreaterage++;  
        }
        
        if($result['Msamba_hali']=='Umeachia' && $age < 20){
          $msambaLessage++;
         }elseif ($result['Msamba_hali']=='Umeachia' && $age>=20) {
          $msambaGreaterage++;  
        }
        
         if($result['Fistula']=='N' && $age < 20){
          $fistulaLessage++;
         }elseif ($result['Fistula']=='N' && $age>=20) {
          $fistulaGreaterage++;  
        }
        
        
        if($result['Mahali_Alipojifungulia']=='BBA' && $age < 20){
          $BBALessage++;
         }elseif ($result['Mahali_Alipojifungulia']=='BBA' && $age>=20) {
          $BBAGreaterage++;  
        }
        
        if($result['Mahali_Alipojifungulia']=='TBA' && $age < 20){
          $TBALessage++;
         }elseif ($result['Mahali_Alipojifungulia']=='TBA' && $age>=20) {
          $TBAGreaterage++;  
        }
        
        if($result['Mahali_Alipojifungulia']=='H' && $age < 20){
          $HLessage++;
         }elseif ($result['Mahali_Alipojifungulia']=='H' && $age>=20) {
          $HGreaterage++;  
        }
        
        
         if($result['Family_planing']=='Ushauri umetolewa' && $age < 20){
          $FPLessage++;
         }elseif ($result['Family_planing']=='Ushauri umetolewa' && $age>=20) {
          $FPGreaterage++;  
        }
         if($result['Family_planing']=='Amepatiwa kielelezo(IEC material)' && $age < 20){
          $FPIECLessage++;
         }elseif ($result['Family_planing']=='Amepatiwa kielelezo(IEC material)' && $age>=20) {
          $FPIECGreaterage++;  
        }
        
        if($result['Family_planing']=='Amepata njia ya uzazi wa mpango wakati wa PPC' && $age < 20){
          $FPPPCLessage++;
         }elseif ($result['Family_planing']=='Amepata njia ya uzazi wa mpango wakati wa PPC' && $age>=20) {
          $FPPPCGreaterage++;  
        }
        
         if($result['Family_planing']=='Amepata rufaa kupata njia ya uzazi wa mpango' && $age < 20){
          $FPrufaaLessage++;
         }elseif ($result['Family_planing']=='Amepata rufaa kupata njia ya uzazi wa mpango' && $age>=20) {
          $FPrufaaGreaterage++;  
        }
        
        if($result['Kipimo_VVU']!='' && $age < 20){
          $KVVULessage++;
         }elseif ($result['Kipimo_VVU']!='' && $age>=20) {
          $KVVUGreaterage++;  
        }
        

         if($result['Kipimo_VVU']=='P' && $age < 20){
          $KPVVULessage++;
         }elseif ($result['Kipimo_VVU']=='P' && $age>=20) {
          $KPVVUGreaterage++;  
        }
        
         if($result['Hali_ya_VVU']=='P' && $age < 20){
          $HPVVULessage++;
         }elseif ($result['Hali_ya_VVU']=='P' && $age>=20) {
          $HPVVUGreaterage++;  
        }
 
         if(($result['Hali_ya_VVU']=='P' || $result['Kipimo_VVU']=='P') && $result['Ulishaji_wa_mtoto']=='EBF' && $age < 20){
          $VVUEBFLessage++;
         }elseif (($result['Hali_ya_VVU']=='P' || $result['Kipimo_VVU']=='P') && $result['Ulishaji_wa_mtoto']=='EBF' && $age>=20) {
          $VVUEBFGreaterage++;  
        }
        

        if(($result['Hali_ya_VVU']=='P' || $result['Kipimo_VVU']=='P') && ($result['Ulishaji_wa_mtoto']=='RF') && ($age < 20)){
          $VVURFLessage++;
         } elseif (($result['Hali_ya_VVU']=='P' || $result['Kipimo_VVU']=='P') && ($result['Ulishaji_wa_mtoto']=='RF') && ($age>=20)) {
          $VVURFGreaterage++;  
        }
        
        if($result['Hudhurio_la_mtoto']=='Masaa 48' && $age < 20){
          $hudhurioMtotoLessage++;
         }elseif ($result['Hudhurio_la_mtoto']=='Masaa 48' && $age>=20) {
          $hudhurioMtotoGreaterage++;  
        }
        
        
         if($result['Hudhurio_la_mtoto']=='Siku 3-7' && $age < 20){
          $hudhuriosiku3MtotoLessage++;
         }elseif ($result['Hudhurio_la_mtoto']=='Siku 3-7' && $age>=20) {
          $hudhuriosiku3MtotoGreaterage++;  
        }


        if($result['Chanjo']=='BCG' && $age < 20){
          $BCGLessage++;
         }elseif ($result['Chanjo']=='BCG' && $age>=20) {
          $BCGGreaterage++;  
        }
        
        
        if($result['Chanjo']=='BCG' && $age < 20){
          $BCGLessage++;
         }elseif ($result['Chanjo']=='BCG' && $age>=20) {
          $BCGGreaterage++;  
        }
        
        
         if($result['Chanjo']=='OPV 0' && $age < 20){
          $OPVLessage++;
         }elseif ($result['Chanjo']=='OPV 0' && $age>=20) {
          $OPVGreaterage++;  
        }
        
        
          if($result['KMC']=='N' && $age < 20){
          $KMCLessage++;
         }elseif ($result['KMC']=='N' && $age>=20) {
          $KMCGreaterage++;  
        }
        
        if(($result['Mahali_Alipojifungulia']=='H' && $result['Uzito_wa_mtoto']<2.5)  && $age < 20){
          $uzitoLessage++;
         }elseif (($result['Mahali_Alipojifungulia']=='H' && $result['Uzito_wa_mtoto']<2.5) && $age>=20) {
          $uzitoGreaterage++;  
        }
        
         if(($result['Mahali_Alipojifungulia']=='H' && $result['KMC']=='N')  && $age < 20){
          $HomeKMCLessage++;
         }elseif (($result['Mahali_Alipojifungulia']=='H' && $result['KMC']=='N') && $age>=20) {
          $HomeKMCGreaterage++;  
        }
        
   
         if($result['mtoto_HB']<10 && $age < 20){
          $HbLessage++;
         } elseif ($result['mtoto_HB'] < 10 && $age>=20) {
          $HbGreaterage++;  
//          echo $result['mtoto_HB'];
        }  
        
        
         if($result['Uambukizo_Mkali']=='N' && $age < 20){
          $septLessage++;
         } elseif ($result['Uambukizo_Mkali']=='N' && $age>=20) {
          $septGreaterage++;  
        }
        
         if($result['Kitovu']=='N' && $age < 20){
          $kitovuLessage++;
         } elseif ($result['Kitovu']=='N' && $age>=20) {
          $kitovuGreaterage++;  
        }
        
        
         if($result['Ngozi']=='N' && $age < 20){
          $NgoziLessage++;
         } elseif ($result['Ngozi']=='N' && $age>=20) {
          $NgoziGreaterage++;  
        }
        
        
        
         if($result['Jaundice']=='N' && $age < 20){
          $jauLessage++;
         } elseif ($result['Jaundice']=='N' && $age>=20) {
          $jauGreaterage++;  
        }
        
        
         if(($result['Mahali_Alipojifungulia']=='H' && $result['Hali_ya_mtoto']=='A')  && $age < 20){
          $vifoLessage++;
         }elseif (($result['Mahali_Alipojifungulia']=='H' && $result['Hali_ya_mtoto']=='A') && $age>=20) {
          $vifoGreaterage++;  
        }

        if($result['ARV']!='' && $age < 20){
          $ARVtotoLessage++;
         } elseif ($result['ARV']!='' && $age>=20) {
          $ARVtotoGreaterage++;  
        }
        
        
         if($result['Ulishaji_wa_mtoto']=='EBF' && $age < 20){
          $EFBtotoLessage++;
         } elseif ($result['Ulishaji_wa_mtoto']=='EBF' && $age>=20) {
          $EFBtotoGreaterage++;  
        }
        
        
         if($result['Ulishaji_wa_mtoto']=='RF' && $age < 20){
          $RFtotoLessage++;
         } elseif ($result['Ulishaji_wa_mtoto']=='RF' && $age>=20) {
          $RFtotoGreaterage++;  
        }
        
        
         if($result['Ulishaji_wa_mtoto']=='MF' && $age < 20){
          $MFtotoLessage++;
         } elseif ($result['Ulishaji_wa_mtoto']=='MF' && $age>=20) {
          $MFtotoGreaterage++;  
        }

 }
   
$totalhudhurio24hrs=$hudhurioageless+$hudhurioagegreater;
$totalhudhurio3days=$hudhurioageless3days+$hudhurioagegreater3days;
$ndanidaysless=$hudhurioageless+$hudhurioageless3days;
$ndanidaysgreater=$hudhurioagegreater+$hudhurioagegreater3days;
$Total7days=$ndanidaysless+$ndanidaysgreater;
$damuLessTotal=$damuLessage+$damuGreaterage;
$akiliLessTotal=$akiliLessage+$akiliGreaterage;
$vitaminTotal=$vitaminLessage+$vitaminGreaterage; 
$msambaTotal=$msambaLessage+$msambaGreaterage;
$fistulaTotal=$fistulaLessage+$fistulaGreaterage;
$BBATotal=$BBALessage+$BBAGreaterage; 
$TBATotal=$TBALessage+$TBAGreaterage;
$HTotal=$HLessage+$HGreaterage;
$FPTotal=$FPLessage+$FPGreaterage;
$FPIECTotal=$FPIECLessage+$FPIECGreaterage;
$FPPPCTotal=$FPPPCLessage+$FPPPCGreaterage; 
$FPrufaaTotal=$FPrufaaLessage+$FPrufaaGreaterage;
$KVVUTotal=$KVVULessage+$KVVUGreaterage;
$KPVVUTotal=$KPVVULessage+$KPVVUGreaterage;
$HPVVUTotal=$HPVVULessage+$HPVVUGreaterage;
$VVUEBFTotal=$VVUEBFLessage+$VVUEBFGreaterage;
$VVURFTotal=$VVURFLessage+$VVURFGreaterage;
$BCGTotal=$BCGLessage+$BCGGreaterage;
$OPVTotal=$OPVLessage+$OPVGreaterage;
$KCMTotal=$KMCLessage+$KMCGreaterage;
$uzitoTotal=$uzitoLessage+$uzitoGreaterage;
$HomeKMCTotal=$HomeKMCLessage+$HomeKMCGreaterage;
$HbTotal=$HbLessage+$HbGreaterage;
$septTotal=$septLessage+$septGreaterage;
$kitovuTotal=$kitovuLessage+$kitovuGreaterage;
$NgoziTotal=$NgoziLessage+$NgoziGreaterage;
$jauTotal=$jauLessage+$jauGreaterage;
$vifoTotal=$vifoLessage+$vifoGreaterage;
$ARVtotoTotal=$ARVtotoLessage+$ARVtotoGreaterage;
$EFBtotoTotal=$EFBtotoLessage+$EFBtotoGreaterage;
$RFtotoTotal=$RFtotoLessage+$RFtotoGreaterage;
$MFtotoTotal=$MFtotoLessage+$MFtotoGreaterage;
$hudhurioMtotoTotal=$hudhurioMtotoLessage+$hudhurioMtotoGreaterage;
$hudhuriosiku3MtotoTotal=$hudhuriosiku3MtotoLessage+$hudhuriosiku3MtotoGreaterage;
 
$TotalmtotoLess=$hudhurioMtotoLessage+$hudhuriosiku3MtotoLessage;
$TotalmtotoGreater=$hudhurioMtotoGreaterage+$hudhuriosiku3MtotoGreaterage;
$grandMtotohudhurio=$TotalmtotoLess+$TotalmtotoGreater;


  $disp= "<div id='all'>";
  $disp.="<div id='hudhurio1'>";
  $disp.='<table width=100%>
		<tr>
		    <td style="text-align: center;">
			<b>TAARIFA YA MWEZI MTOTO NA MAMA BAADA YA KUJIFUNGUA</b>
			<br>
			<span style="font-size: x-small;"><b>Mwezi</b></span>
		    </td>
		</tr>
	    </table><br/>';
    
    $disp.="<table style='width:100%'>";
   $disp.="<tr><th>Namba</th><th>Maelezo</th><th>Umri < 20</th><th>Umri Miaka 20 na zaidi</th><th>Total</th></tr>";
    
    $disp.="<tr><td style='text-align:center'>1a</td><td>Waliohudhuria ndani ya saa 48</td><td><input type='text' readonly value='$hudhurioageless'></td><td><input type='text' readonly value='$hudhurioagegreater'></td><td><input type='text' readonly value='$totalhudhurio24hrs'></td>
      <tr><td style='text-align:center'>1b</td><td>Waliohudhuria kati ya siku 3-7</td><td><input type='text' readonly value='$hudhurioageless3days'></td><td><input type='text' readonly value='$hudhurioagegreater3days'></td><td><input type='text' readonly value='$totalhudhurio3days'></td>
     <tr><td style='text-align:center'></td><td>Jumla Waliohudhuria ndani ya siku 7 (1a +1b)</td><td><input type='text' readonly value='$ndanidaysless'></td><td><input type='text' readonly value='$ndanidaysgreater'></td><td><input type='text' readonly value='$Total7days'></td>
         
    <tr><td style='text-align:center'>2</td><td>Waliomaliza mahudhurio yote(saa 48,siku 3-7,siku 8-28,siku 29-42)</td><td><input type='text' readonly value=''></td><td><input type='text' readonly value=''></td><td><input type='text' readonly value=''></td>
    
    <tr><td style='text-align:center'>3</td><td>Wenye upungufu mkubwa wa damu(Hb<8.5g/dl)</td><td><input type='text' readonly value='$damuLessage'></td><td><input type='text' readonly value='$damuGreaterage'></td><td><input type='text' readonly value='$damuLessTotal'></td>
    <tr><td style='text-align:center'>4</td><td>Waliopata matatizo ya akili</td><td><input type='text' readonly value='$akiliLessage'></td><td><input type='text' readonly value='$akiliGreaterage'></td><td><input type='text' readonly value='$akiliLessTotal'></td>
        
    <tr><td style='text-align:center'>5</td><td>Walopata Vit. A</td><td><input type='text' readonly value='$vitaminLessage'></td><td><input type='text' readonly value='$vitaminGreaterage'></td><td><input type='text' readonly value='$vitaminTotal'></td>
    <tr><td style='text-align:center'>6</td><td>Wenye msamba ulioambukizwa/Ulioachia</td><td><input type='text' readonly value='$msambaLessage'></td><td><input type='text' readonly value='$msambaGreaterage'></td><td><input type='text' readonly value='$msambaTotal'></td>
     <tr><td style='text-align:center'>7</td><td>Wenye fistula</td><td><input type='text' readonly value='$fistulaLessage'></td><td><input type='text' readonly value='$fistulaGreaterage'></td><td><input type='text' readonly value='$fistulaTotal'></td>
    


     </tr>";
     
     $disp.="<tr><th style='text-align:center'>8</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Waliojifungulia Nje ya kituo cha kutolea huduma za afya</th></tr>";
     
     
      $disp.="<tr><td style='text-align:center'>8a</td><td>Waliojifungua kabla ya kufika kituo cha kutolea huduma za afya (BBA)</td><td><input type='text' readonly value='$BBALessage'></td><td><input type='text' readonly value='$BBAGreaterage'></td>
     
     <td><input type='text' readonly value='$BBATotal'></td></tr>
     
    <tr><td style='text-align:center'>8b</td><td>Waliojifungulia kwa wakunga wa jadi (TBA)</td><td><input type='text' readonly value='$TBALessage'></td><td><input type='text' readonly value='$TBAGreaterage'></td>
     
     <td><input type='text' readonly value='$TBATotal'></td></tr>
     
     <tr><td style='text-align:center'>8c</td><td>Waliojifungulia nyumbani</td><td><input type='text' readonly value='$HLessage'></td><td><input type='text' readonly value='$HGreaterage'></td>
     
     <td><input type='text' readonly value='$HTotal'></td></tr>
    
";
      
      $disp.="<tr><th style='text-align:center'>9</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Uzazi wa Mpango</th></tr>";

     $disp.="<tr><td style='text-align:center'>9a</td><td>Idadi ya wateja waliopata ushauri nasaha mara moja</td><td><input type='text' readonly value='$FPLessage'></td><td><input type='text' readonly value='$FPGreaterage'></td>
     
     <td><input type='text' readonly value='$FPTotal'></td></tr>
  
     
     <tr><td style='text-align:center'>9b</td><td>Amepata njia ya Uzazi wa mpango wakati wa hudhurio ya Postnatal</td><td><input type='text' readonly value='$FPIECLessage'></td><td><input type='text' readonly value='$FPIECGreaterage'></td>
     
     <td><input type='text' readonly value='$FPIECTotal'></td></tr>

 
     <tr><td style='text-align:center'>9c</td><td>Waliopata njia ya Uzazi wa mpango baada ya mimba kuharibika</td><td><input type='text' readonly value='$FPPPCLessage'></td><td><input type='text' readonly value='$FPPPCGreaterage'></td>
     
     <td><input type='text' readonly value='$FPPPCTotal'></td></tr>
   
     <tr><td style='text-align:center'>9d</td><td>Waliopata rufaa kupata njia ya uzazi wa mpango</td><td><input type='text' readonly value='$FPrufaaLessage'></td><td><input type='text' readonly value='$FPrufaaGreaterage'></td>
     
     <td><input type='text' readonly value='$FPrufaaTotal'></td></tr>
    
      ";

    $disp.="<tr><th style='text-align:center'>10</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>PMTCT</th></tr>";
    $disp.="<tr><td style='text-align:center'>10a</td><td>Waliokuja postnatal wakiwa positive</td><td><input type='text' readonly value='$HPVVULessage'></td><td><input type='text' readonly value='$HPVVUGreaterage'></td>
     
     <td><input type='text' readonly value='$HPVVUTotal'></td></tr>

     <tr><td style='text-align:center'>10b</td><td>Waliopima VVU wakati wa postnatal(ndani ya siku 42 tangu ya kujifungua)</td><td><input type='text' readonly value='$KVVULessage'></td><td><input type='text' readonly value='$KVVUGreaterage'></td>
     
     <td><input type='text' readonly value='$KVVUTotal'></td></tr>

    <tr><td style='text-align:center'>10c</td><td>Waliogundulika wana VVU wakati wa postnatal(ndani ya siku 42 tangu kujifungua)</td><td><input type='text' readonly value='$KPVVULessage'></td><td><input type='text' readonly value='$KPVVUGreaterage'></td>
     
     <td><input type='text' readonly value='$KPVVUTotal'></td></tr>
     <tr><td style='text-align:center'>10d</td><td>Wenye VVU waliochagua kunyonyesha maziwa ya mama pekee(EBF)</td><td><input type='text' readonly value='$VVUEBFLessage'></td><td><input type='text' readonly value='$VVUEBFGreaterage'></td>
     
     <td><input type='text' readonly value='$VVUEBFTotal'></td></tr>
    
     <tr><td style='text-align:center'>10e</td><td>Wenye VVU waliochagua kunywesha maziwa mbadala (RF)</td><td><input type='text' readonly value='$VVURFLessage'></td><td><input type='text' readonly value='$VVURFGreaterage'></td>
     
     <td><input type='text' readonly value='$VVURFTotal'></td></tr>
";
    
      $disp.="<tr><th style='text-align:center'>11</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>MTOTO</th></tr>";
      $disp.="<tr><td style='text-align:center'>11a</td><td>Idadi ya watoto waliohudhuria Ndani ya saa 48</td><td><input type='text' readonly value='$hudhurioMtotoLessage'></td><td><input type='text' readonly value='$hudhurioMtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$hudhurioMtotoTotal'></td></tr>
     
    
    <tr><td style='text-align:center'>11b</td><td>Idadi ya watoto waliohudhuria kati ya siku 3-7</td><td><input type='text' readonly value='$hudhuriosiku3MtotoLessage'></td><td><input type='text' readonly value='$hudhuriosiku3MtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$hudhuriosiku3MtotoTotal'></td></tr>
    
   
    ";
      
    $disp.="<tr><td style='text-align:center'></td><td style='font-weight:bold;font-style:italic;'>Jumla ya Waliohudhuria ndani ya siku 7(11a+11b)</td><td><input type='text' style='font-weight:bold' readonly value='$TotalmtotoLess'></td><td><input type='text' style='font-weight:bold' readonly value='$TotalmtotoGreater'></td>
     
     <td><input type='text' style='font-weight:bold' readonly value='$grandMtotohudhurio'></td></tr>
     
     <tr><td style='text-align:center'>11c</td><td>Waliomaliza mahudhurio yote (saa 48, siku 3-7,siku 8-28,siku 29-42)</td><td><input type='text' readonly value=''></td><td><input type='text' readonly value=''></td>
     
     <td><input type='text' readonly value=''></td></tr>
        
    ";
     
     $disp.="<tr><th style='text-align:center'>12</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>HUDUMA KWA MTOTO</th></tr>";
     $disp.="<tr><td style='text-align:center'>12a</td><td>Idadi ya watoto waliopewa BCG</td><td><input type='text' readonly value='$BCGLessage'></td><td><input type='text' readonly value='$BCGGreaterage'></td>
     
     <td><input type='text' readonly value='$BCGTotal'></td></tr>
     
     <tr><td style='text-align:center'>12b</td><td>Idadi ya watoto waliopewa OPV O</td><td><input type='text' readonly value='$OPVLessage'></td><td><input type='text' readonly value='$OPVGreaterage'></td>
     
     <td><input type='text' readonly value='$OPVTotal'></td></tr>
    
     <tr><td style='text-align:center'>12c</td><td>Idadi ya watoto waliozaliwa na uzito wa < 2.5kg wakati wa KMC</td><td><input type='text' readonly value='$KMCLessage'></td><td><input type='text' readonly value='$KMCGreaterage'></td>
     
     <td><input type='text' readonly value='$KCMTotal'></td></tr>
     
     <tr><td style='text-align:center'>12d</td><td>Idadi ya watoto waliozaliwa nyumbani chini ya 2.5kg</td><td><input type='text' readonly value='$uzitoLessage'></td><td><input type='text' readonly value='$uzitoGreaterage'></td>
     
     <td><input type='text' readonly value='$uzitoTotal'></td></tr>

     <tr><td style='text-align:center'>12e</td><td>Idadi ya watoto waliozaliwa nyumbani walioanzishiwa huduma ya kangaroo (KMC)</td><td><input type='text' readonly value='$HomeKMCLessage'></td><td><input type='text' readonly value='$HomeKMCGreaterage'></td>
     
     <td><input type='text' readonly value='$HomeKMCTotal'></td></tr>
   
     <tr><td style='text-align:center'>12f</td><td>Idadi ya watoto wenye upungufu mkubwa wa damu (Hb < 10g/dl au viganja vyeupe sana)</td><td><input type='text' readonly value='$HbLessage'></td><td><input type='text' readonly value='$HbGreaterage'></td>
     
     <td><input type='text' readonly value='$HbTotal'></td></tr>
    ";
     
    $disp.="<tr><th style='text-align:center'>13</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>UAMBUKIZO WA MTOTO</th></tr>";
    
    $disp.="<tr><td style='text-align:center'>13a</td><td>Idadi ya watoto wenye uambukizo mkali(septicaemia)</td><td><input type='text' readonly value='$septLessage'></td><td><input type='text' readonly value='$septGreaterage'></td>
     
     <td><input type='text' readonly value='$septTotal'></td></tr>
   
     <tr><td style='text-align:center'>13b</td><td>Idadi ya watoto wenye uambukizo kwenye kitovu</td><td><input type='text' readonly value='$kitovuLessage'></td><td><input type='text' readonly value='$kitovuGreaterage'></td>
     
     <td><input type='text' readonly value='$kitovuTotal'></td></tr>
   
     <tr><td style='text-align:center'>13c</td><td>Idadi ya watoto wenye uambukizo kwenye ngozi</td><td><input type='text' readonly value='$NgoziLessage'></td><td><input type='text' readonly value='$NgoziGreaterage'></td>
     
     <td><input type='text' readonly value='$NgoziTotal'></td></tr>
    
     <tr><td style='text-align:center'>13d</td><td>Idadi ya watoto wenye jaundice</td><td><input type='text' readonly value='$jauLessage'></td><td><input type='text' readonly value='$jauGreaterage'></td>
     
     <td><input type='text' readonly value='$jauTotal'></td></tr>

    <tr><td style='text-align:center'>14</td><td>Vifo vya watoto wachanga waliozaliwa nyumbani (perinatal;neonatal)</td><td><input type='text' readonly value='$vifoLessage'></td><td><input type='text' readonly value='$vifoGreaterage'></td>
     
     <td><input type='text' readonly value='$vifoTotal'></td></tr>


     <tr><td style='text-align:center'>15</td><td>Waliopewa dawa ya ARV</td><td><input type='text' readonly value='$ARVtotoLessage'></td><td><input type='text' readonly value='$ARVtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$ARVtotoTotal'></td></tr>

    ";
     
    $disp.="<tr><th style='text-align:center'>16</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>ULISHAJI WA MTOTO</th></tr>";
    $disp.="<tr><td style='text-align:center'>16a</td><td>Watoto wachanga wanaonyonya maziwa ya mama pekee</td><td><input type='text' readonly value='$EFBtotoLessage'></td><td><input type='text' readonly value='$EFBtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$EFBtotoTotal'></td></tr>
    
    <tr><td style='text-align:center'>16b</td><td>Watoto wachanga wanaonyweshwa maziwa maziwa mbadala (RF)</td><td><input type='text' readonly value='$RFtotoLessage'></td><td><input type='text' readonly value='$RFtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$RFtotoTotal'></td></tr>
     <tr><td style='text-align:center'>16c</td><td>Watoto wachanga wanaonyonyeshwa maziwa ya mama na kupatiwa chakula kingine (MF)</td><td><input type='text' readonly value='$MFtotoLessage'></td><td><input type='text' readonly value='$MFtotoGreaterage'></td>
     
     <td><input type='text' readonly value='$MFtotoTotal'></td></tr>
     
    ";
    $disp.="</table>";
    $disp.="</div>";
    
        
        
   include("MPDF/mpdf.php");

    //$mpdf=new mPDF('','Letter-L',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf=new mPDF('c','A3-L'); 

    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>