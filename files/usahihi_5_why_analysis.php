<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */             
                    .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:1px solid black;
                    padding: 5px;
                    font-size: 14PX;
                }
</style>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/requisition.php");

    //get employee name
    
        if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }
    
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }
    
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Sub_Department_Name = $row['Sub_Department_Name'];
    } else {
        $Sub_Department_Name = '';
    }
}


    

    if (!isset($_SESSION['userinfo'])) {
        session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    /*$Requisition_ID = '';
    if (isset($_SESSION['Requisition_ID'])) {
        $Requisition_ID = $_SESSION['Requisition_ID'];
    }
     */

    /*if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }
*/
    $Requisition = array();
    if (!empty($Requisition_ID)) {
        $Requisition = Get_Requisition($Requisition_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }

$engineering
?>
        <a href='usahihi_5_why_analysis_list.php?section=Engineering_Works=Engineering_WorksThisPageisPage' class='art-button-green'>
            BACK
        </a>

        <style>
        
        
        .procure {
            display: none;
        }

        .spare {
            display: none;
            border: 1px white black;
        }

        .spare table tr th {
            background: gray;
            border: 1px solid #fff;
        }
        .spare table tr:nth-child(even){
            background-color: #eee;
        }
        .spare table tr:nth-child(odd){
            background-color: #fff;
        }
        </style>

<br/><br/>
<center>
<table width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>MCHANGANUO WA 5-WHY (5 WHY ANALYSIS)</b></legend>
        <form action='' method='POST' name='' id='myForm' >
            <table   id="spu_lgn_tbl" width=100%>
                <?php
              
               //get details from tbl_enginnering_requisition
                                $get_details = mysqli_query($conn,"select * FROM tbl_engineering_requisition
											where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($get_details);
                                if ($no > 0) {
                                    //$Process_Status = 'processed';
                                    while ($data2 = mysqli_fetch_array($get_details)) {
                                        //$requisition_ID = $data2['requisition_ID'];
                                        $Department_ID = $data2['select_dept'];
                                        $employee = $data2['employee_name'];
                                        $title = $data2['title'];
                                        $floor = $data2['floor'];
                                        $requisition_date = $data2['date_of_requisition'];
                                        $equipment_name = $data2['equipment_name'];
                                        $equipment_ID = $data2['equipment_ID'];
                                        $equipment_serial=$data2['equipment_serial'];
                                        $equipment_code=$data2['equipment_code'];
                                        $description_works_to_done = $data2['description_works_to_done'];
                                        $assigned_engineer = $data2['assigned_engineer'];
                                        $assistance_engineer = $data2['assistance_engineer'];
                                        $type_of_work = $data2['type_of_work'];
                                        $section_required = $data2['section_required'];

                                        $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Department_Name FROM tbl_department WHERE Department_ID='$Department_ID'"))['Department_Name'];

                                    $requested = mysqli_fetch_assoc(mysqli_query($conn,"Select Employee_Name from tbl_employee where Employee_ID = '$employee'"))['Employee_Name'];
                                }}
                                
                                $Get_5_why_analysis = mysqli_query($conn,"SELECT an.Requisition_ID, an.Employee_ID, an.Created_at, an.Mzizi_wa_tatizo, an.hatua_za_mchakato, an.washiriki, an.kwanini1, an.kwanini2, an.kwanini3, an.kwanini4, an.kwanini5, an.kwanini6, an.kwanini7, an.kwanini8, an.kwanini9, an.kwanini, an.ziada, an.ufuatiliaji, an.maoni, an.vitu_zaidi, an.kiini, an.umejiridhisha1, an.umejiridhisha2, an.umejiridhisha3, an.umejiridhisha4, an.umejiridhisha5, em.Employee_Name FROM tbl_5_why_analysis an, tbl_employee em WHERE an.Requisition_ID ='$Requisition_ID' AND em.Employee_ID = an.Employee_ID") or die(mysqli_error($conn));
                                
                                $details = mysqli_num_rows($Get_5_why_analysis);
                                if ($details > 0) {
                                    while($taarifa = mysqli_fetch_array($Get_5_why_analysis)) {
                                        $aliyejaza = $taarifa['Employee_Name'];
                                        $Created_at = $taarifa['Created_at'];
                                        $Mzizi_wa_tatizo = $taarifa['Mzizi_wa_tatizo'];
                                        $hatua_za_mchakato = $taarifa['hatua_za_mchakato'];
                                        $washiriki = $taarifa['washiriki'];
                                        $kwanini1 = $taarifa['kwanini1'];
                                        $kwanini2 = $taarifa['kwanini2'];
                                        $kwanini3 = $taarifa['kwanini3'];
                                        $kwanini4 = $taarifa['kwanini4'];
                                        $kwanini5 = $taarifa['kwanini5'];
                                        $kwanini6 = $taarifa['kwanini6'];
                                        $kwanini7 = $taarifa['kwanini7'];
                                        $kwanini8 = $taarifa['kwanini8'];
                                        $kwanini9 = $taarifa['kwanini9'];
                                        $kwanini = $taarifa['kwanini'];
                                        $ziada = $taarifa['ziada'];
                                        $ufuatiliaji = $taarifa['ufuatiliaji'];
                                        $maoni = $taarifa['maoni'];
                                        $vitu_zaidi = $taarifa['vitu_zaidi'];
                                        $kiini = $taarifa['kiini'];
                                        $umejiridhisha1 = $taarifa['umejiridhisha1'];
                                        $umejiridhisha2 = $taarifa['umejiridhisha2'];
                                        $umejiridhisha3 = $taarifa['umejiridhisha3'];
                                        $umejiridhisha4 = $taarifa['umejiridhisha4'];
                                        $umejiridhisha5 = $taarifa['umejiridhisha5'];
                                    }
                                }
                                    ?>
            <tr>
                <td style='text-align: right; width:15%;'>Ombi Namba:</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly' name='Requisition_ID' id='Requisition_ID' value='{$Requisition_ID}'";
                    ?>
                </td>
            <td style='text-align: right; width:15%;'>Idara ya Muombaji:</td>
		    <td width='10%' style>
                    <?php
                        echo "<input type='text' value='{$department}'>"
                    ?>
                </td>
                <td width='20%' style='text-align: right;'>Tarehe(Date)</td>
                <td width='30%'>
                    <?php
                            echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' value='{$requisition_date}'>";
                    ?>
                </td> 
                
                </tr>
                <tr>
                 <td style='text-align: right; width:15%;'>Jina la Aliyetoa Taarifa:</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly' name='reporter' value='{$requested}'>";
                    
                    ?>
                </td>
                    
                        <td style='text-align: right; width:15%;'>Cheo Chake:</td>
                        <td>
                            <?php
                                   echo "<input type='text' readonly='readonly'  name='employee' value='{$title}'>";                    
                     ?>
                        </td>
                    <td style='text-align: right; width:15%;'>Eneo/Kitengo <br>Area:</td>
                        <td>
                           <?php
                                   echo "<input type='text' readonly='readonly'  name='floor' value='{$floor}'>";                    
                     ?>
                        </td>
                </tr>
                <td style='text-align: right; width:15%;'>Mtambo/Kifaa<br>Mashine:</td>
                        <td colspan='2'>
                               <?php
                                   echo "<input type='text' readonly='readonly'  name='equipment' value='$equipment_name'>";                    
                     ?>
                        </td>
                        <td style='text-align: right;'>Kitengo(Section):</td>
                               <td colspan="2">
                               <?php
                                   echo "<input type='text' readonly='readonly'  name='section_required' value='$section_required'>";                    
                     ?>
                    
                    
                    
                        </select>
                        </td>
                    </tr>
                
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>Taarifa za Kiashiria Cha Tatizo</b></legend></>
                            </tr>
                            <tr>
                                <td style='text-align: right; width:15%;'>Tatizo ni nini hasa? Kiashiria Kilichoonekana: </td>
                                <td width="100%" height="20%" colspan='5'>
                                    <textarea name='Mzizi_wa_tatizo' readonly='readonly'><?php echo $Mzizi_wa_tatizo ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>Hatua Zilizochukuliwa ili kuwezesha Mchakato, au Mashine Kuendelea kufanya kazi</b></legend></>
                            </tr>
                            <tr>
                            
                            </tr>
                                <td style='text-align: right; width:15%;'>Hatua Stahiki zilizochukuliwa: </td>
                                <td width="100%" height="20%" colspan='5'>
                                    <textarea name='hatua_za_mchakato' readonly='readonly'><?php echo $hatua_za_mchakato ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>Mchanganuo wa Kutafuta Kiini/Mzizi wa Tatizo</b></legend></>
                            </tr>
                            <tr>
                                <td style='text-align: right; width:15%;'>Washiriki:</td>
                                <td width="100%" height="20%" colspan='5'>
                                    <textarea name='washiriki' readonly='readonly'><?php echo $washiriki ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4'style='text-align: center;'>Jibu Kwa nini</td>
                                <td colspan='2'style='text-align: center;'>Ushahidi: Unaonaje kama kweli hili ndio jibu sahihi? <br>Umejiridhishaje?</td>
                            </tr>
                            <tr>
                                <td rowspan='2' style='text-align: center;' width='5%'>1.</td>
                                <td rowspan='2' style='text-align: center;'>Kwa nini?</td>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini1}'>"
                                    ?>
                                </td>
                                <td colspan='2' rowspan='2' readonly='readonly'><textarea name="umejiridhisha1" id="" cols="30" rows="3"><?php echo $umejiridhisha1 ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini2}'>"
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan='2' style='text-align: center;' width='5%'>2.</td>
                                <td rowspan='2' style='text-align: center;'>Kwa nini?</td>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini3}'>"
                                    ?>
                                </td>
                                <td colspan='2' rowspan='2'>
                                <textarea name="umejiridhisha2" id="" cols="30" rows="3" readonly='readonly'><?php echo $umejiridhisha2 ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini4}'>"
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan='2' style='text-align: center;' width='5%'>3.</td>
                                <td rowspan='2' style='text-align: center;'>Kwa nini?</td>
                                <td colspan='2'>
                                <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini5}'>"
                                    ?>
                                <td colspan='2' rowspan='2'>
                                    <textarea name="umejiridhisha3" id="" cols="30" rows="3" readonly='readonly'><?php echo $umejiridhisha3 ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini6}'>"
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan='2' style='text-align: center;' width='5%'>4.</td>
                                <td rowspan='2' style='text-align: center;'>Kwa nini?</td>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini7}'>"
                                    ?>
                                <td colspan='2' rowspan='2'>
                                    <textarea name="umejiridhisha4" id="" cols="30" rows="3" readonly='readonly'><?php echo $umejiridhisha4 ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini8}'>"
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan='2' style='text-align: center;' width='5%'>5.</td>
                                <td rowspan='2' style='text-align: center;'>Kwa nini?</td>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini9}'>"
                                    ?>
                                </td>
                                <td colspan='2' rowspan='2'>
                                    <textarea name="umejiridhisha5" id="" cols="30" rows="3" readonly='readonly'><?php echo $umejiridhisha5 ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <?php
                                        echo"<input type='text' width='100' readonly='readonly' name='kwanini6' value='{$kwanini}'>"
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>Kukamilisha Mzunguko</b></legend></>
                            </tr>
                            <tr>
                                <td style='text-align: right; font-weight: bold;'>Kunahitajika uchunguzi wa Ziada?<br> (Chagua Ndio au Hapana)</td>
                                <td width="4">
                                    <textarea cols="30" rows="4" readonly="readonly"><?php echo $ziada ?></textarea>
                                </td>
                                <td>
                                    <textarea cols="30" rows="4" readonly="readonly"><?php echo $kiini ?></textarea>
                                </td>
                                <td  style='text-align: right; font-weight: bold;'>Kiini cha Tatizo kimepatikana, lakini kuna vitu zaidi vya kufanya</td>
                                <td>
                                    <textarea cols="30" rows="4" readonly="readonly"><?php echo $vitu_zaidi ?></textarea>
                                </td>
                                <td>
                                    <textarea cols="30" rows="4" readonly="readonly"><?php echo $ufuatiliaji ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right; font-weight: bold;'>Comments/Actions <br>(If Needed):<br>Maoni au hatua za ziada (Kama zinahitajika):</td>
                                <td width="100%" height="20%" colspan='5'>
                                    <textarea name='maoni' rows='3'readonly='readonly'><?php echo $maoni ?></textarea>
                                </td>
                            </tr>                            
                            <!-- <tr>
                                <td colspan='6' style='text-align: center;'>
                                     <input type='submit' name='submit_form' id='submit_form' value='   SAVE INFORMATIONS   ' class='art-button-green'>
                                </td>
                            </tr>                                -->
