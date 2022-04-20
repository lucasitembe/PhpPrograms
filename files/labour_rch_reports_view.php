

<?php
   
include("./includes/connection.php");

if(isSet($_POST['d']))
{    
  $date1 = $_POST['d'];
  $date2 = $_POST['d2'];
  
  $jifunguliaHFless=0;
  $jifunguliaHFgreter=0;
  $jifunguliaBBAless=0;
  $jifunguliaBBAgreter=0;
  $jifunguliaTBAless=0;
  $jifunguliaTBAgreter=0;
  $jifunguliaHless=0;
  $jifunguliaHgreter=0;
  $threeALess=0;
  $threeAGreater=0;
  $fourALess=0;
  $fourAGreater=0;
  $fourBLess=0;
  $fourBGreater=0;
  $fourCLess=0;
  $fourCGreater=0;
  $fourDLess=0;
  $fourDGreater=0;
  $fourELess=0;
  $fourEGreater=0;
  $fourFLess=0;
  $fourFGreater=0;
  $fourGLess=0;
  $fourGGreater=0;
  $fiveALess=0;
  $fiveAGreater=0;
  $fiveBLess=0;
  $fiveBGreater=0;
  $fiveCLess=0;
  $fiveCGreater=0;
  $fiveDLess=0;
  $fiveDGreater=0;
  $fiveELess=0;
  $fiveEGreater=0;
  $fiveFLess=0;
  $fiveFGreater=0;
  $fiveGLess=0;
  $fiveGGreater=0;
  $fiveHLess=0;
  $fiveHGreater=0;
  $fiveILess=0;
  $fiveIGreater=0;
  $sixALess=0;
  $sixAGreater=0;
  $sixBLess=0;
  $sixBGreater=0;
  $sixCLess=0;
  $sixCGreater=0;
  $sixDLess=0;
  $sixDGreater=0;
  $sixELess=0;
  $sixEGreater=0;
  $sixFLess=0;
  $sixFGreater=0;
  $sixGLess=0;
  $sixGGreater=0;
  $sixHLess=0;
  $sixHGreater=0;
  $sevenALess=0;
  $sevenAGreater=0;
  $sevenBLess=0;
  $sevenBGreater=0;
  $sevenCLess=0;
  $sevenCGreater=0;
  $sevenDLess=0;
  $sevenDGreater=0;
  $eightALess=0;
  $eightAGreater=0;
  $eightBLess=0;
  $eightBGreater=0;
  $eightCLess=0;
  $eightCGreater=0;
  $eightDLess=0;
  $eightDGreater=0;
  $eightELess=0;
  $eightEGreater=0;
  $eightFLess=0;
  $eightFGreater=0;
  $eightGLess=0;
  $eightGGreater=0;
  $nineALess=0;
  $nineAGreater=0;
  $nineBLess=0;
  $nineBGreater=0;
  $nineCLess=0;
  $nineCGreater=0;
  $nineDLess=0;
  $nineDGreater=0;
  $nineELess=0;
  $nineEGreater=0;
  $nineFLess=0;
  $nineFGreater=0;
  $nineGLess=0;
  $nineGGreater=0;
  $nineHLess=0;
  $nineHGreater=0;
  $elevenALess=0;
  $elevenAGreater=0;
  $elevenBLess=0;
  $elevenBGreater=0;
  $elevenCLess=0;
  $elevenCGreater=0;
  $twelveALess=0;
  $twelveAGreater=0;
  $twelveBLess=0;
  $twelveBGreater=0;
  $twelveCLess=0;
  $twelveCGreater=0;
  $firstphase=mysqli_query($conn,"SELECT * FROM tbl_wazazi tw JOIN tbl_patient_registration tpr ON tw.Patient_No=tpr.Registration_ID");
  $Today = Date("Y-m-d");
  while($result=  mysqli_fetch_assoc($firstphase)){
    $Date_Of_Birth = $result['Date_Of_Birth'];
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y;

    
     if ($result['jifungulia']=='HF' && $age < 20){
       $jifunguliaHFless++;   
     
      } else if($result['jifungulia']=='HF' && $age>=20){
        $jifunguliaHFgreter++;  
      };
      
      
      if ($result['jifungulia']=='BBA' && $age < 20){
       $jifunguliaBBAless++;   
     
      } else if($result['jifungulia']=='BBA' && $age>=20){
        $jifunguliaBBAgreter++;  
      };

       if ($result['jifungulia']=='TBA' && $age < 20){
       $jifunguliaTBAless++;   
     
      } else if($result['jifungulia']=='TBA' && $age>=20){
        $jifunguliaTBAgreter++;  
      };
      
      if ($result['jifungulia']=='H' && $age < 20){
       $jifunguliaHless++;   
     
      } else if($result['jifungulia']=='H' && $age>=20){
        $jifunguliaHgreter++;  
      };
      
       if ($result['uchungu']=='baada12' && $age < 20){
       $threeALess++;   
     
      } else if($result['uchungu']=='baada12' && $age>=20){
        $threeAGreater++;  
      };
      
       if ($result['kujifungua_njia']=='KW' && $age < 20){
       $fourALess++;   
     
      } else if($result['kujifungua_njia']=='KW' && $age>=20){
        $fourAGreater++;  
      };
     
      if ($result['kujifungua_njia']=='VM' && $age < 20){
       $fourBLess++;   
     
      } else if($result['kujifungua_njia']=='VM' && $age>=20){
        $fourBGreater++;  
      };
      if ($result['kujifungua_njia']=='BR' && $age < 20){
       $fourCLess++;   
     
      } else if($result['kujifungua_njia']=='BR' && $age>=20){
        $fourCGreater++;  
      };
      
       if ($result['kujifungua_njia']=='CS' && $age < 20){
       $fourDLess++;   
     
      } else if($result['kujifungua_njia']=='CS' && $age>=20){
        $fourDGreater++;  
      };
 
      
       if ($result['miso']=='Ndiyo' && $age < 20){
       $fourELess++;   
     
      } else if($result['miso']=='Ndiyo' && $age>=20){
        $fourEGreater++;  
      };
      
      if ($result['miso']=='Ndiyo' && $age < 20){
       $fourFLess++;   
     
      } else if($result['miso']=='Ndiyo' && $age>=20){
        $fourFGreater++;  
      };
      
       if ($result['miso']=='Ndiyo' && $age < 20){
       $fourGLess++;   
     
      } else if($result['miso']=='Ndiyo' && $age>=20){
        $fourGGreater++;  
      };
      
      
      if ($result['AP']=='AP' && $age < 20){
       $fiveALess++;   
     
      } else if($result['AP']=='AP' && $age>=20){
        $fiveAGreater++;  
      };
 
       if ($result['AP']=='PROM' && $age < 20){
       $fiveALess++;   
     
      } else if($result['AP']=='PROM' && $age>=20){
        $fiveAGreater++;  
      };
      
      if ($result['AP']=='PROM' && $age < 20){
       $fiveALess++;   
     
      } else if($result['AP']=='PROM' && $age>=20){
        $fiveAGreater++;  
      };
      
      if ($result['AP']=='PE' && $age < 20){
       $fiveCLess++;   
     
      } else if($result['AP']=='PE' && $age>=20){
        $fiveCGreater++;  
      };
      
      if ($result['AP']=='PE' && $age < 20){
       $fiveDLess++;   
     
      } else if($result['AP']=='PE' && $age>=20){
        $fiveDGreater++;  
      };
      
      if($result['AP']=='E' && $age < 20){
       $fiveELess++;   
     
      } else if($result['AP']=='E' && $age>=20){
        $fiveEGreater++;  
      };
      
      if($result['AP']=='A' && $age < 20){
       $fiveFLess++;   
     
      } else if($result['AP']=='A' && $age>=20){
        $fiveFGreater++;  
      };
      
      if($result['AP']=='Malaria' && $age < 20){
       $fiveGLess++;   
     
      } else if($result['AP']=='Malaria' && $age>=20){
        $fiveGGreater++;  
      };
      
       if($result['AP']=='HIV' && $age < 20){
       $fiveHLess++;   
     
      } else if($result['AP']=='HIV' && $age>=20){
        $fiveHGreater++;  
      };
      
      if($result['AP']=='FGM' && $age < 20){
       $fiveILess++;   
     
      } else if($result['AP']=='FGM' && $age>=20){
        $fiveIGreater++;  
      };
      
      if($result['PPH']=='PPH' && $age < 20){
       $sixALess++;   
      } else if($result['PPH']=='PPH' && $age>=20){
        $sixAGreater++;  
      };
      
       if($result['PPH']=='pree' && $age < 20){
       $sixBLess++;   
      } else if($result['PPH']=='pree' && $age>=20){
        $sixBGreater++;  
      };
      
      if($result['PPH']=='ecla' && $age < 20){
       $sixCLess++;   
      } else if($result['PPH']=='ecla' && $age>=20){
        $sixCGreater++;  
      };
      
       if($result['PPH']=='OL' && $age < 20){
       $sixDLess++;   
      } else if($result['PPH']=='OL' && $age>=20){
        $sixDGreater++;  
      };
      
       if($result['PPH']=='RP' && $age < 20){
       $sixELess++;   
      } else if($result['PPH']=='RP' && $age>=20){
        $sixEGreater++;  
      };
      
       if($result['PPH']=='tear' && $age < 20){
       $sixFLess++;   
      } else if($result['PPH']=='tear' && $age>=20){
        $sixFGreater++;  
      };
      
     if($result['PPH']=='tear' && $age < 20){
       $sixFLess++;   
      } else if($result['PPH']=='tear' && $age>=20){
        $sixFGreater++;  
      };

      if($result['PPH']=='tear' && $age < 20){
       $sixHLess++;   
      } else if($result['PPH']=='tear' && $age>=20){
        $sixHGreater++;  
      };
      
      if($result['antibiotic']=='Ndiyo' && $age < 20){
       $sevenALess++;   
      } else if($result['antibiotic']=='Ndiyo' && $age>=20){
        $sevenAGreater++;  
      };
      
       if($result['antibiotic']=='Ndiyo' && $age < 20){
       $sevenBLess++;   
      } else if($result['antibiotic']=='Ndiyo' && $age>=20){
        $sevenBGreater++;  
      };
      
      if($result['sulfate']=='Ndiyo' && $age < 20){
       $sevenCLess++;   
      } else if($result['sulfate']=='Ndiyo' && $age>=20){
        $sevenCGreater++;  
      };
      
       if($result['sulfate']=='Ndiyo' && $age < 20){
       $sevenDLess++;   
        } else if($result['sulfate']=='Ndiyo' && $age>=20){
        $sevenDGreater++;  
      };
      
    if(($result['VVU_Kipimo']=='Positive' || $result['VVU_Kipimo']=='Negative') && $age < 20){
       $eightALess++;   
        } else if(($result['VVU_Kipimo']=='Positive' || $result['VVU_Kipimo']=='Negative') && $age>=20){
       $eightAGreater++;  
    };
  
    if($result['VVU_Kipimo']=='Positive' && $age < 20){
       $eightBLess++;   
        } else if($result['VVU_Kipimo']=='Positive' && $age>=20){
       $eightBGreater++;  
    };
  
        if(($result['VVU_uchungu']=='Positive' || $result['VVU_uchungu']=='Negative') && $age < 20){
       $eightCLess++;   
        } else if(($result['VVU_uchungu']=='Positive' || $result['VVU_uchungu']=='Negative') && $age>=20){
       $eightCGreater++;  
    };
  
   if($result['VVU_uchungu']=='Positive' && $age < 20){
       $eightDLess++;   
        } else if($result['VVU_uchungu']=='Positive' && $age>=20){
       $eightDGreater++;  
    };
    
    if($result['mtoto_ulishaji']=='EBF' && $age < 20){
       $eightELess++;   
        } else if($result['mtoto_ulishaji']=='EBF' && $age>=20){
       $eightEGreater++;  
    };
    
    if($result['mtoto_ulishaji']=='RF' && $age < 20){
       $eightFLess++;   
        } else if($result['mtoto_ulishaji']=='RF' && $age>=20){
       $eightFGreater++;  
    };
  
    if($result['ARV_mtoto']=='Amepewa' && $age < 20){
       $eightGLess++;   
        } else if($result['ARV_mtoto']=='Amepewa' && $age>=20){
       $eightGGreater++;  
    };
    
    if($result['mtoto_hali']=='hai' && $age < 20){
       $nineALess++;   
        } else if($result['mtoto_hali']=='hai' && $age>=20){
       $nineAGreater++;  
    };
    
    if($result['mtotoUzito']<1.5 && $age < 20){
       $nineBLess++;   
        } else if($result['mtotoUzito']<1.5 && $age>=20){
       $nineBGreater++;  
    };
  
    if($result['mtotoUzito']>=2.5 && $age < 20){
       $nineCLess++;   
        } else if($result['mtotoUzito']>=2.5 && $age>=20){
       $nineCGreater++;  
    };
    
    
    if($result['MSB']=='MSB' && $age < 20){
       $nineDLess++;   
        } else if($result['MSB']=='MSB' && $age>=20){
       $nineDGreater++;  
    };
    
     if($result['MSB']=='FSB' && $age < 20){
       $nineELess++;   
        } else if($result['MSB']=='FSB' && $age>=20){
       $nineEGreater++;  
    };
  
     if(($result['VVU_uchungu']=='Positive' || $result['VVU_Kipimo']=='Positive') && $age < 20){
       $nineFLess++;  
        } else if(($result['VVU_uchungu']=='Positive' || $result['VVU_Kipimo']=='Positive') && $age>=20){
       $nineFGreater++;   
    };
  
     if($result['ARV_mtoto']=='Amepewa' && $age < 20){
       $nineGLess++;   
        } else if($result['ARV_mtoto']=='Amepewa' && $age>=20){
       $nineGGreater++;  
    };
    
    if($result['apgar']=='5' && $age < 20){
       $nineHLess++;   
        } else if($result['apgar']=='5' && $age>=20){
       $nineHGreater++;  
    };
    
     if($result['kupumua']=='suction' && $age < 20){
       $elevenALess++;   
        } else if($result['kupumua']=='suction' && $age>=20){
       $elevenAGreater++;  
    };
    
    if($result['kupumua']=='stimulation' && $age < 20){
       $elevenBLess++;   
        } else if($result['kupumua']=='stimulation' && $age>=20){
       $elevenBGreater++;  
    };
  
    if($result['kupumua']=='Mask' && $age < 20){
       $elevenCLess++;   
        } else if($result['kupumua']=='Mask' && $age>=20){
       $elevenCGreater++;  
    };
    
     if($result['nyonyeshwa']=='Ndiyo' && $age < 20){
       $twelveALess++;   
        } else if($result['nyonyeshwa']=='Ndiyo' && $age>=20){
       $twelveAGreater++;  
    };
  
     }
     $TotalHF=$jifunguliaHFless+$jifunguliaHFgreter;
     $TotalBBA=$jifunguliaBBAless+$jifunguliaBBAgreter;
     $TotalTBA=$jifunguliaTBAless+$jifunguliaTBAgreter;
     $TotalH=$jifunguliaHless+$jifunguliaHgreter;
     $TotalLess=$jifunguliaHFless+$jifunguliaBBAless+$jifunguliaTBAless+$jifunguliaHless;
     $TotalGeater=$jifunguliaHFgreter+$jifunguliaBBAgreter+$jifunguliaTBAgreter+$jifunguliaHgreter;
     $TotalWaliofungwa=$TotalLess+$TotalGeater;
     $Total3A=$threeALess+$threeAGreater;
     $Total4A=$fourALess+$fourAGreater;
     $Total4B=$fourBLess+$fourBGreater;
     $Total4C=$fourCLess+$fourCGreater;
     $Total4D=$fourDLess+$fourDGreater;
     $Total4Less=$fourALess+$fourBLess+$fourCLess+$fourDLess;
     $Total4Greater=$fourAGreater+$fourBGreater+$fourCGreater+$fourDGreater;
     $Total4=$Total4Less+$Total4Greater;
     $Total4E=$fourELess+$fourEGreater;
     $Total4F=$fourFLess+$fourFGreater;
     $Total4G=$fourGLess+$fourGGreater;
     $Total5A=$fiveALess+$fiveAGreater;
     $Total5B=$fiveBLess+$fiveBGreater;
     $Total5C=$fiveCLess+$fiveCGreater;
     $Total5D=$fiveDLess+$fiveDGreater;
     $Total5E=$fiveELess+$fiveEGreater;
     $Total5F=$fiveFLess+$fiveFGreater;
     $Total5G=$fiveGLess+$fiveGGreater;
     $Total5H=$fiveHLess+$fiveHGreater;
     $Total5Less=$fiveALess+$fiveBLess+$fiveCLess+$fiveDLess+$fiveELess+$fiveFLess+$fiveGLess+$fiveHLess;
     $Total5Greater=$fiveAGreater+$fiveBGreater+$fiveCGreater+$fiveDGreater+$fiveEGreater+$fiveFGreater+$fiveGGreater+$fiveHGreater;
     $Total5=$Total5Less+$Total5Greater;
     $Total5I=$fiveILess+$fiveIGreater;
     $Total6A=$sixALess+$sixAGreater;
     $Total6B=$sixBLess+$sixBGreater;
     $Total6C=$sixCLess+$sixCGreater;
     $Total6D=$sixDLess+$sixDGreater;
     $Total6E=$sixELess+$sixEGreater;
     $Total6F=$sixFLess+$sixFGreater;
     $Total7A=$sevenALess+$sevenAGreater;
     $Total7B=$sevenBLess+$sevenBGreater;
     $Total7C=$sevenCLess+$sevenCGreater;
     $Total7D=$sevenDLess+$sevenDGreater;
     $Total8A=$eightALess+$eightAGreater;
     $Total8B=$eightBLess+$eightBGreater;
     $Total8C=$eightCLess+$eightCGreater;
     $Total8D=$eightDLess+$eightDGreater;
     $Total8E=$eightELess+$eightEGreater;
     $Total8F=$eightFLess+$eightFGreater;
     $Total8G=$eightGLess+$eightGGreater;
     $Total9A=$nineALess+$nineAGreater;
     $Total9B=$nineBLess+$nineBGreater;
     $Total9C=$nineCLess+$nineCGreater;
     $Total9D=$nineDLess+$nineDGreater;
     $Total9E=$nineELess+$nineEGreater;
     $Total9F=$nineFLess+$nineFGreater;
     $Total9G=$nineGLess+$nineGGreater;
     $Total9less=$nineALess+$nineDLess+$nineELess;
     $Total9greater=$nineAGreater+$nineDGreater+$nineEGreater;
     $Total9=$Total9less+$Total9greater;
     $Total9H=$nineHLess+$nineHGreater;
     $Total11A=$elevenALess+$elevenAGreater;
     $Total11B=$elevenBLess+$elevenBGreater;
     $Total11C=$elevenCLess+$elevenCGreater;
     $Total12A=$twelveALess+$twelveAGreater;
    echo "<div id='all'>";
    echo "<div id='hudhurio1'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
     echo "<tr><td style='text-align:center'>1</td><td>Waliotarajiwa kujifungua katika eneo la huduma</td><td style='text-align:center'><input type='text' class='expected' id='umrichini' style='width:100px;text-align:Center'></td><td style='text-align:center'><input type='text' class='expected' id='umrizaidi' style='width:100px;text-align:center'></td><td style='text-align:center'><input type='text' readonly='true' id='totalExpected' style='text-align:center'></td></tr>";
     echo "<tr><th style='text-align:center'>2</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Taarifa ya Waliojifungua</th></tr>";
     
      
    echo "<tr><td style='text-align:center'>2a</td><td>Waliojifungulia katika kituo cha kutolea huduma za afya</td><td style='text-align:center'><sapn id='lessHF'>$jifunguliaHFless</span></td><td style='text-align:center'><span id='greaterHF'>$jifunguliaHFgreter</span></td><td style='text-align:center'><span id='Total'>$TotalHF</span></td>
     
        
        <tr><td style='text-align:center'>2b</td><td>Waliojifungua kabla ya kufika kituoni(BBA)</td><td style='text-align:center'>$jifunguliaBBAless</td><td style='text-align:center'>$jifunguliaBBAgreter</td><td style='text-align:center'>$TotalBBA</td>
        <tr><td style='text-align:center'>2c</td><td>Waliozalishwa na wakunga wa jadi(TBA)</td><td style='text-align:center'>$jifunguliaTBAless</td><td style='text-align:center'>$jifunguliaTBAgreter</td><td style='text-align:center'>$TotalTBA</td>
        <tr><td style='text-align:center'>2d</td><td>Waliojifungua nyumbani(H) bila msaada wa TBA</td><td style='text-align:center'>$jifunguliaHless</td><td style='text-align:center'>$jifunguliaHgreter</td><td style='text-align:center'>$TotalH</td>
        
    <tr><td style='text-align:center'></td><td><b><i>Jumla ya waliojifungua(2a+2b+2c+2d)</i></b></td><td style='text-align:center'><b><i>$TotalLess</i></b></td><td style='text-align:center'><b><i>$TotalGeater</i></b></td><td style='text-align:center'><b><i>$TotalWaliofungwa</i></b></td>
    
    <tr><td style='text-align:center'>2e</td><td>Waliozalishwa na watoa huduma wenye ujuzi</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
    <tr><td style='text-align:center'></td><td><b><i>Asilimia ya waliojifungulia katika vituo vya kutolea huduma za afya(2a/1)*100</i></b></td><td style='text-align:center'><b><i><span id='huduma' style='text-align:center'></span></i></b></td><td style='text-align:center'><b><i><span id='ghuduma'></span></b></i></td><td style='text-align:center'><b><i><span id='gtotal'></span></i></b></td>
   
    </tr>";
     
     echo "<tr><th style='text-align:center'>3</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Taarifa kuhusu uchungu</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>3a</td><td>Waliojifungua baada ya saa 12 toka uchungu kuanza</td><td style='text-align:center'>$threeALess</td><td style='text-align:center'>$threeAGreater</td>
       
   
     <td style='text-align:center'>$Total3A</td></tr>";
         
      echo "<tr><th style='text-align:center'>4</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Taarifa ya njia za kujifungua</th></tr>";
     
     
      
    echo "<tr><td style='text-align:center'>4a</td><td>Kawaida (KW)</td><td style='text-align:center'>$fourALess</td><td style='text-align:center;'>$fourAGreater</td>
     
     <td style='text-align:center'>$Total4A</td></tr>

     <tr><td style='text-align:center'>4b</td><td>Vacuum (VM)</td><td style='text-align:center;'>$fourBLess</td><td style='text-align:center'>$fourBGreater</td>
     
     <td style='text-align:center'>$Total4B</td></tr>
     
     <tr><td style='text-align:center'>4c</td><td>Breech delivery(BR)</td><td style='text-align:center'>$fourCLess</td><td style='text-align:center'>$fourCGreater</td>
     
     <td style='text-align:center'>$Total4C</td></tr>


     <tr><td style='text-align:center'>4d</td><td>Caesarian Section(CS)</td><td style='text-align:center'>$fourDLess</td><td style='text-align:center'>$fourDGreater</td>
     
     <td style='text-align:center'>$Total4D</td></tr>
         
     <tr><td style='text-align:center'></td><td><b><i>Jumla(4a+4b+4c+4d)</i></b></td><td style='text-align:center'><b><i>$Total4Less</i></b></td><td style='text-align:center'><b><i>$Total4Greater</i></b></td><td style='text-align:center'><b><i>$Total4</i></b></td>
    
         
     <tr><td style='text-align:center'>4e</td><td>Idadi ya wanawake waliopata Oxytocin baada ya kujifungua</td><td style='text-align:center'>$fourELess</td><td style='text-align:center'>$fourEGreater</td>
     
     <td style='text-align:center'>$Total4E</td></tr>
     
     <tr><td style='text-align:center'>4f</td><td>Idadi ya wanawake waliopata Egometrine baada ya kujifungua</td><td style='text-align:center'>$fourFLess</td><td style='text-align:center'>$fourFGreater</td>
     
     <td style='text-align:center'>$Total4F</td></tr>
      
  
     <tr><td style='text-align:center'>4g</td><td>Idadi ya wanawake waliopata Misoprostol baada ya kujifungua</td><td style='text-align:center'>$fourGLess</td><td style='text-align:center'>$fourGGreater</td>
     
     <td style='text-align:center'>$Total4G</td></tr>";
    
    
    echo "<tr><th style='text-align:center'>5</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Matatizo kabla ya kujifungua</th></tr>";
    echo "<tr><td style='text-align:center'>5a</td><td>APH</td><td style='text-align:center'>$fiveALess</td><td style='text-align:center'>$fiveAGreater</td>
     
     <td style='text-align:center'>$Total5A</td></tr>
       
     
     <tr><td style='text-align:center'>5b</td><td>Pre-mature Rupture of Membrane(PROM)</td><td style='text-align:center'>$fiveBLess</td><td style='text-align:center'>$fiveBGreater</td>
     
     <td style='text-align:center'>$Total5B</td></tr>

      
     <tr><td style='text-align:center'>5c</td><td>High BP</td><td style='text-align:center'>$fiveCLess</td><td style='text-align:center'>$fiveCGreater</td>
     
     <td style='text-align:center'>$Total5C</td></tr>
     
     <tr><td style='text-align:center'>5d</td><td>Pre eclampsia</td><td style='text-align:center'>$fiveDLess</td><td style='text-align:center'>$fiveDGreater</td>
     
     <td style='text-align:center'>$Total5D</td></tr>
      
     <tr><td style='text-align:center'>5e</td><td>Eclampsia</td><td style='text-align:center'>$fiveELess</td><td style='text-align:center'>$fiveEGreater</td>
     
     <td style='text-align:center'>$Total5E</td></tr>
    
     <tr><td style='text-align:center'>5f</td><td>Anaemia</td><td style='text-align:center'>$fiveFLess</td><td style='text-align:center'>$fiveFGreater</td>
     
     <td style='text-align:center'>$Total5F</td></tr>
    
   
     <tr><td style='text-align:center'>5g</td><td>Malaria</td><td style='text-align:center'>$fiveGLess</td><td style='text-align:center'>$fiveGGreater</td>
     
     <td style='text-align:center'>$Total5G</td></tr>
         
     <tr><td style='text-align:center'>5h</td><td>HIV + stage III au IV</td><td style='text-align:center'>$fiveHLess</td><td style='text-align:center'>$fiveHGreater</td>
     
     <td style='text-align:center'>$Total5H</td></tr>
    
     <tr><td style='text-align:center'></td><td><b><i>Jumla wenye matatizo kabla ya kujifungua(5a+5b+5c+5d+5e+5f+5g+5h)</i></b></td><td style='text-align:center'><b><i>$Total5Less</i></b></td><td style='text-align:center'><b><i>$Total5Greater</i></b></td><td style='text-align:center'><b><i>$Total5</i></b></td>
        
     <tr><td style='text-align:center'>5i</td><td>Waliokeketwa(FGM)</td><td style='text-align:center'>$fiveILess</td><td style='text-align:center'>$fiveIGreater</td>
     
     <td style='text-align:center'>$Total5I</td></tr>";
    
      echo "<tr><th style='text-align:center'>6</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Matatizo wakati na baada ya kujifungua</th></tr>";
      echo "<tr><td style='text-align:center'>6a</td><td>PPH</td><td style='text-align:center'>$sixALess</td><td style='text-align:center'>$sixAGreater</td>
     
     <td style='text-align:center'>$Total6A</td></tr>
      
   
     <tr><td style='text-align:center'>6b</td><td>Pre eclampsia</td><td style='text-align:center'>$sixBLess</td><td style='text-align:center'>$sixBGreater</td>
     
     <td style='text-align:center'>$Total6B</td></tr>
     
      <tr><td style='text-align:center'>6c</td><td>Eclampsia</td><td style='text-align:center'>$sixCLess</td><td style='text-align:center'>$sixCGreater</td>
     
     <td style='text-align:center'>$Total6C</td></tr>
       
     
     <tr><td style='text-align:center'>6d</td><td>Obstructed Labour</td><td style='text-align:center'>$sixDLess</td><td style='text-align:center'>$sixDGreater</td>
     
     <td style='text-align:center'>$Total6D</td></tr>
       
     <tr><td style='text-align:center'>6e</td><td>Retained placenta</td><td style='text-align:center'>$sixELess</td><td style='text-align:center'>$sixEGreater</td>
     
     <td style='text-align:center'>$Total6E</td></tr>
         
     <tr><td style='text-align:center'>6f</td><td>Third degree tear</td><td style='text-align:center'>$sixFLess</td><td style='text-align:center'>$sixFGreater</td>
     
     <td style='text-align:center'>$Total6F</td></tr>
    
     <tr><td style='text-align:center'>6g</td><td>Ruptured Uterus</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     
     <tr><td style='text-align:center'>6h</td><td>Uambukizo/Sepsis</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
         
      <tr><td style='text-align:center'></td><td><b><i>Jumla ya matatizo baada ya kujifungua(6a+6b+6c+6d+6e+6f+6g)</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td>

    ";

     echo "<tr><th style='text-align:center'>7</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>EmOC</th></tr>";
     echo "<tr><td style='text-align:center'>7a</td><td>Amepewa Antibiotic</td><td style='text-align:center'>$sevenALess</td><td style='text-align:center'>$sevenAGreater</td>
     
     <td style='text-align:center'>$Total7A</td></tr>
         
     <tr><td style='text-align:center'>7b</td><td>Amepewa Uterotonic</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     <td style='text-align:center'>0</td></tr>
     
  
     <tr><td style='text-align:center'>7c</td><td>Amepewa Magnesium Sulphate</td><td style='text-align:center'>$sevenCLess</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>$Total7C</td></tr>
     
     <tr><td style='text-align:center'>7d</td><td>Ameondolewa kondo la nyuma kwa mkono</td><td style='text-align:center'>0</td><td style='text-align:center'>$sevenCGreater</td>
     
     <td style='text-align:center'>0</td></tr>

    ";
     
     echo "<tr><th style='text-align:center'>8</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>PMTCT</th></tr>";
     echo "<tr><td style='text-align:center'>8a</td><td>Jumla waliopima VVU ANC</td><td style='text-align:center'>$eightALess</td><td style='text-align:center'>$eightAGreater</td>
     
     <td style='text-align:center'>$Total8A</td></tr>
         
     <tr><td style='text-align:center'>8b</td><td>Waliogundulika Positive kutoka ANC</td><td style='text-align:center'>$eightBLess</td><td style='text-align:center'>$eightBGreater</td>
     <td style='text-align:center'>$Total8B</td></tr>
     
     
     <tr><td style='text-align:center'>8c</td><td>Jumla waliopimwa VVU wakati na baada ya kujifungua</td><td style='text-align:center'>$eightCLess</td><td style='text-align:center'>$eightCGreater</td>
     
     <td style='text-align:center'>$Total8C</td></tr>
     
     <tr><td style='text-align:center'>8d</td><td>Waliogundulika Positive wakati na baada ya kujifungua</td><td style='text-align:center'>$eightDLess</td><td style='text-align:center'>$eightDGreater</td>
     
     <td style='text-align:center'>$Total8D</td></tr>
    
      <tr><td style='text-align:center'></td><td><b><i>Jumla walio na VVU(8b+8f)</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td>
      
      <tr><td style='text-align:center'>8e</td><td>Waliochagua kunyonyesha maziwa ya mama pekee(EBF)</td><td style='text-align:center'>$eightELess</td><td style='text-align:center'>$eightEGreater</td>
     
     <td style='text-align:center'>$Total8E</td></tr>
         
     <tr><td style='text-align:center'>8f</td><td>Waliochagua ulishaji wa maziwa mbadala(RF)</td><td style='text-align:center'>$eightFLess</td><td style='text-align:center'>$eightFGreater</td>
     <td style='text-align:center'>$Total8F</td></tr>
     
  
     <tr><td style='text-align:center'>8g</td><td>Waliopewa ARV Prophylaxis(tail) wakati wa kuruhusiwa</td><td style='text-align:center'>$eightGLess</td><td style='text-align:center'>$eightGGreater</td>
     
     <td style='text-align:center'>$Total8G</td></tr>
     
     <tr><td style='text-align:center'>8h</td><td>Waliopata rufaa kwenda kliniki ya huduma na matibabu ya wenye VVU (CTC)</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>

    ";
     
     echo "<tr><th style='text-align:center'>9</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Watoto waliozaliwa mmoja</th></tr>";
     echo "<tr><td style='text-align:center'>9a</td><td>Jumla ya watoto waliozaliwa hai</td><td style='text-align:center'>$nineALess</td><td style='text-align:center'>$nineAGreater</td>
     
     <td style='text-align:center'>$Total9A</td></tr>
         
     <tr><td style='text-align:center'>9b</td><td>Waliozaliwa hai Uzito<1.5kg</td><td style='text-align:center'>$nineBLess</td><td style='text-align:center'>$nineBGreater</td>
     <td style='text-align:center'>$Total9B</td></tr>
     
  
     <tr><td style='text-align:center'>9c</td><td>Waliozaliwa hai Uzito=>2.5</td><td style='text-align:center'>$nineCLess</td><td style='text-align:center'>$nineCGreater</td>
     
     <td style='text-align:center'>$Total9C</td></tr>
     
     <tr><td style='text-align:center'>9d</td><td>Waliozaliwa wafu Macerated (MSB)</td><td style='text-align:center'>$nineDLess</td><td style='text-align:center'>$nineDGreater</td>
     
     <td style='text-align:center'>$Total9D</td></tr>
     
     <tr><td style='text-align:center'>9e</td><td>Waliozaliwa wafu Fresh(FSB)</td><td style='text-align:center'>$nineELess</td><td style='text-align:center'>$nineEGreater</td>
     
     <td style='text-align:center'>$Total9E</td></tr>
     
     <tr><td style='text-align:center'>9f</td><td>Waliozaliwa na mama wenye VVU</td><td style='text-align:center'>$nineFLess</td><td style='text-align:center'>$nineFGreater</td>
     
     <td style='text-align:center'>$Total9F</td></tr>
     
     <tr><td style='text-align:center'>9g</td><td>Waliopewa dawa za ARV</td><td style='text-align:center'>$nineGLess</td><td style='text-align:center'>$nineGGreater</td>
     
     <td style='text-align:center'>$Total9G</td></tr>
      
      <tr><td style='text-align:center'></td><td><b><i>Jumla ya watoto waliozaliwa(hai na wafu)(9a+9d+9e)</i></b></td><td style='text-align:center'><b><i>$Total9less</i></b></td><td style='text-align:center'><b><i>$Total9greater</i></b></td><td style='text-align:center'><b><i>$Total9</i></b></td>
      
      <tr><td style='text-align:center'>9h</td><td>Watoto wenye APGAR Score chini ya 7 katika dakika 5</td><td style='text-align:center'>$nineHLess</td><td style='text-align:center'>$nineHGreater</td>
     
     <td style='text-align:center'>$Total9H</td></tr>
    ";
     
     echo "<tr><th style='text-align:center'>10</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Watoto waliozaliwa mapacha</th></tr>";
     echo "<tr><td style='text-align:center'>10a</td><td>Jumla ya watoto waliozaliwa hai</td><td style='text-align:center'>$twelveALess</td><td style='text-align:center'>$twelveAGreater</td>
     
     <td style='text-align:center'>$Total12A</td></tr>
         
     <tr><td style='text-align:center'>10b</td><td>Waliozaliwa hai Uzito<1.5kg</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     <td style='text-align:center'>0</td></tr>
     
  
     <tr><td style='text-align:center'>10c</td><td>Waliozaliwa hai Uzito=>2.5</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     
     <tr><td style='text-align:center'>10d</td><td>Waliozaliwa wafu Macerated (MSB)</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     
     <tr><td style='text-align:center'>10e</td><td>Waliozaliwa wafu Fresh(FSB)</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     <tr><td style='text-align:center'>10f</td><td>Waliozaliwa na mama wenye VVU</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     <tr><td style='text-align:center'>10g</td><td>Waliopewa dawa za ARV</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
     
      <tr><td style='text-align:center'></td><td><b><i>Jumla ya watoto waliozaliwa(hai na wafu)(10a+10f+10g)</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td><td style='text-align:center'><b><i>0</i></b></td>
      
      <tr><td style='text-align:center'>10h</td><td>Watoto wenye APGAR Score chini ya 7 katika dakika 5</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
    ";
     
     
      echo "<tr><th style='text-align:center'>11</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Watoto waliosaidiwa kupumua</th></tr>";
     echo "<tr><td style='text-align:center'>11a</td><td>Idadi ya watoto waliosaidiwa kupumua-suction</td><td style='text-align:center'>$elevenALess</td><td style='text-align:center'>$elevenAGreater</td>
     
     <td style='text-align:center'>$Total11A</td></tr>
         
     <tr><td style='text-align:center'>11b</td><td>Idadi ya watoto waliosaidiwa kupumua-stimulation</td><td style='text-align:center'>$elevenBLess</td><td style='text-align:center'>$elevenBGreater</td>
     <td style='text-align:center'>$Total11B</td></tr>
     
  
     <tr><td style='text-align:center'>11c</td><td>Idadi ya watoto waliosaidiwa kupumua-bag and mask</td><td style='text-align:center'>$elevenCLess</td><td style='text-align:center'>$elevenCGreater</td>
     
     <td style='text-align:center'>$Total11C</td></tr>";
     
     echo "<tr><th style='text-align:center'>12</th><th  style='background-color:#006400;color:white;padding:2px;text-align:left' colspan='7'>Huduma nyinginezo</th></tr>";
     echo "<tr><td style='text-align:center'>12a</td><td>Idadi ya watoto walionyonyeshwa ndani ya saa moja baada ya kuzaliwa</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>
         
     <tr><td style='text-align:center'>12b</td><td>Idadi ya Mama waliopewa rufaa</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     <td style='text-align:center'>0</td></tr>
     
  
     <tr><td style='text-align:center'>12c</td><td>Idadi ya watoto waliopewa rufaa</td><td style='text-align:center'>0</td><td style='text-align:center'>0</td>
     
     <td style='text-align:center'>0</td></tr>";
     
    echo "</table>";
    echo "</div>";
    
  } ?>
  
  <script>
// we used jQuery 'keyup' to trigger the computation as the user type
$('.expected').keyup(function () {
 
    // initialize the sum (total price) to zero
    var sum = 0;
     
    // we use jQuery each() to loop through all the textbox with 'price' class
    // and compute the sum for each loop
    $('.expected').each(function() {
        sum += Number($(this).val());
    });
      
     var less= $('#lessHF').html();
     var greater=$('#greaterHF').html();
     
     
     var umrichini=$('#umrichini').val();
     var umrizaidi=$('#umrizaidi').val();
     
     var hfless=(less/umrichini*100).toFixed(2);
     var hfgreater=(greater/umrizaidi*100).toFixed(2);
     
    // set the computed value to 'totalPrice' textbox
    $('#totalExpected').val(sum);
    $('#huduma').html(hfless);
    $('#ghuduma').html(hfgreater);
    
   
     
});

$('.expected').on('input',function(){
    var Total=$('#Total').html();
   var totalExpected= $('#totalExpected').val();
   var htotal=(Total/totalExpected*100).toFixed(2);
   $('#gtotal').html(htotal);
});
</script>