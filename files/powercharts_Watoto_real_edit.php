
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Rch_Works'])) {
        if ($_SESSION['userinfo']['Rch_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        echo "<a href='searchvisitorsoutpatientlistrchmahudhurio.php?section=Rch&RchWorks=RchWorksThisPage' class='art-button-green'>BACK</a>";
    }
}
?>
<script>
    $(function () {
        $("#datepickery").datepicker();
    });
</script> 


<?php
if (isset($_GET['pn'])) {

    $pn = $_GET['pn'];

    $select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender,pr.registration_id from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));
    //display all items
    while ($row2 = mysqli_fetch_array($select_Patient_Details)) {
        $Today = Date("Y-m-d");
        $Date_Of_Birth = $row2['Date_Of_Birth'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y;
        $age = $diff->m;
        $age = $diff->d;


        $name = $row2['Patient_Name'];
        $gende = $row2['Gender'];
        $regNo = $row2['registration_id'];
    }
    
    
    $select=  mysqli_query($conn,"SELECT * FROM tbl_watoto WHERE watoto_ID='$pn'");
    while ($result=  mysqli_fetch_assoc($select)){
      $Identity_No=$result['Identity_No'];  
      $Birth_reg_No=$result['Birth_reg_No']; 
      $Mtoto_Jina=$result['Mtoto_Jina']; 
      $Birth_date=$result['Birth_date'];
      $Address=$result['Address'];
      $Jinsi=$result['Jinsi'];
      $Ana_TT2=$result['Ana_TT2'];
      $VVU_Hali=$result['VVU_Hali'];
      $HEID_No=$result['HEID_No'];
      $BCG=$result['BCG'];
      $OPVO=$result['OPVO'];
      $PENTA_1=$result['PENTA_1'];
      $PENTA_2=$result['PENTA_2'];
      $PENTA_3=$result['PENTA_3'];
      $Polio_1=$result['Polio_1'];
      $Polio_2=$result['Polio_2'];
      $Polio_3=$result['Polio_3'];
      $PCV_1=$result['PCV_1'];
      $PCV_2=$result['PCV_2'];
      $PCV_3=$result['PCV_3'];
      $Rota_1=$result['Rota_1'];
      $Rota_2=$result['Rota_2'];
      $Surua_1=$result['Surua_1'];
      $Surua_2=$result['Surua_2'];
      $VM_6=$result['VM_6'];
      $V_U_mwaka=$result['V_U_mwaka'];
      $V_mwaka_1_5=$result['V_mwaka_1_5'];
      $Uz_um_9=$result['Uz_um_9'];
      $Uz_ur_9=$result['Uz_ur_9'];
      $Ur_um_9=$result['Ur_um_9'];
      $Uz_um_18=$result['Uz_um_18'];
      $Uz_ur_18=$result['Uz_ur_18'];
      $Ur_um_18=$result['Ur_um_18'];
      $Uz_um_36=$result['Uz_um_36'];
      $Uz_ur_36=$result['Uz_ur_36'];
      $Ur_um_36=$result['Ur_um_36'];
      $Uz_um_48=$result['Uz_um_48'];
      $Uz_ur_48=$result['Uz_ur_48'];
      $Ur_um_48=$result['Ur_um_48'];
      $AM_12=$result['AM_12'];
      $AM_18=$result['AM_18'];
      $AM_24=$result['AM_24'];
      $AM_30=$result['AM_30'];
      $Hati_punguzo=$result['Hati_punguzo'];
      $Mama_maziwa=$result['Mama_maziwa'];
      $maziwa_mbadala=$result['maziwa_mbadala'];
      $kituo_alikotoka=$result['kituo_alikotoka'];
      $alikopelekwa=$result['alikopelekwa'];
      $Rufaa_sababu=$result['Rufaa_sababu'];
      $maoni=$result['maoni'];
    }
    
}
?>  
<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">  
    <legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA WATOTO</b></legend>
    <div class="tabcontents" style="height:550px;overflow:auto" >
        <div id="tabs-1">
            <center> 
                <table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Namba ya Utambulisho
                        </td>
                        <td width="40%">
                            <input  type="text" id="utambulisho_No" value="<?php echo $Identity_No;?>">
                        </td>
                        <td  style="text-align:right;" width="20%">Namba ya Usajili wa vizazi</td>
                        <td  width="40%" colspan="2">

                            <input style="width:240px;" id="birth_reg_No" name="" type="text" value="<?php echo $Birth_reg_No;?>">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Jina la mtoto
                        </td>
                        <td >
                            <input name="jinakamili" id="mtoto_Jina"  type="text" value="<?php echo $Mtoto_Jina;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Tarehe ya kuzaliwa
                        </td>
                        <td>
                            <input name="" id="birth_date"  type="text" value="<?php echo $Birth_date;?>">
                        </td> 
                    </tr>

                    <tr>
                        <td width="20%" class="powercharts_td_left" style="text-align:right">
                            Mahali Anapoishi(Kitongoji/Mtaa)
                        </td>
                        <td>
                            <input name="name" id="kijiji_jina" type="text" value="<?php echo $Address;?>">
                        </td>
                        <td  colspan="" align="right" style="text-align:right;">
                            Jinsi
                        </td>
                        <td>
                            <?php
                            if($Jinsi=='KE'){
                                echo '
                                    <span id="spanjinsike"><input type="radio" checked="true" name="jinsi" id="jinsike">KE</span>
                                    <span id="spanjinsime"><input type="radio" name="jinsi" id="jinsime">ME</span>
                                    ';
                            }elseif ($Jinsi=='ME') {
                                echo '
                                    <span id="spanjinsike"><input type="radio" name="jinsi" id="jinsike">KE</span>
                                    <span id="spanjinsime"><input type="radio" checked="true" name="jinsi" id="jinsime">ME</span>
                                    ';
                            }else{
                                echo '    
                                    <span id="spanjinsike"><input type="radio" name="jinsi" id="jinsike">KE</span>
                                   <span id="spanjinsime"><input type="radio" name="jinsi" id="jinsime">ME</span>
                                  ';  
                            }
                            
                            ?>
                        </td> 
                    </tr>

                </table>
                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="38%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Taarifa za Mama </td> <td width="29%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tarehe ya Chanjo ya PENTA</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                Jina la Mama
                                            </td>
                                            <td>
                                                <input type="text" style="width:200px" id="mother_name" value="<?php echo $name; ?>">

                                            </td>


                                        </tr>

                                        <tr>
                                            <td >
                                                Ana kinga ya pepopunda (TT2+)?
                                            </td>
                                            <td>
                                                <?php
                                                if($Ana_TT2=='N'){
                                                    echo '
                                                        <span id="spanTT2H"><input type="radio" name="tt2" id="tt2H">H</span>
                                                       <span id="spanTT2N"><input type="radio" checked="true" name="tt2" id="tt2N">N</span>
                                                        <span id="spanTT2U"><input type="radio" name="tt2" id="tt2U">U</span>
                                                      ';  
                                                    
                                                }elseif ($Ana_TT2=='H') {
                                                    echo '
                                                      <span id="spanTT2H"><input type="radio" checked="true" name="tt2" id="tt2H">H</span>
                                                      <span id="spanTT2N"><input type="radio" name="tt2" id="tt2N">N</span>
                                                     <span id="spanTT2U"><input type="radio" name="tt2" id="tt2U">U</span>
                                                        ';
                                                    
                                                } elseif($Ana_TT2=='U'){
                                                 echo '
                                                <span id="spanTT2H"><input type="radio" name="tt2" id="tt2H">H</span>
                                                <span id="spanTT2N"><input type="radio" name="tt2" id="tt2N">N</span>
                                                <span id="spanTT2U"><input type="radio" checked="true" name="tt2" id="tt2U">U</span>
                                                 ';  
   
                                                }else{
                                                    echo '
                                                        <span id="spanTT2H"><input type="radio" name="tt2" id="tt2H">H</span>
                                                        <span id="spanTT2N"><input type="radio" name="tt2" id="tt2N">N</span>
                                                         <span id="spanTT2U"><input type="radio" name="tt2" id="tt2U">U</span>
                                                         '; 
                                                    
                                                }
                                                
                                                ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                Hali ya VVU
                                            </td>
                                            <td>
                                                <input type="text" style="width:200px" id="VVU_hali" value="<?php echo $VVU_Hali;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td >
                                                HEID No
                                            </td>
                                            <td>
                                                <input type="text" style="width:200px" id="heid_No" value="<?php echo $HEID_No;?>">

                                            </td>

                                        </tr>



                                    </table>


                                </td><td >           
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:60px;">BCG</td>
                                            <td width="20%">
                                                <input type="text" id="BCG" style="width:300px" value="<?php echo $BCG;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:60px;">
                                                OPVO
                                            </td>
                                            <td>
                                                <input type="text" id="OPVO" style="width:300px" value="<?php echo $OPVO;?>">
                                            </td>
                                        </tr>


                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">1</td>
                                            <td width="25%"> 
                                                <input type="text" style="width:350px;" id="penta_1" value="<?php echo $PENTA_1;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:350px;" name="mimbanamba" id="penta_2" value="<?php echo $PENTA_2;?>" >

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                3
                                            </td>
                                            <td>
                                                <input type="text" style="width:350px;" name="mimbanamba" id="penta_3" value="<?php echo $PENTA_3;?>">

                                            </td>
                                        </tr>


                                    </table>   

                                </td>
                    </tr>
                </table>       
                </td>
                </tr>
                </table>

                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo ya Polio</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo Pneumococcal (PCV13)</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tarehe ya Chanjo ya Rota</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                1
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="polio_1" value="<?php echo $Polio_1;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="polio_2" value="<?php echo $Polio_2;?>">

                                            </td>

                                        </tr>


                                        <tr>
                                            <td style="text-align:right;">
                                                3
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="polio_3" value="<?php echo $Polio_3;?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td><td >           
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                1
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="PCV_1" value="<?php echo $PCV_1;?>">

                                            </td>

                                        </tr>



                                        <tr>
                                            <td style="text-align:right;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="PCV_2" value="<?php echo $PCV_2;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                3
                                            </td>
                                            <td>
                                                <input type="text" style="width:340px" id="PCV_3" value="<?php echo $PCV_3;?>">

                                            </td>

                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">1</td>
                                            <td width="25%"> 
                                                <input type="text" style="width:370px;" id="Rota_1" value="<?php echo $Rota_1;?>">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:370px;" name="mimbanamba" id="Rota_2" value="<?php echo $Rota_2;?>">

                                            </td>
                                        </tr>


                                    </table>   

                                </td>
                    </tr>
                </table>       
                </td>
                </tr>
                </table>



                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="35%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya chanjo ya Surua/Rubella</td> <td width="30%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Vitamini A</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Ukuaji wa mtoto (1=>80% au >-2SD,2=80% au -2SD--3SD;3=<60% au <-3SD) </td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;">
                                                1
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="surua_1" value="<?php echo $Surua_1;?>">

                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                2
                                            </td>
                                            <td>
                                                <input type="text" style="width:400px" id="surua_2" value="<?php echo $Surua_2;?>">
                                            </td>

                                        </tr>


                                    </table>
                                </td><td >           
                                    <table width="100%">

                                        <tr>
                                            <td style="text-align:right;">
                                                Miezi 6
                                            </td>
                                            <td>
                                                <?php 
                                                if($VM_6=='N'){
                                                     echo '
                                                    <span id="spanVM_6N"><input type="radio" checked="true" id="VM_6N" name="VM_6">Ndiyo</span>
                                                    <span id="spanVM_6H"><input type="radio" id="VM_6H" name="VM_6">Hapana</span>
                                                   ';
                                                }elseif($VM_6=='H'){
                                                     echo '
                                                    <span id="spanVM_6N"><input type="radio" id="VM_6N" name="VM_6">Ndiyo</span>
                                                    <span id="spanVM_6H"><input type="radio" checked="true" id="VM_6H" name="VM_6">Hapana</span>
                                                   ';
                                                    
                                                    
                                                }  else {
                                                    echo '
                                                    <span id="spanVM_6N"><input type="radio" id="VM_6N" name="VM_6">Ndiyo</span>
                                                    <span id="spanVM_6H"><input type="radio" id="VM_6H" name="VM_6">Hapana</span>
                                                   ';
                                                }
                                                
                                                
                                                ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                chini ya mwaka
                                            </td>
                                            <td>
                                                <?php
                                                if($V_U_mwaka=='N'){
                                                     echo ' 
                                                        <span id="spanVM_U_mwakaN"><input type="radio" checked="true" name="VM_U_mwaka" id="VM_U_mwakaN">Ndiyo</span>
                                                        <span id="spanVM_U_mwakaH"><input type="radio" name="VM_U_mwaka" id="VM_U_mwakaH">Hapana</span>
                                                        ';
                                                    
                                                }elseif ($V_U_mwaka=='H') {
                                                    
                                                     echo ' 
                                                        <span id="spanVM_U_mwakaN"><input type="radio" name="VM_U_mwaka" id="VM_U_mwakaN">Ndiyo</span>
                                                        <span id="spanVM_U_mwakaH"><input type="radio" checked="true" name="VM_U_mwaka" id="VM_U_mwakaH">Hapana</span>
                                                        ';
                                                    
                                                }  else {
                                                    
                                                    echo ' 
                                                        <span id="spanVM_U_mwakaN"><input type="radio" name="VM_U_mwaka" id="VM_U_mwakaN">Ndiyo</span>
                                                        <span id="spanVM_U_mwakaH"><input type="radio" name="VM_U_mwaka" id="VM_U_mwakaH">Hapana</span>
                                                        ';
                                                }
                                                
                                                ?>
                                             
                                            </td>

                                        </tr>

                                        <tr>
                                            <td style="text-align:right;">
                                                mwaka 1-5
                                            </td>
                                            <td>
                                                <?php
                                                 if($V_mwaka_1_5=='N'){
                                                     echo ' 
                                                         <span id="spanVM_1_5N"><input type="radio" checked="true" name="VM_1_5" id="VM_1_5N">Ndiyo</span>
                                                         <span id="spanVM_1_5H"><input type="radio" name="VM_1_5" id="VM_1_5H">Hapana</span>
                                                            '; 
                                                     
                                                 }elseif ($V_mwaka_1_5=='H') {
                                                     echo ' 
                                                         <span id="spanVM_1_5N"><input type="radio" name="VM_1_5" id="VM_1_5N">Ndiyo</span>
                                                         <span id="spanVM_1_5H"><input type="radio" checked="true" name="VM_1_5" id="VM_1_5H">Hapana</span>
                                                          ';
                                                    
                                                }  else {
                                                    echo ' 
                                                        <span id="spanVM_1_5N"><input type="radio" name="VM_1_5" id="VM_1_5N">Ndiyo</span>
                                                        <span id="spanVM_1_5H"><input type="radio" name="VM_1_5" id="VM_1_5H">Hapana</span>
                                                        ';
                                                    
                                                }
                                                
                                                ?>
                                               
                                            </td>

                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Miezi 9 <br />
                                                <span style="width:80%">
                                                    Uzito/umri
                                                </span>
                                                <br />

                                                <span style="width:80%">
                                                    Uzito/Urefu
                                                </span>
                                                <br />
                                                <span style="width:80%">
                                                    Urefu/umri
                                                </span>
                                            </td>
                                            <td width="25%"> 
                                                <select style="width:200px;" id="uz_um_9">
                                                    <option value="<?php echo $Uz_um_9;?>">
                                                        <?php echo $Uz_um_9;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" id="uz_ur_9">
                                                    <option value="<?php echo $Uz_ur_9;?>">
                                                        <?php echo $Uz_ur_9;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" id="ur_um_9">
                                                    <option value="<?php echo $Ur_um_9;?>">
                                                        <?php echo $Ur_um_9;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Miezi 18 <br />
                                                <span style="width:80%">
                                                    Uzito/umri
                                                </span>
                                                <br />

                                                <span style="width:80%">
                                                    Uzito/Urefu
                                                </span>
                                                <br />
                                                <span style="width:80%">
                                                    Urefu/umri
                                                </span>
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="uz_um_18" >
                                                    <option value="<?php echo $Uz_um_18;?>">
                                                        <?php echo $Uz_um_18;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="uz_ur_18" >
                                                    <option value="<?php echo $Uz_ur_18;?>">
                                                        <?php echo $Uz_ur_18;?>;
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="ur_um_18" >
                                                    <option value="<?php echo $Ur_um_18;?>">
                                                        <?php echo $Ur_um_18;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Miezi 36 <br />
                                                <span style="width:80%">
                                                    Uzito/umri
                                                </span>
                                                <br />

                                                <span style="width:80%">
                                                    Uzito/Urefu
                                                </span>
                                                <br />
                                                <span style="width:80%">
                                                    Urefu/umri
                                                </span>
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="uz_um_36" >
                                                    <option value="<?php echo $Uz_um_36;?>">
                                                        <?php echo $Uz_um_36;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="uz_ur_36" >
                                                    <option value="<?php echo $Uz_ur_36;?>">
                                                        <?php echo $Uz_ur_36;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="ur_um_36" >
                                                    <option value="<?php echo $Ur_um_36;?>">
                                                        <?php echo $Ur_um_36;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">
                                                Miezi 48 <br />
                                                <span style="width:80%">
                                                    Uzito/umri
                                                </span>
                                                <br />

                                                <span style="width:80%">
                                                    Uzito/Urefu
                                                </span>
                                                <br />
                                                <span style="width:80%">
                                                    Urefu/umri
                                                </span>
                                            </td>
                                            <td>
                                                <select style="width:200px;" name="mimbanamba" id="uz_um_48" >
                                                    <option value="<?php echo $Uz_um_48;?>">
                                                        <?php echo $Uz_um_48;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="uz_ur_48" >
                                                    <option value="<?php echo $Uz_ur_48;?>">
                                                        <?php echo $Uz_ur_48;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                                <select style="width:200px;" name="mimbanamba" id="ur_um_48" >
                                                    <option value="<?php echo $Ur_um_48;?>">
                                                        <?php echo $Ur_um_48;?>
                                                    </option>
                                                    <option value="1">
                                                        1=>80% au >-2SD
                                                    </option>
                                                    <option value="2">
                                                        2=80% au -2SD--3SD
                                                    </option>
                                                    <option value="3">
                                                        3=<60% au <-3SD
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>   
                                </td>
                    </tr>
                </table>       
                </td>
                </tr>
                </table>


                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~last but one plzzzzzzzzzzzzzzzzzzzzz~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Mebendazole/Albendazole kila miezi 6</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Hati punguzo chandarua &amp; Ulishaji wa mtoto</td></tr>

                                <td >           
                                    <table width="100%">
                                        <tr>
                                            <td>Miezi 12</td>
                                            <td width="70%"> 
                                                <?php
                                                if($AM_12=='N'){
                                                    echo ' 
                                                        <span id="spanAM_12N"><input type="radio" checked="true" name="AM_12" id="AM_12N">Ndiyo</span>
                                                        <span id="spanAM_12H"><input type="radio" name="AM_12" id="AM_12H">Hapana</span>
                                                        ';
                                                    
                                                }elseif($AM_12=='H'){
                                                    echo ' 
                                                        <span id="spanAM_12N"><input type="radio" name="AM_12" id="AM_12N">Ndiyo</span>
                                                        <span id="spanAM_12H"><input type="radio" checked="true" name="AM_12" id="AM_12H">Hapana</span>
                                                        '; 
                                                    
                                                }  else {
                                                    echo ' 
                                                        <span id="spanAM_12N"><input type="radio" name="AM_12" id="AM_12N">Ndiyo</span>
                                                         <span id="spanAM_12H"><input type="radio" name="AM_12" id="AM_12H">Hapana</span>
                                                       '; 
                                                }
                                                
                                                ?>
                                                 </td>
                                        </tr>
                                        <tr>
                                            <td>Miezi 18</td>
                                            <td width="70%"> 
                                                <?php
                                                if($AM_18=='N'){
                                                    echo '
                                                        <span id="spanAM_18N"><input type="radio" checked="true" id="AM_18N" name="AM_18">Ndiyo</span>
                                                        <span id="spanAM_18H"><input type="radio" id="AM_18H" name="AM_18">Hapana</span>
                                                         '; 
                                                }elseif($AM_18=='H'){
                                                    echo '
                                                        <span id="spanAM_18N"><input type="radio" id="AM_18N" name="AM_18">Ndiyo</span>
                                                        <span id="spanAM_18H"><input type="radio" checked="true" id="AM_18H" name="AM_18">Hapana</span>
                                                         ';  
                                                    
                                                }else{
                                                    
                                                    echo '
                                                        <span id="spanAM_18N"><input type="radio" id="AM_18N" name="AM_18">Ndiyo</span>
                                                        <span id="spanAM_18H"><input type="radio" id="AM_18H" name="AM_18">Hapana</span>
                                                      ';  
                                                    
                                                }
                                                
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Miezi 24</td>
                                            <td width="70%"> 
                                                <?php
                                                if($AM_24=='N'){
                                                     echo ' 
                                                        <span class="pointer" id="spanAM_24N"><input type="radio" checked="true" id="AM_24N" name="AM_24">Ndiyo</span>
                                                        <span class="pointer" id="spanAM_24H"><input type="radio" id="AM_24H" name="AM_24">Hapana</span>
                                                            ';
                                                }elseif ($AM_24=='H') {
                                                    echo ' 
                                                        <span class="pointer" id="spanAM_24N"><input type="radio" id="AM_24N" name="AM_24">Ndiyo</span>
                                                        <span class="pointer" id="spanAM_24H"><input type="radio" checked="true" id="AM_24H" name="AM_24">Hapana</span>
                                                            ';
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanAM_24N"><input type="radio" id="AM_24N" name="AM_24">Ndiyo</span>
                                                        <span class="pointer" id="spanAM_24H"><input type="radio" id="AM_24H" name="AM_24">Hapana</span>
                                                        ';  
                                                    
                                                }
                                                
                                                
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Miezi 30</td>
                                            <td width="70%"> 
                                                <?php
                                                 if($AM_30=='N'){
                                                     echo ' 
                                                         <span class="pointer" id="spanAM_30N"><input type="radio" checked="true" id="AM_30N" name="AM_30">Ndiyo</span>
                                                         <span class="pointer" id="spanAM_30H"><input type="radio" id="AM_30H" name="AM_30">Hapana</span>
                                                            ';
                                                     
                                                     
                                                 }elseif ($AM_30=='H') {
                                                     echo ' 
                                                         <span class="pointer" id="spanAM_30N"><input type="radio" id="AM_30N" name="AM_30">Ndiyo</span>
                                                         <span class="pointer" id="spanAM_30H"><input type="radio" checked="true" id="AM_30H" name="AM_30">Hapana</span>
                                                           ';
                                                }  else {
                                                    echo ' 
                                                        <span class="pointer" id="spanAM_30N"><input type="radio" id="AM_30N" name="AM_30">Ndiyo</span>
                                                        <span class="pointer" id="spanAM_30H"><input type="radio" id="AM_30H" name="AM_30">Hapana</span>
                                                        ';
                                                    
                                                }
                                                
                                                
                                                ?>
                                              
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td>Hati punguzo/Chandarua</td>
                                            <td width="70%"> 
                                                <?php
                                                if($Hati_punguzo=='N'){
                                                    echo ' 
                                                        <span class="pointer" id="spanhatipunguzoN"><input type="radio" checked="true" id="hatipunguzoN" name="hatipunguzo">Ndiyo</span>
                                                       <span class="pointer" id="spanhatipunguzoH"><input type="radio" id="hatipunguzoH" name="hatipunguzo">Hapana</span>
                                                       ';
                                                    
                                                }elseif ($Hati_punguzo=='H') {
                                                    echo ' 
                                                        <span class="pointer" id="spanhatipunguzoN"><input type="radio" id="hatipunguzoN" name="hatipunguzo">Ndiyo</span>
                                                       <span class="pointer" id="spanhatipunguzoH"><input type="radio" checked="true" id="hatipunguzoH" name="hatipunguzo">Hapana</span>
                                                       ';
                                                }else{
                                                    echo ' 
                                                        <span class="pointer" id="spanhatipunguzoN"><input type="radio" id="hatipunguzoN" name="hatipunguzo">Ndiyo</span>
                                                       <span class="pointer" id="spanhatipunguzoH"><input type="radio" id="hatipunguzoH" name="hatipunguzo">Hapana</span>
                                                       '; 
                                                    
                                                }
                                                
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Maziwa ya mama pekee</td>
                                            <td width="70%"> 
                                                <?php
                                                if($Mama_maziwa=='N'){
                                                    echo '  
                                                        <span class="pointer" id="spanmaziwa_pekeeN"><input type="radio" checked="true" id="maziwa_pekeeN" name="maziwa_pekee">Ndiyo</span>
                                                          <span class="pointer" id="spanmaziwa_pekeeH"><input type="radio" id="maziwa_pekeeH" name="maziwa_pekee">Hapana</span>
                                                          ';
                                                    
                                                }elseif ($Mama_maziwa=='H') {
                                                    echo ' 
                                                        <span class="pointer" id="spanmaziwa_pekeeN"><input type="radio" id="maziwa_pekeeN" name="maziwa_pekee">Ndiyo</span>
                                                        <span class="pointer" id="spanmaziwa_pekeeH"><input type="radio" checked="true" id="maziwa_pekeeH" name="maziwa_pekee">Hapana</span>
                                                        ';
                                               
                                                }  else {
                                                  
                                                    echo '
                                                        <span class="pointer" id="spanmaziwa_pekeeN"><input type="radio" id="maziwa_pekeeN" name="maziwa_pekee">Ndiyo</span>
                                                        <span class="pointer" id="spanmaziwa_pekeeH"><input type="radio" id="maziwa_pekeeH" name="maziwa_pekee">Hapana</span>
                                                         '; 
                                                    
                                                }
                                                
                                                ?>
                                              
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Maziwa mbadala</td>
                                            <td width="70%"> 
                                                <?php
                                                if($maziwa_mbadala=='N'){
                                                     echo '
                                                        <span class="pointer" id="spanmaziwa_mbadalaN"><input type="radio" checked="true" id="maziwa_mbadalaN" name="maziwa_mbadala">Ndiyo</span>
                                                         <span class="pointer" id="spanmaziwa_mbadalaH"><input type="radio" id="maziwa_mbadalaH" name="maziwa_mbadala">Hapana</span>
                                                        ';
                                                    
                                                }elseif ($maziwa_mbadala=='H') {
                                                     echo '
                                                        <span class="pointer" id="spanmaziwa_mbadalaN"><input type="radio" id="maziwa_mbadalaN" name="maziwa_mbadala">Ndiyo</span>
                                                         <span class="pointer" id="spanmaziwa_mbadalaH"><input type="radio" checked="true" id="maziwa_mbadalaH" name="maziwa_mbadala">Hapana</span>
                                                        ';
                                                    
                                                }else{
                                                    echo '
                                                        <span class="pointer" id="spanmaziwa_mbadalaN"><input type="radio" id="maziwa_mbadalaN" name="maziwa_mbadala">Ndiyo</span>
                                                         <span class="pointer" id="spanmaziwa_mbadalaH"><input type="radio" id="maziwa_mbadalaH" name="maziwa_mbadala">Hapana</span>
                                                        ';  
                                                    
                                                }
                                                
                                                ?>

                                               

                                            </td>
                                        </tr>

                                    </table>   

                                </td>
                    </tr>
                </table>       
                </td>
                </tr>
                </table>

                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~final table starts here plzzzzzzzzzzzzzzzzzzzzz~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <table align="left" style="width:100%">	   					
                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Rufaa</td> <td style="font-weight:bold; background-color:#006400;color:white" width="62%">Maelezo mengineyo/maoni</td></tr>

                                   <td >           
                                    <table width="100%">
                                        <tr>
                                            <td>Andika kituo alikotoka mtoto</td>
                                            <td width="70%"> 
                                                <input type="text" id="alikotoka" style="width:100%" value="<?php echo $kituo_alikotoka;?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kituo alikopelekwa</td>
                                            <td width="70%"> 
                                                <input type="text" id="alikopelekwa" style="width:100%" value="<?php echo $alikopelekwa;?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Sababu ya rufaa</td>
                                            <td width="70%"> 
                                                <input type="text" id="rufaasababu" style="width:100%" value="<?php echo $Rufaa_sababu;?>">
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>

                                            <td width="100%"> 
                                                <textarea style="width:100%;height:90px;text-align:left" id="maoni">
                                                    <?php echo $maoni;?>
                                                </textarea>

                                            </td>
                                        </tr>

                                    </table>   

                                </td>
                    </tr>
                </table>       
                </td>
                </tr>
                </table>

                <table align="left" style="width:100%">
                    <tr>
                        <td>
                    <center> 
                        <input type="button" value="Save" id="save_data" class="art-button-green" style="width:200px"> 
                    </center>
                    <input type="hidden" id="patient_ID" value="<?php echo $_GET['pn']; ?>">
                    </td>
                    </tr>
                </table>
        </div>

    </div>
