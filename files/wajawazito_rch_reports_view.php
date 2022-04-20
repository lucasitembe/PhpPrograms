

<?php
   
include("./includes/connection.php");

if(isSet($_POST['d']))
{    
  $date1 = $_POST['d'];
  $date2 = $_POST['d2'];
  $Zaid4Less=0;
  $Zaid4Greater=0;
  $Zaid20Less=0;
  $Zaidi35=0;
  $BpLess=0;
  $BPGreater=0;
  $DamuLess=0;
  $DamuGreater=0;
  $mkojosukariLess=0;
  $mkojosukariGreater=0;
  $PimaKsLess=0;
  $PimaKsGreater=0;
  $AnaKsLess=0;
  $AnaKsGreater=0;
  $TibiwaKsLess=0;
  $TibiwaKsGreater=0;
  $WenzaPimaKsLess=0;
  $WenzaPimaKsGreater=0;
  $WenzaAnaKsLess=0;
  $WenzaAnaKsGreater=0;
  $WenzaTibiwaKsLess=0;
  $WenzaTibiwaKsGreater=0;
  $ngLess=0;
  $ngGreater=0;
  $TibangLess=0;
  $TibangGreater=0;
  $WenzangLess=0;
  $WenzangGreater=0;
  $WenzaTibangLess=0;
  $WenzaTibangGreater=0;
  $TayariVVULess=0;
  $TayariVVUGreater=0;
  $UshauriLess=0;
  $UshauriGreater=0;
  $pimaVVU1Less=0;
  $pimaVVU1Greater=0;
  $PositiveVVU1Less=0;
  $PositiveVVU1Greater=0;
  $PositiveVVU1under25Less=0;
  $PositiveVVU1under25Greater=0;
  $UnasihiLess=0;
  $UnasihiGreater=0;
  $mekepimaLess=0;
  $mekepimaGreater=0;
  $pimaVVU2Less=0;
  $pimaVVU2Greater=0;
  $PositiveVVU2Less=0;
  $PositiveVVU2Greater=0;
  $WenzapimaVVU1Less=0;
  $WenzapimaVVU1Greater=0;
  $WenzaPositiveVVU1Less=0;
  $WenzaPositiveVVU1Greater=0;
  $TofautimatokeoLess=0;
  $TofautimatokeoGreater=0;
  $mtotofeedngLess=0;
  $mtotofeedngGreater=0;
  $hatipunguzoLess=0;
  $hatipunguzoGreater=0;
  $mrdtLess=0;
  $mrdtGreater=0;
  $PositivemrdtLess=0;
  $PositivemrdtGreater=0;
  $IPT2Less=0;
  $IPT2Greater=0;
  $IPT4Less=0;
  $IPT4Greater=0;
  $MabeLess=0;
  $MabeGreater=0;
  $RufaaLess=0;
  $RufaaGreater=0;
  $mimbau12Less=0;
  $mimbau12Greater=0;
  $mimbaabove12Less=0;
  $mimbaabove12Greater=0;
  $pimadamuLess=0;
  $pimadamuGreater=0;
  $TT2Less=0;
  $TT2Greater=0;
  
  $firstphase=mysqli_query($conn,"SELECT * FROM tbl_wajawazito tw JOIN tbl_patient_registration tpr ON tw.Patient_ID=tpr.Registration_ID WHERE hudhurio_tarehe BETWEEN '$date1' AND '$date2'");
  $Today = Date("Y-m-d");
  while($result=  mysqli_fetch_assoc($firstphase)){
    $Date_Of_Birth = $result['Date_Of_Birth'];
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y;

    
     if ($result['mimba_no']>4 && $age < 20){
       $Zaid4Less++;   
     
      } else if($result['mimba_no']>4 && $age>=20){
        $Zaid4Greater++;  
      };
      
      if ($age < 20){
       $Zaid20Less++;   
     
      }
      
      if ($age > 35){
       $Zaidi35++;   
      }
      
      if ($result['mimba_umri']<12 && $age < 20){
       $mimbau12Less++;   
       
      } else if($result['mimba_umri']<12 && $age>=20){
        $mimbau12Greater++; 
        
      };
      
      if ($result['mimba_umri']>12 && $age < 20){
       $mimbaabove12Less++;   
       
      } else if($result['mimba_umri']>12 && $age>=20){
        $mimbaabove12Greater++; 
       
      };
      
      if ($result['damu_kiwango']>0 && $age < 20){
       $pimadamuLess++;   
       
      } else if($result['damu_kiwango']>0 && $age>=20){
        $pimadamuGreater++; 
       
      };
      
      
      if ($result['tt2tarehe']!='0000-00-00' && $age < 20){
       $TT2Less++;   
       
      } else if($result['tt2tarehe']!='0000-00-00' && $age>=20){
        $TT2Greater++; 
       
      };
      
      if ($result['damu_kiwango']<8.5 && $age < 20){
       $DamuLess++;   
     
      } else if($result['damu_kiwango']<8.5 && $age>=20){
        $DamuGreater++;   
      };
      
      if ($result['Bp']>=140/90 && $age < 20){
       $BpLess++;   
     
      } else if($result['Bp']>=140/90 && $age>=20){
        $BPGreater++; 
      };
      
      if ($result['mkojo_sukari']=='N' && $age < 20){
       $BpLess++;   
     
      } else if($result['mkojo_sukari']=='N' && $age>=20){
        $BPGreater++; 
      };
      
      if (($result['kaswende_matokeo_ke']=='N' || $result['kaswende_matokeo_ke']=='P') && $age < 20){
       $PimaKsLess++;   
     
      } else if(($result['kaswende_matokeo_ke']=='N' || $result['kaswende_matokeo_ke']=='P') && $age>=20){
        $PimaKsGreater++; 
      };
      
      if ($result['kaswende_matokeo_ke']=='P' && $age < 20){
       $AnaKsLess++;   
     
      } else if($result['kaswende_matokeo_ke']=='P' && $age>=20){
        $AnaKsGreater++; 
      };

       if ($result['kaswende_ametibiwa_ke']=='N' && $age < 20){
       $TibiwaKsLess++;   
     
      } else if($result['kaswende_ametibiwa_ke']=='N' && $age>=20){
        $TibiwaKsGreater++; 
      };
      
       if (($result['kaswende_matokeo_me']=='N' || $result['kaswende_matokeo_me']=='P') && $age < 20){
       $WenzaPimaKsLess++;   
     
      } else if(($result['kaswende_matokeo_me']=='N' || $result['kaswende_matokeo_me']=='P') && $age>=20){
        $WenzaPimaKsGreater++; 
      };
      
      if ($result['kaswende_matokeo_me']=='P' && $age < 20){
       $WenzaAnaKsLess++;   
     
      } else if($result['kaswende_matokeo_me']=='P' && $age>=20){
        $WenzaAnaKsGreater++; 
      };
      
      if ($result['kaswende_ametibiwa_me']=='N' && $age < 20){
       $WenzaAnaKsLess++;   
     
      } else if($result['kaswende_ametibiwa_me']=='N' && $age>=20){
        $WenzaAnaKsGreater++; 
      };
      
      if ($result['ng_matokeo_ke']=='P' && $age < 20){
       $ngLess++;   
     
      } else if($result['ng_matokeo_ke']=='P' && $age>=20){
        $ngGreater++; 
      };
      
      if ($result['ng_ametibiwa_ke']=='N' && $age < 20){
       $TibangLess++;   
     
      } else if($result['ng_ametibiwa_ke']=='N' && $age>=20){
        $TibangGreater++; 
      };
      
      if ($result['ng_matokeo_me']=='P' && $age < 20){
       $WenzangLess++;   
     
      } else if($result['ng_matokeo_me']=='P' && $age>=20){
        $WenzangGreater++; 
      };
      
      if ($result['ng_ametibiwa_me']=='N' && $age < 20){
       $WenzaTibangLess++;   
     
      } else if($result['ng_ametibiwa_me']=='N' && $age>=20){
        $WenzangGreater++; 
      };
      
      if ($result['ana_VVU_ke']=='N' && $age < 20){
       $TayariVVULess++;   
     
      } else if($result['ana_VVU_ke']=='N' && $age>=20){
        $TayariVVUGreater++; 
      };
      
      if ($result['unasihi_ke']!='' && $age < 20){
       $UshauriLess++;   
     
      } else if($result['unasihi_ke']!='' && $age>=20){
        $UshauriGreater++; 
      };
      
      if ($result['amepima_VVU_ke']=='N' && $age < 20){
       $pimaVVU1Less++;   
     
      } else if($result['amepima_VVU_ke']=='N' && $age>=20){
        $pimaVVU1Greater++; 
      };
      
      if ($result['kipimo_1_VVU_matokeo_ke']=='P' && $age < 20){
       $PositiveVVU1Less++;   
     
      } else if($result['kipimo_1_VVU_matokeo_ke']=='P' && $age>=20){
        $PositiveVVU1Greater++; 
      };
      
      if ($result['kipimo_1_VVU_matokeo_ke']=='P' && $age < 20){
       $PositiveVVU1under25Less++;   
     
      } else if($result['kipimo_1_VVU_matokeo_ke']=='P' && ($age>=20 && $age<25)){
        $PositiveVVU1under25Greater++; 
      };
      
      if ($result['unasihi_kupima_ke']=='N' && $age < 20){
       $UnasihiLess++;   
     
      } else if($result['unasihi_kupima_ke']=='N' && $age>=20){
        $UnasihiGreater++; 
      };
    
       if (($result['amepima_VVU_ke']=='N' && $result['amepima_VVU_me']=='N') && $age < 20){
       $mekepimaLess++;   
     
      } else if(($result['amepima_VVU_ke']=='N' && $result['amepima_VVU_me']=='N') && $age>=20){
        $mekepimaGreater++; 
      };
      
      if ($result['matokeo_VVU_2']!='' && $age < 20){
       $pimaVVU2Less++;   
     
      } else if($result['matokeo_VVU_2']!='' && $age>=20){
        $pimaVVU2Greater++; 
      };
      
       if ($result['matokeo_VVU_2']=='P' && $age < 20){
       $PositiveVVU2Less++;   
     
      } else if($result['matokeo_VVU_2']=='P' && $age>=20){
        $PositiveVVU2Greater++; 
      };
      
      
       if ($result['amepima_VVU_me']=='N' && $age < 20){
       $WenzapimaVVU1Less++;   
     
      } else if($result['amepima_VVU_me']=='N' && $age>=20){
        $WenzapimaVVU1Greater++; 
      };
      
      if ($result['kipimo_1_VVU_matokeo_me']=='P' && $age < 20){
       $WenzaPositiveVVU1Less++;   
     
      } else if($result['kipimo_1_VVU_matokeo_me']=='P' && $age>=20){
        $WenzaPositiveVVU1Greater++; 
      };
      
       if (($result['kipimo_1_VVU_matokeo_ke']!=$result['kipimo_1_VVU_matokeo_me']) && $age < 20){
       $TofautimatokeoLess++;   
     
      } else if(($result['kipimo_1_VVU_matokeo_ke']!=$result['kipimo_1_VVU_matokeo_me']) && $age>=20){
        $TofautimatokeoGreater++; 
      };
      
      
       if ($result['amepata_ushauri']=='N' && $age < 20){
       $mtotofeedngLess++;   
     
      } else if($result['amepata_ushauri']=='N' && $age>=20){
        $mtotofeedngGreater++; 
      };
      
      
      if ($result['hatipunguzo']=='N' && $age < 20){
       $hatipunguzoLess++;   
     
      } else if($result['hatipunguzo']=='N' && $age>=20){
        $hatipunguzoGreater++; 
      };
      
       if (($result['mrdt']=='N' || $result['mrdt']=='P') && $age < 20){
       $mrdtLess++;   
     
      } else if(($result['mrdt']=='N' || $result['mrdt']=='P') && $age>=20){
        $mrdtGreater++; 
      };
      
       if ($result['mrdt']=='P' && $age < 20){
       $PositivemrdtLess++;   
     
      } else if($result['mrdt']=='P' && $age>=20){
        $PositivemrdtGreater++; 
      };
 
      
       if ($result['IPT2']!='' && $age < 20){
       $IPT2Less++;   
     
      } else if($result['IPT2']!='' && $age>=20){
        $IPT2Greater++; 
      };
      
      
       if ($result['IPT4']!='' && $age < 20){
       $IPT4Less++;   
     
      } else if($result['IPT4']!='' && $age>=20){
        $IPT4Greater++; 
      };
      
      if ($result['IPT4']!='' && $age < 20){
       $MabeLess++;   
     
      } else if($result['IPT4']!='' && $age>=20){
        $MabeGreater++; 
      };
      
      
      if (($result['rufaa_tarehe']!='0000-00-00' && $result['alikopelekwa']!='') && $age < 20){
       $RufaaLess++;   
     
      } else if(($result['rufaa_tarehe']!='0000-00-00' && $result['alikopelekwa']!='') && $age>=20){
        $RufaaGreater++; 
      };
      
 
      

     }
 
  $TotalZaidi4=$Zaid4Less+$Zaid4Greater;
  $Total20Less=$Zaid20Less;
  $TotalZaidi35=$Zaidi35;
  $TotalBP=$BpLess+$BPGreater;
  $Totalmimbau12=$mimbau12Less+$mimbau12Greater;
  $Totalmimbaabove12=$mimbaabove12Less+$mimbaabove12Greater;
  $TotalmimbaLess=$mimbau12Less+$mimbaabove12Less;
  $TotalmimbaGreater=$mimbau12Greater+$mimbaabove12Greater;
  $GrandTotalmimba=$TotalmimbaLess+$TotalmimbaGreater;
  $Totalpimadamu=$pimadamuLess+$pimadamuGreater;
  $TotalTT2=$TT2Less+$TT2Greater;
  $TotalDamu=$DamuLess+$DamuGreater;
  $Totalmkojosukari=$mkojosukariLess+$mkojosukariGreater;
  $TotalPimaKs=$PimaKsLess+$PimaKsGreater;
  $TotalAnaKs=$AnaKsLess+$AnaKsGreater;
  $TibiwaTotal=$TibiwaKsLess+$TibiwaKsGreater;
  $TotalWenzapima=$WenzaPimaKsLess+$WenzaPimaKsGreater;
  $TotalMwenzaAnaKs=$WenzaAnaKsLess+$WenzaAnaKsGreater; 
  $TotalWenzaksTiba=$WenzaTibiwaKsLess+$WenzaTibiwaKsGreater;
  $Totalng=$ngLess+$ngGreater;
  $TotalTibang=$TibangLess+$TibangGreater;
  $WenzaTotalng=$WenzangLess+$WenzangGreater;
  $TotalwenzaTibang=$WenzaTibangLess+$WenzaTibangGreater;
  $TotalTayarianaVVU=$TayariVVULess+$TayariVVUGreater;
  $UshauriTotal=$UshauriLess+$UshauriGreater;
  $TotalpimaVVU1=$pimaVVU1Less+$pimaVVU1Greater; 
  $TotalPositiveVVU1=$PositiveVVU1Less+$PositiveVVU1Greater;
  $TotalPositiveVVU1under25=$PositiveVVU1under25Less+$PositiveVVU1under25Greater;
  $TotalUnasihi=$UnasihiLess+$UnasihiGreater;
  $Totalmekepima=$mekepimaLess+$mekepimaGreater;
  $TotalpimaVVU2=$pimaVVU2Less+$pimaVVU2Greater;
  $TotalPositiveVVU2=$PositiveVVU2Less+$PositiveVVU2Greater;
  $TotalWenzapimaVVU1=$WenzapimaVVU1Less+$WenzapimaVVU1Greater;
  $TotalWenzaPositiveVVU1=$WenzaPositiveVVU1Less+$WenzaPositiveVVU1Greater;
  $TotalTofautimatokeo=$TofautimatokeoLess+$TofautimatokeoGreater;
  $Totalmtotofeedng=$mtotofeedngLess+$mtotofeedngGreater;
  $Totalhatipunguzo=$hatipunguzoLess+$hatipunguzoGreater;
  $Totalmrdt=$mrdtLess+$mrdtGreater;
  $TotalPositivemrdt=$PositivemrdtLess+$PositivemrdtGreater;
  $TotalIPT2=$IPT2Less+$IPT2Greater;
  $TotalIPT4=$IPT4Less+$IPT4Greater;
  $TotalMabe=$MabeLess+$MabeGreater;
  $TotalRufaa=$RufaaLess+$RufaaGreater;
  echo "<div id='all'>";
  echo "<div id='hudhurio1'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < 20</th><th>Umri Miaka 20 na zaidi</th><th>Total</th></tr>";
    
     echo "<tr><td style='text-align:center'>1</td><td>Idadi ya Wajawazito Waliotegemewa</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td></tr>";
     echo "<tr><th style='text-align:center'>2</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Hudhurio la kwanza</th></tr>";
     
      
    echo "<tr><td style='text-align:center'>2a</td><td>Umri wa mimba chini ya wiki 12 (<12 weeks)</td><td style='text-align:center'>$mimbau12Less</td><td style='text-align:center'>$mimbau12Greater</td><td style='text-align:center'>$Totalmimbau12</td>
     
  
        <tr><td style='text-align:center'>2b</td><td>Umri wa mimba wiki 12 au zaidi (12+ weeks)</td><td style='text-align:center'>$mimbaabove12Less</td><td style='text-align:center'>$mimbaabove12Greater</td><td style='text-align:center'>$Totalmimbaabove12</td>
      
      
      
    <tr><td style='text-align:center'></td><td><b><i>Jumla ya hudhurio la kwanza(2a+2b)</i></b></td><td style='text-align:center'><b><i>$TotalmimbaLess</i></b></td><td style='text-align:center'><b><i>$TotalmimbaGreater</i></b></td><td style='text-align:center'><b><i>$GrandTotalmimba</i></b></td>
    
    <tr><td style='text-align:center'>2c</td><td>Wateja wa marudio</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
    <tr><td style='text-align:center'>2d</td><td>Hudhurio la nne wajawazito wote</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
        
    <tr><td style='text-align:center'></td><td><b><i>Jumla ya mahudhurio yote (2c+2d)</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</b></i></td><td style='text-align:center'><b><i>0</i></b></td>
   
    <tr><td style='text-align:center'>2e</td><td>Idadi ya wajawazito waliopima damu hudhurio la kwanza</td><td style='text-align:center'>$pimadamuLess</td><td style='text-align:center'>$pimadamuGreater</td><td style='text-align:center'>$Totalpimadamu</td>
    
    <tr><td style='text-align:center'>3</td><td>Wajawazito waliopata chanjo ya TT2+</td><td style='text-align:center'>$TT2Less</td><td style='text-align:center'>$TT2Greater</td><td style='text-align:center'>$TotalTT2</td>
 
    </tr>";
     
     echo "<tr><th style='text-align:center'>4</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Vidokezo vya Hatari</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>4a</td><td>Mimba zaidi ya 4</td><td style='text-align:center'>$Zaid4Less</td><td style='text-align:center'>$Zaid4Greater</td>
       
   
     <td style='text-align:center'>$TotalZaidi4</td></tr>
      
     <tr><td style='text-align:center'>4b</td><td>Umri chini ya miaka 20</td><td style='text-align:center'>$Zaid20Less</td><td style='text-align:center;background-color:rgb(200,200,200)'></td>
     
     <td style='text-align:center'>$Total20Less</td></tr>
     
   
      
     <tr><td style='text-align:center'>4c</td><td>Umri zaidi ya miaka 35</td><td style='text-align:center;background-color:rgb(200,200,200)'></td><td style='text-align:center'>$Zaidi35</td>
     
     <td style='text-align:center'>$TotalZaidi35</td></tr>
     
     <tr><td style='text-align:center'>4d</td><td>Upungufu mkubwa wa damu <8.5g/dl Anaemiahudhurio la kwanza</td><td style='text-align:center'>$DamuLess</td><td style='text-align:center'>$DamuGreater</td>
     
     <td style='text-align:center'>$TotalDamu</td></tr>
   
   
   
     <tr><td style='text-align:center'>4e</td><td>Shinikizo la damu(BP=>140/90mm/hg)</td><td style='text-align:center'>$BpLess</td><td style='text-align:center'>$BPGreater</td>
     
     <td style='text-align:center'>$TotalBP</td></tr>
     
     <tr><td style='text-align:center'>4f</td><td>Kifua kikuu</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     
     <tr><td style='text-align:center'>4g</td><td>Sukari kwenye mkojo</td><td style='text-align:center'>$mkojosukariLess</td><td style='text-align:center'>$mkojosukariGreater</td>
     
     <td style='text-align:center'>$Totalmkojosukari</td></tr>
      
  
     <tr><td style='text-align:center'>4h</td><td>Protein kwenye mkojo</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     <tr><td style='text-align:center'>4i</td><td>Waliopima Kaswende</td><td style='text-align:center'>$PimaKsLess</td><td style='text-align:center'>$PimaKsGreater</td>
     
     <td style='text-align:center'>$TotalPimaKs</td></tr>
     
 
     <tr><td style='text-align:center'>4j</td><td>Waliogundulika na maambukizi ya kaswende</td><td style='text-align:center'>$AnaKsLess</td><td style='text-align:center'>$AnaKsGreater</td>
     
     <td style='text-align:center'>$TotalAnaKs</td></tr>

  
     <tr><td style='text-align:center'>4k</td><td>Waliopata matibabu ya kaswende</td><td style='text-align:center'>$TibiwaKsLess</td><td style='text-align:center'>$TibiwaKsGreater</td>
     
     <td style='text-align:center'>$TibiwaTotal</td></tr>
 
     <tr><td style='text-align:center'>4l</td><td>Wenza/waume waliopima Kaswende</td><td style='text-align:center'>$WenzaPimaKsLess</td><td style='text-align:center'>$WenzaPimaKsGreater</td>
     
     <td style='text-align:center'>$TotalWenzapima</td></tr>
     
     <tr><td style='text-align:center'>4m</td><td>Wenza/waume waliogundulika na maambukizi ya kaswende</td><td style='text-align:center'>$WenzaAnaKsLess</td><td style='text-align:center'>$WenzaAnaKsGreater</td>
     
     <td style='text-align:center'>$TotalMwenzaAnaKs</td></tr>
          
     <tr><td style='text-align:center'>4n</td><td>Wenza/waume waliopata matibabu ya kaswende</td><td style='text-align:center'>$WenzaTibiwaKsLess</td><td style='text-align:center'>$WenzaTibiwaKsGreater</td>
     
     <td style='text-align:center'>$TotalWenzaksTiba</td></tr>
     
   
 
     <tr><td style='text-align:center'>4o</td><td>Waliopatikana na magonjwa ya maambukizo ya ngono yasio kaswende</td><td style='text-align:center'>$ngLess</td><td style='text-align:center'>$ngGreater</td>
     
     <td style='text-align:center'>$Totalng</td></tr>
         
     <tr><td style='text-align:center'>4p</td><td>Waliopata tiba sahihi ya magonjwa ya maambukizo ya ngono yasio kaswende</td><td style='text-align:center'>$TibangLess</td><td style='text-align:center'>$TibangGreater</td>
     
     <td style='text-align:center'>$TotalTibang</td></tr>

    <tr><td style='text-align:center'>4q</td><td>Wenza/Waume waliopatikana na magonjwa ya maambukizi ya ngono yasio kaswende </td><td style='text-align:center'>$WenzangLess</td><td style='text-align:center'>$WenzangGreater</td>
     
     <td style='text-align:center'>$WenzaTotalng</td></tr>
      
  
     <tr><td style='text-align:center'>4r</td><td>Wenza/waume waliopata tiba sahihi ya magonjwa ya ngono yasio kaswende</td><td style='text-align:center'>$WenzaTibangLess</td><td style='text-align:center'>$WenzaTibangGreater</td>
     
     <td style='text-align:center'>$TotalwenzaTibang</td></tr>
 
  ";

    echo "<tr><th style='text-align:center'>5</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>PMTCT</th></tr>";
    echo "<tr><td style='text-align:center'>5a</td><td>Tayari wana maambukizi ya VVU kabla ya kuanza kliniki</td><td style='text-align:center'>$TayariVVULess</td><td style='text-align:center'>$TayariVVUGreater</td>
     
     <td style='text-align:center'>$TotalTayarianaVVU</td></tr>
       
     
     <tr><td style='text-align:center'>5b</td><td>Wajawazito wote waliopata ushauri nasaha kabla ya kupima VVU kliniki</td><td style='text-align:center'>$UshauriLess</td><td style='text-align:center'>$UshauriGreater</td>
     
     <td style='text-align:center'>$UshauriTotal</td></tr>

      
     <tr><td style='text-align:center'>5c</td><td>Wajawazito waliopima VVU kipimo cha kwanza kliniki</td><td style='text-align:center'>$pimaVVU1Less</td><td style='text-align:center'>$pimaVVU1Greater</td>
     
     <td style='text-align:center'>$TotalpimaVVU1</td></tr>
     
     <tr><td style='text-align:center'>5d</td><td>Wajawazito waliokutwa na VVU(Positive) kipimo cha kwanza</td><td style='text-align:center'>$PositiveVVU1Less</td><td style='text-align:center'>$PositiveVVU1Greater</td>
     
     <td style='text-align:center'>$TotalPositiveVVU1</td></tr>
      
     <tr><td style='text-align:center'>5e</td><td>Wajawazito waliokutwa na VVU(Positive) kipimo cha kwanza walio chini ya umri wa miaka 25</td><td style='text-align:center'>$PositiveVVU1under25Less</td><td style='text-align:center'>$PositiveVVU1under25Greater</td>
     
     <td style='text-align:center'>$TotalPositiveVVU1under25</td></tr>
          
     <tr><td style='text-align:center'>5f</td><td>Wajawazito waliopata ushauri baada ya kupima</td><td style='text-align:center'>$UnasihiLess</td><td style='text-align:center'>$UnasihiGreater</td>
     
     <td style='text-align:center'>$TotalUnasihi</td></tr>
    
   
     <tr><td style='text-align:center'>5g</td><td>Wajawazito waliopima VVU na wenza wao(Couple) kwa pamoja katika kliniki ya wajawazito</td><td style='text-align:center'>$mekepimaLess</td><td style='text-align:center'>$mekepimaGreater</td>
     
     <td style='text-align:center'>$Totalmekepima</td></tr>
         
     <tr><td style='text-align:center'>5h</td><td>Wajawazito waliopima VVU kipimo cha pili</td><td style='text-align:center'>$pimaVVU2Less</td><td style='text-align:center'>$pimaVVU2Greater</td>
     
     <td style='text-align:center'>$TotalpimaVVU2</td></tr>
         
     <tr><td style='text-align:center'>5i</td><td>Wajawazito waliokutwa na maambukizi ya VVU kipimo cha pili</td><td style='text-align:center'>$PositiveVVU2Less</td><td style='text-align:center'>$PositiveVVU2Greater</td>
     
     <td style='text-align:center'>$TotalPositiveVVU2</td></tr>
         
     <tr><td style='text-align:center'>5j</td><td>Wenza waliopima VVU kipimo cha kwanza kliniki ya wajawazito</td><td style='text-align:center'>$WenzapimaVVU1Less</td><td style='text-align:center'>$WenzapimaVVU1Greater</td>
     
     <td style='text-align:center'>$TotalWenzapimaVVU1</td></tr>
         
     <tr><td style='text-align:center'>5k</td><td>Wenza waliogundulika kuwa na maambukizi ya VVU kipimo cha kwanza katika kliniki ya wajawazito</td><td style='text-align:center'>$WenzaPositiveVVU1Less</td><td style='text-align:center'>$WenzaPositiveVVU1Greater</td>
     
     <td style='text-align:center'>$TotalWenzaPositiveVVU1</td></tr>
          
    
     <tr><td style='text-align:center'>5l</td><td>Wenza waliopima VVU kipimo cha pili kliniki ya wajawazito</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
         
     <tr><td style='text-align:center'>5m</td><td>Wenza waliogundulika kuwa na maambukizi ya VVU kipimo cha pili katika kliniki ya wajawazito</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     <tr><td style='text-align:center'>5n</td><td>Wajawazito na wenza waliopata majibu tofauti(discordant) baada ya kupima VVU kliniki ya wajawazito</td><td style='text-align:center'>$TofautimatokeoLess</td><td style='text-align:center'>$TofautimatokeoGreater</td>
     
     <td style='text-align:center'>$TotalTofautimatokeo</td></tr>    
     
     <tr><td style='text-align:center'>5o</td><td>Waliopata ushauri juu ya ulishaji wa mtoto</td><td style='text-align:center'>$mtotofeedngLess</td><td style='text-align:center'>$mtotofeedngGreater</td>
     
     <td style='text-align:center'>$Totalmtotofeedng</td></tr>

";
    
      echo "<tr><th style='text-align:center'>6</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Malaria</th></tr>";
      echo "<tr><td style='text-align:center'>6a</td><td>Waliopewa vocha ya hati punguzo</td><td style='text-align:center'>$hatipunguzoLess</td><td style='text-align:center'>$hatipunguzoGreater</td>
     
     <td style='text-align:center'>$Totalhatipunguzo</td></tr>
      
   
     <tr><td style='text-align:center'>6b</td><td>Waliopima maralia kutumia MRDT</td><td style='text-align:center'>$mrdtLess</td><td style='text-align:center'>$mrdtGreater</td>
     
     <td style='text-align:center'>$Totalmrdt</td></tr>
     
      <tr><td style='text-align:center'>6c</td><td>Waliopima malaria positive</td><td style='text-align:center'>$PositivemrdtLess</td><td style='text-align:center'>$PositivemrdtGreater</td>
     
     <td style='text-align:center'>$TotalPositivemrdt</td></tr>
       
     
     <tr><td style='text-align:center'>6d</td><td>Waliopewa IPT2</td><td style='text-align:center'>$IPT2Less</td><td style='text-align:center'>$IPT2Greater</td>
     
     <td style='text-align:center'>$TotalIPT2</td></tr>
       
     <tr><td style='text-align:center'>6e</td><td>Waliopewa IPT4</td><td style='text-align:center'>$IPT4Less</td><td style='text-align:center'>$IPT4Greater</td>
     
     <td style='text-align:center'>$TotalIPT4</td></tr>
     
    ";

     echo "<tr><th style='text-align:center'></th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Huduma Nyingine</th></tr>";
     echo "<tr><td style='text-align:center'>7</td><td>Waliopewa Iron/Folic Acid (I,F,IFA) vidonge vya kutosha mpaka hudhurio linalofuata</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
         
     <tr><td style='text-align:center'>8</td><td>Waliopewa Dawa za minyoo(Albendazole/Mebendazole)</td><td style='text-align:center'>$MabeLess</td><td style='text-align:center'>$MabeGreater</td>
     <td style='text-align:center'>$TotalMabe</td></tr>
     
  
     <tr><td style='text-align:center'>9</td><td>Waliopewa rufaa wakati wa ujauzito</td><td style='text-align:center'>$RufaaLess</td><td style='text-align:center'>$RufaaGreater</td>
     
     <td style='text-align:center'>$TotalRufaa</td></tr>
     
     <tr><td style='text-align:center'>10</td><td>Waliopewa rufaa kwenda CTC</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>

    ";
    echo "</table>";
    echo "</div>";
    
  } ?>
  
  