</table>
                        </form>
                            </table>
                            </center>



<?php
                                      
$creating_5_why_analysis='';

 
 if(isset($_POST['submit_form'])){
        $Mzizi_wa_tatizo=$_POST['Mzizi_wa_tatizo'];
        $hatua_za_mchakato=$_POST['hatua_za_mchakato'];
        $washiriki=$_POST['washiriki'];
        $kwanini1=$_POST['kwanini1'];
        $kwanini2=$_POST['kwanini2'];
        $kwanini3=$_POST['kwanini3'];
        $kwanini4=$_POST['kwanini4'];
        $kwanini5=$_POST['kwanini5'];
        $kwanini6=$_POST['kwanini6'];
        $kwanini7=$_POST['kwanini7'];
        $kwanini8=$_POST['kwanini8'];
        $kwanini9=$_POST['kwanini9'];
        $kwanini=$_POST['kwanini'];
        $umejiridhisha1=$_POST['umejiridhisha1'];
        $umejiridhisha2=$_POST['umejiridhisha2'];
        $umejiridhisha3=$_POST['umejiridhisha3'];
        $umejiridhisha4=$_POST['umejiridhisha4'];
        $umejiridhisha5=$_POST['umejiridhisha5'];
        $ziada=$_POST['ziada'];
        $vitu_zaidi=$_POST['vitu_zaidi'];
        $kiini=$_POST['kiini'];
        $ufuatiliaji=$_POST['ufuatiliaji'];
        $maoni=$_POST['maoni'];
        

        
     if(!empty($Requisition_ID)){
            $creating_5_why_analysis = mysqli_query($conn,"INSERT INTO tbl_5_why_analysis (Requisition_ID, Employee_ID, Created_at, Mzizi_wa_tatizo, hatua_za_mchakato, washiriki, kwanini1, kwanini2, kwanini3, kwanini4, kwanini5, kwanini6, kwanini7, kwanini8, kwanini9, kwanini, ziada, ufuatiliaji, maoni, vitu_zaidi, kiini, umejiridhisha1, umejiridhisha2, umejiridhisha3, umejiridhisha4, umejiridhisha5) VALUES ('$Requisition_ID', '$Employee_ID', NOW(), '$Mzizi_wa_tatizo', '$hatua_za_mchakato', '$washiriki', '$kwanini1', '$kwanini2', '$kwanini3', '$kwanini4', '$kwanini5', '$kwanini6', '$kwanini7', '$kwanini8', '$kwanini9', '$kwanini', '$ziada', '$ufuatiliaji', '$maoni', '$vitu_zaidi', '$kiini', '$umejiridhisha1', '$umejiridhisha2', '$umejiridhisha3', '$umejiridhisha4', '$umejiridhisha5')");

        
      
     if ($creating_5_why_analysis)
     {  
        echo "<script>
        alert('5-Why Analysis was saved successfully!');
        document.location = './job_assignment_engineering.php?New_Process_Requisition=True&Requisition_ID=".$Requisition_ID."';
               </script>";
    }
     else 
     {
         echo "<script>alert('5 Why Analysis Failed!')</script>";
     }
     }else{
         echo "FAILED";
     }
 }
 mysqli_close($conn);
?>
<br>
<?php
    include("./includes/footer.php");