</fieldset>

<?php
include("./includes/footer.php");
?>

<link href="css/jquery-ui.css" rel="stylesheet">
<style>
    #spanAM_18H:hover,#spanAM_18N:hover,#spanAM_12H:hover,#spanAM_12N:hover,#spanVM_1_5H:hover,#spanVM_1_5N:hover,#spanVM_U_mwakaN:hover,#spanVM_U_mwakaH:hover,#spanVM_6H:hover,#spanVM_6N:hover,#spanTT2U:hover,#spanTT2N:hover,#spanTT2H:hover,#spanjinsike:hover,#spanjinsime:hover{
    cursor:pointer;
    }
    .pointer{
    cursor:pointer; 
    }

</style>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(".tabcontents").tabs();
    $('#birth_date,#penta_1,#penta_2,#penta_3,#BCG,#OPVO,#polio_1,#polio_2,#polio_3,#PCV_1,#PCV_2,#PCV_3,#Rota_1,#Rota_2,#Rota_3,#surua_1,#surua_2').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#spanjinsike').on('click', function () {
        $('#jinsike').prop('checked', true);
    });
    $('#spanjinsime').on('click', function () {
        $('#jinsime').prop('checked', true);
    });

    $('#spanTT2H').on('click', function () {
        $('#tt2H').prop('checked', true);
    });

    $('#spanTT2N').on('click', function () {
        $('#tt2N').prop('checked', true);
    });
    $('#spanTT2U').on('click', function () {
        $('#tt2U').prop('checked', true);
    });

    $('#spanVM_6N').on('click', function () {
        $('#VM_6N').prop('checked', true);
    });
    $('#spanVM_6H').on('click', function () {
        $('#VM_6H').prop('checked', true);
    });

    $('#spanVM_U_mwakaH').on('click', function () {
        $('#VM_U_mwakaH').prop('checked', true);
    });

    $('#spanVM_U_mwakaN').on('click', function () {
        $('#VM_U_mwakaN').prop('checked', true);
    });

    $('#spanVM_1_5N').on('click', function () {
        $('#VM_1_5N').prop('checked', true);
    });
    $('#spanVM_1_5H').on('click', function () {
        $('#VM_1_5H').prop('checked', true);
    });

    $('#spanAM_12N').on('click', function () {
        $('#AM_12N').prop('checked', true);
    });
    $('#spanAM_12H').on('click', function () {
        $('#AM_12H').prop('checked', true);
    });

    $('#spanAM_18N').on('click', function () {
        $('#AM_18N').prop('checked', true);
    });

    $('#spanAM_18H').on('click', function () {
        $('#AM_18H').prop('checked', true);
    });

    $('#spanAM_24N').on('click', function () {
        $('#AM_24N').prop('checked', true);
    });

    $('#spanAM_24H').on('click', function () {
        $('#AM_24H').prop('checked', true);
    });

    $('#spanAM_30N').on('click', function () {
        $('#AM_30N').prop('checked', true);
    });

    $('#spanAM_30H').on('click', function () {
        $('#AM_30H').prop('checked', true);
    });
    
    $('#spanhatipunguzoN').on('click', function () {
        $('#hatipunguzoN').prop('checked', true);
    });

    $('#spanhatipunguzoH').on('click', function () {
        $('#hatipunguzoH').prop('checked', true);
    });

    $('#spanmaziwa_pekeeN').on('click', function () {
        $('#maziwa_pekeeN').prop('checked', true);
    });

    $('#spanmaziwa_pekeeH').on('click', function () {
        $('#maziwa_pekeeH').prop('checked', true);
    });


    $('#spanmaziwa_mbadalaN').on('click', function () {
        $('#maziwa_mbadalaN').prop('checked', true);
    });

    $('#spanmaziwa_mbadalaH').on('click', function () {
        $('#maziwa_mbadalaH').prop('checked', true);
    });

    $('#save_data').click(function () {
        var patient_ID=$('#patient_ID').val();
        var utambulisho_No = $('#utambulisho_No').val();
        var birth_reg_No = $('#birth_reg_No').val();
        var mtoto_Jina = $('#mtoto_Jina').val();
        var birth_date = $('#birth_date').val();
        var kijiji_jina = $('#kijiji_jina').val();
        var mother_name = $('#mother_name').val();
        var jinsi;
        var TT2_kinga;
        var VM_6;
        var VM_U_mwaka;
        var VM_1_5;
        var AM_12;
        var AM_18;
        var AM_24;
        var AM_30;
        var hatipunguzo;
        var maziwa_pekee;
        var maziwa_mbadala;
        if ($('#jinsike').is(':checked')) {
            jinsi = 'KE';
        } else if ($('#jinsime').is(':checked')) {
            jinsi = 'ME';
        }
        
        if ($('#tt2H').is(':checked')) {
            TT2_kinga = 'H';
        } else if ($('#tt2N').is(':checked')) {
            TT2_kinga = 'N';
        } else if ($('#tt2U').is(':checked')) {
            TT2_kinga = 'U';
        } 
        if ($('#VM_6N').is(':checked')) {
            VM_6 = 'N';
        } else if ($('#VM_6H').is(':checked')) {
            VM_6 = 'H';
        }

        if ($('#VM_U_mwakaN').is(':checked')) {
            VM_6 = 'N';
        } else if ($('#VM_U_mwakaH').is(':checked')) {
            VM_6 = 'H';
        }

        if ($('#VM_1_5N').is(':checked')) {
            VM_1_5 = 'N';
        } else if ($('#VM_1_5H').is(':checked')) {
            VM_1_5 = 'H';
        }

        if ($('#AM_12N').is(':checked')) {
            AM_12 = 'N';
        } else if ($('#AM_12H').is(':checked')) {
            AM_12 = 'H';
        } 

        if ($('#AM_18N').is(':checked')) {
            AM_18 = 'N';
        } else if ($('#AM_18H').is(':checked')) {
            AM_18 = 'H';
        } 

        if ($('#AM_24N').is(':checked')) {
            AM_24 = 'N';
        } else if ($('#AM_24H').is(':checked')) {
            AM_24 = 'H';
        } 

        if ($('#AM_30N').is(':checked')) {
            AM_30 = 'N';
        } else if ($('#AM_30H').is(':checked')) {
            AM_30 = 'H';
        } 

        if ($('#hatipunguzoN').is(':checked')) {
            hatipunguzo = 'N';
        } else if ($('#hatipunguzoH').is(':checked')) {
            hatipunguzo = 'H';
        } 


        if ($('#maziwa_pekeeN').is(':checked')) {
            maziwa_pekee = 'N';
        } else if ($('#maziwa_pekeeH').is(':checked')) {
            maziwa_pekee = 'H';
        } 


        if ($('#maziwa_mbadalaN').is(':checked')) {
            maziwa_mbadala = 'N';
        } else if ($('#maziwa_mbadalaH').is(':checked')) {
            maziwa_mbadala = 'H';
        } 

        var VVU_hali = $('#VVU_hali').val();
        var heid_No = $('#heid_No').val();
        var BCG = $('#BCG').val();
        var OPVO = $('#OPVO').val();
        var penta_1 = $('#penta_1').val();
        var penta_2 = $('#penta_2').val();
        var penta_3 = $('#penta_3').val();
        var polio_1 = $('#polio_1').val();
        var polio_2 = $('#polio_2').val();
        var polio_3 = $('#polio_3').val();
        var PCV_1 = $('#PCV_1').val();
        var PCV_2 = $('#PCV_2').val();
        var PCV_3 = $('#PCV_3').val();
        var Rota_1 = $('#Rota_1').val();
        var Rota_2 = $('#Rota_2').val();
        var Rota_3 = $('#Rota_3').val();
        var surua_1 = $('#surua_1').val();
        var surua_2 = $('#surua_2').val();
        var uz_um_9 = $('#uz_um_9').val();
        var uz_ur_9 = $('#uz_ur_9').val();
        var ur_um_9 = $('#ur_um_9').val();
        var uz_um_18 = $('#uz_um_18').val();
        var uz_ur_18 = $('#uz_ur_18').val();
        var ur_um_18 = $('#ur_um_18').val();
        var uz_um_36 = $('#uz_um_36').val();
        var uz_ur_36 = $('#uz_ur_36').val();
        var ur_um_36 = $('#ur_um_36').val();
        var uz_um_48 = $('#uz_um_48').val();
        var uz_ur_48 = $('#uz_ur_48').val();
        var ur_um_48 = $('#ur_um_48').val();

        var alikotoka = $('#alikotoka').val();
        var alikopelekwa = $('#alikopelekwa').val();
        var rufaasababu = $('#rufaasababu').val();
        var maoni = $('#maoni').val();
        $.ajax({
            type: 'POST',
            url: 'requests/save_watoto_edit.php',
            data: 'action=save&utambulisho_No=' + utambulisho_No + '&birth_reg_No=' + birth_reg_No + '&mtoto_Jina=' + mtoto_Jina + '&birth_date=' + birth_date + '&kijiji_jina=' + kijiji_jina
                    + '&jinsi=' + jinsi + '&mother_name=' + mother_name + '&TT2_kinga=' + TT2_kinga + '&VVU_hali=' + VVU_hali + '&heid_No=' + heid_No + '&BCG=' + BCG + '&OPVO=' + OPVO + '&penta_1=' + penta_1 + '&penta_2=' + penta_2 + '&penta_3=' + penta_3
                    + '&polio_1=' + polio_1 + '&polio_2=' + polio_2 + '&polio_3=' + polio_3 + '&PCV_1=' + PCV_1 + '&PCV_2=' + PCV_2 + '&PCV_3=' + PCV_3 + '&Rota_1=' + Rota_1 + '&Rota_2=' + Rota_2 + '&Rota_3=' + Rota_3
                    + '&surua_1=' + surua_1 + '&surua_2=' + surua_2 + '&VM_6=' + VM_6 + '&VM_U_mwaka=' + VM_U_mwaka + '&VM_1_5=' + VM_1_5 + '&uz_um_9=' + uz_um_9 + '&uz_ur_9=' + uz_ur_9 + '&ur_um_9=' + ur_um_9 + '&uz_um_18=' + uz_um_18
                    + '&uz_ur_18=' + uz_ur_18 + '&ur_um_18=' + ur_um_18 + '&uz_um_36=' + uz_um_36 + '&uz_ur_36=' + uz_ur_36 + '&ur_um_36=' + ur_um_36 + '&uz_um_48=' + uz_um_48 + '&uz_ur_48=' + uz_ur_48 + '&ur_um_48=' + ur_um_48
                    + '&AM_12=' + AM_12 + '&AM_18=' + AM_18 + '&AM_24=' + AM_24 + '&AM_30=' + AM_30 + '&hatipunguzo=' + hatipunguzo + '&maziwa_pekee=' + maziwa_pekee + '&maziwa_mbadala=' + maziwa_mbadala + '&alikotoka=' + alikotoka
                    + '&alikopelekwa=' + alikopelekwa + '&rufaasababu=' + rufaasababu + '&maoni=' + maoni+'&patient_ID='+patient_ID,
            cache: false,
            success: function (html) {
                alert(html);
            }
        });
    });
</script>