<?php 
    include("../includes/connection.php");
    session_start();
    if(isset($_POST['setupreport'])){?>
    <style>
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
    </style>
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <input type='text' placeholder="~~~~~Search procedure Name~~~~~" onkeyup='ajax_search_setup()' id="setup_name" style='text-align:center'>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-4" style='height:400px;overflow-y:scroll' id="background_procedure">
            <table class='table table-bordered' style='background:#FFFFFF'>
                <caption><b>LIST OF ALL PROCEDURE</b></caption>
                <tr>
                    <th>S/No.</th>
                    <th>Procedure NAME</th>
                </tr>
                <tbody id='list_of_all_setup'>
                    <?php
                      
                        $sql_search_procedure=mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE  Consultation_Type IN('Procedure' , 'Radiology') LIMIT 50") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_search_procedure)>0){ 
                            $count_sn=1;
                            while($employee_rows=mysqli_fetch_assoc($sql_search_procedure)){
                                $Item_ID=$employee_rows['Item_ID'];
                                $Product_Name=$employee_rows['Product_Name'];
                                echo "<tr class='rows_list' onclick='save_template_setup($Item_ID)'>
                    
                                        <td>$count_sn</td>
                                        <td>$Product_Name</td>                                        
                                    </tr>";  
                                    $count_sn++;
                            }
                        }else{
                            echo "<tr>
                                <td colspan='2'>No result Found</td>    
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-8" style='height:400px;overflow-y:scroll'>
            <table class='table' style='background:#FFFFFF' >
                <caption><b>LIST OF SELECTED PROCEDURE</b></caption>
                <tr>
                    <th>S/No.</th>
                    <th>PROCEDURE NAME</th>
                    <th>PROTOCAL</th>
                    <th>FINDINGS</th>
                    <th>PROCEDURE</th>
                    <th>FUNCTION</th>
                    <th>PHASE</th>
                </tr>
                <tbody id='list_of_selected_setup'>

                </tbody>
            </table>
        </div>
        <div class="col-md-12" id="send_data">
            <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_procedure_selected()">
        </div>


<?php  }


if(isset($_POST['Product_Name'])){
    $Product_Name=mysqli_real_escape_string($conn,$_POST['Product_Name']);

    $sql_search_procedure=mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name LIKE '%$Product_Name%' AND (Consultation_Type='Procedure' OR Consultation_Type='Radiology') LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_procedure)>0){ 
        $count_sn=1;
        while($employee_rows=mysqli_fetch_assoc($sql_search_procedure)){
            $Item_ID=$employee_rows['Item_ID'];
            $Product_Name=$employee_rows['Product_Name'];
            echo "<tr class='rows_list' onclick='save_template_setup($Item_ID)'>

                    <td>$count_sn</td>
                    <td>$Product_Name</td>
                    
                </tr>";  
                $count_sn++;
        }
    }else{
        echo "<tr>
                <td colspan='2'>No result Found</td>    
            </tr>";
    }
}

if(isset($_POST['save_setup'])){
    $Item_ID = $_POST['Item_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $select_ID = mysqli_query($conn, "SELECT * FROM tbl_template_report_nm WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_ID)>0){
        echo "Item arleady Exit";
    }else{
        $save_item = mysqli_query($conn, "INSERT INTO tbl_template_report_nm(Item_ID, Saved_by) VALUES('$Item_ID', '$Employee_ID')" ) or die(mysqli_error($conn));
        if($save_item){
            echo "saved";
        }else{
            echo "Not saved";
        }
    }
    
}

if(isset($_POST['selected_item'])){
    $select_item = mysqli_query($conn, "SELECT Product_Name,Template_ID, findings, procedure_done, protocal,phase, functions FROM tbl_template_report_nm rnm, tbl_items i WHERE rnm.Item_ID=i.Item_ID") or die(mysqli_error($conn));
    $num=0;
    if(mysqli_num_rows($select_item)>0){
        while($rows = mysqli_fetch_assoc($select_item)){
            $Product_Name = $rows['Product_Name'];
            $findings = $rows['findings'];
            $procedure_done = $rows['procedure_done'];
            $protocal = $rows['protocal'];
            $functions = $rows['functions'];
            $phase = $rows['phase'];
            $Template_ID = $rows['Template_ID'];
            $num++;
            echo "<tr>
                <td>$num</td>
                <td>$Product_Name</td>
                <td>
                    <select onchange='update_template($Template_ID)' id='protocal' >
                        <option value='$protocal'>$protocal</option>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </td>
                <td>
                    <select onchange='update_template($Template_ID)' id='findings' >
                        <option value='$findings'>$findings</option>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </td>
                <td>
                    <select onchange='update_template($Template_ID)' id='procedure_done' >
                        <option value='$procedure_done'>$procedure_done</option>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </td>
                <td>
                    <select onchange='update_template($Template_ID)' id='functions' >
                        <option value='$functions'>$functions</option>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </td>
                <td>
                    <select onchange='update_template($Template_ID)' id='phase' >
                        <option value='$phase'>$phase</option>
                        <option value='No'>No</option>
                        <option value='Yes'>Yes</option>
                    </select>
                </td>
            </tr>";
        }
    }else{
        echo "<tr>
        <td colspan='6'>No result Found</td>    
    </tr>";
    }
}

if(isset($_POST['Remove_item'])){

}

if(isset($_POST['update_item'])){
    $protocal =$_POST['protocal'];
    $findings =$_POST['findings'];
    $procedure_done =$_POST['procedure_done'];
    $functions =$_POST['functions'];
    $phase =$_POST['phase'];
    $Template_ID = $_POST['Template_ID'];

    $update_query = mysqli_query($conn, "UPDATE tbl_template_report_nm SET protocal='$protocal', findings='$findings', procedure_done='$procedure_done', functions='$functions', phase='$phase' WHERE  Template_ID='$Template_ID' " ) or die(mysqli_error($conn));
    if($update_query){
        echo "Updated";
    }else{
        echo "Failed";
    }
}
if(isset($_POST['dialogtherapychecklist'])){
    $Id = $_POST['Id'];
    $Payment_Item_Cache_List_ID =$_POST['Payment_Item_Cache_List_ID'];
    $select_data = mysqli_query($conn, "SELECT * FROM tbl_nm_therapychecklist WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Id'") or die(mysqli_error($conn));

    if(mysqli_num_rows($select_data)>0){
        while($row = mysqli_fetch_assoc($select_data)){
            $therapychecklist = explode(',', $row['therapychecklist']);
            $Therapy = explode(',', $row['Therapy']);
            
            if($therapychecklist[1]=="Yes"){
                $consentform= "checked = 'checked'";
            }else if($therapychecklist[1]=="No"){
                $consentform1= "checked= 'checked'";
            }

            if($therapychecklist[2]=="Yes"){
                $instructionform= "checked = 'checked'";
            }else if($therapychecklist[2]=="No"){
                $instructionform1= "checked= 'checked'";
            }

            if($therapychecklist[3]=="Yes"){
                $precaution= "checked = 'checked'";
            }else if($therapychecklist[3]=="No"){
                $precaution1= "checked= 'checked'";
            }

            if($therapychecklist[4]=="Yes"){
                $latter= "checked = 'checked'";
            }else if($therapychecklist[4]=="No"){
                $latter1= "checked= 'checked'";
            }
            if($therapychecklist[5]=="Yes"){
                $contraception= "checked = 'checked'";
            }else if($therapychecklist[5]=="No"){
                $contraception1= "checked= 'checked'";
            }else if($therapychecklist[5]=="Nill"){
                $contraception11= "checked= 'checked'";
            }
            // die($therapychecklist[6]);
            if($therapychecklist[6]=="POS"){
                $pregnance= "checked = 'checked'";
            }else if($therapychecklist[6]=="NEG"){
                $pregnance1= "checked= 'checked'";
            }else if($therapychecklist[6]=="Nill"){
                $pregnance11= "checked= 'checked'";
            }
            if($therapychecklist[7]=="Yes"){
                $carbimazole= "checked = 'checked'";
            }else if($therapychecklist[7]=="No"){
                $carbimazole1= "checked= 'checked'";
            }
            if($therapychecklist[9]=="Yes"){
                $propranolol= "checked = 'checked'";
            }else if($therapychecklist[9]=="No"){
                $propranolol1= "checked= 'checked'";
            }
            if($therapychecklist[10]=="Yes"){
                $opthalmopath= "checked = 'checked'";
            }else if($therapychecklist[10]=="No"){
                $opthalmopath1= "checked= 'checked'";
            }
            if($therapychecklist[11]=="Yes"){
                $smoking= "checked = 'checked'";
            }else if($therapychecklist[11]=="No"){
                $smoking1= "checked= 'checked'";
            }
            if($therapychecklist[12]=="Yes"){
                $follow= "checked = 'checked'";
            }else if($therapychecklist[12]=="No"){
                $follow1= "checked= 'checked'";
            }
            if($therapychecklist[13]=="Yes"){
                $opthalmologist= "checked = 'checked'";
            }else if($therapychecklist[13]=="No"){
                $opthalmologist1= "checked= 'checked'";
            }

            if($therapychecklist[14]=="Yes"){
                $prednisone= "checked = 'checked'";
            }else if($therapychecklist[14]=="No"){
                $prednisone1= "checked= 'checked'";
            }

            if($therapychecklist[9]=="Varapamil"){
                $prednisone= "checked = 'checked'";
            }
        }
    }
    
    ?>
   <style>
       tr{
           margin:3vh;
           padding:3vh;
       }
   </style>
    <div  style="margin-top:3vh; ">
    <fieldset>
    <legend align="center"><b>RADIO - ACTIVE IODINE THERAPY FORM HYPERTHYROIDISM</b></legend>
   
        <table class="table">
            <caption style="text-align: center;"><h4>DAY OF THERAPY CHECKLIST</h4></caption>
            <tr>
                <td>
                    Consent Form <?php echo $therapychecklist[0]; ?>
                    <span>

                       Yes <input type="radio" <?php echo  $consentform; ?> style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?php echo  $consentform1; ?> style="display:inline;" id="therapychecklist" value="No">
                    </span>
                    
                </td>
                <td>
                    Patient Information and instruction form
                    <span>
                        Yes  <input type="radio" <?= $instructionform ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $instructionform1 ?>  style="display:inline;" id="therapychecklist" value="No">
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    Advise on precautions given:
                    <span>
                        Yes <input type="radio" <?= $precaution ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $precaution1 ?>  style="display:inline;" id="therapychecklist" value="No">
                    </span>
                    
                </td>
                <td>
                    Treatment letter signed
                    <span>
                       Yes <input type="radio" <?= $latter ?> style="display:inline;" id="therapychecklist" value="Yes">
                        No<input type="radio" <?= $latter1 ?> style="display:inline;" id="therapychecklist" value="No">
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td>
                    Contraception:
                    <span>

                       Yes <input type="radio" <?= $contraception ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $contraception1 ?>  style="display:inline;" id="therapychecklist" value="No">
                        N/A <input type="radio" <?= $contraception11 ?>  style="display:inline;" id="therapychecklist" value="Nill">
                    </span>
                    
                </td>
                <td>
                    Pregnancy Test
                    <span>
                        POS  <input type="radio" <?= $pregnance ?>  style="display:inline;" id="therapychecklist" value="POS">
                        NEG <input type="radio" <?= $pregnance1 ?>  style="display:inline;" id="therapychecklist" value="NEG">
                        N/A <input type="radio" <?= $pregnance11 ?>  style="display:inline;" id="therapychecklist" value="Nill">
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Patient stopped Carbimazole:
                    <span>
                       Yes <input type="radio" <?= $carbimazole ?> style="display:inline;" id="therapychecklist" value="Yes">
                        No<input type="radio" <?= $carbimazole1 ?> style="display:inline;" id="therapychecklist" value="No">
                        If YES: Date: <input type="date" name="Therapy[]" value="<?= $Therapy[0]?>" class="form-control" id="therapychecklist"  style="display:inline; width:30%;"  >
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Propranolol for 1 month prescribed:
                    <span>
                        Yes <input type="radio" <?= $propranolol ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $propranolol1 ?>  style="display:inline;" id="therapychecklist" value="No">
                        Dose: <input type="text" name="Therapy[]" style="display:inline; width:15%;" value="<?= $Therapy[1] ?>">mg
                        If asthmatic Varapamil prescribed 40mg daily for 1 month: <input type="checkbox" name="consentform" style="display:inline; " id="therapychecklist" <?=$Varapamil?>>
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Opthalmopathy:
                    <span>
                       Yes <input type="radio" <?= $opthalmopath ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No<input type="radio" <?= $opthalmopath1 ?>  style="display:inline;" id="therapychecklist" value="No">
                        If YES: Controlled by Opthalmologist?:
                        Yes <input type="radio" <?= $opthalmologist ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No<input type="radio" <?= $opthalmologist1 ?>  style="display:inline;" id="therapychecklist" value="No">
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Patient Smoking:
                    <span>
                        Yes <input type="radio" <?= $smoking ?>  style="display:inline;" id="therapychecklist" value="Yes">
                        No <input type="radio" <?= $smoking1 ?>  style="display:inline;" id="therapychecklist" value="No">                        
                        If yes; prednisone given? 
                        Yes<input type="radio" name="prednisone" <?= $prednisone ?> style="display:inline; " id="therapychecklist" value="Yes">
                       No <input type="radio" name="prednisone" <?= $prednisone1 ?> style="display:inline; " id="therapychecklist" value="No">
                        Dose: <input type="text" name="Therapy[]" style="display:inline; width:15%;" value="<?= $Therapy[2] ?>">(0.3mg/kg starting second day after RAI for 4 weeks)
                    </span>
                    
                </td>
            </tr>
            <tr>
                <td>
                    Follow up date given?:
                    <span>
                       Yes <input type="radio" <?= $follow ?> value="Yes" style="display:inline;" id="therapychecklist">
                        No<input type="radio" <?= $follow1 ?> value="No"  style="display:inline;" id="therapychecklist">
                        
                    </span>
                    
                </td>
                <td>
                    Date:
                    <span>
                        <input type="date"  name="Therapy[]" style="display:inline; width:35%" value="<?= $Therapy[3] ?>">                                           

                    </span>
                    
                </td>
            </tr>
            <tr>
                
               
                <td style="text-align: right;" colspan="2">
                <?php if(mysqli_num_rows($select_data)>0){?>
                    <a href="print_therapy_checklist.php?Item_list=<?=$Payment_Item_Cache_List_ID ?>&Id=<?=$Id?>" target="blank" class="art-button-green">PRINT CHECKLIST</a>
                <?php }else{?>
                    <input type="button"  class="art-button-green" value="SAVE" onclick="save_checklist_data()">
                <?php } ?>
                    
                </td>
            </tr>
        </table>
    </div>
    </fieldset>
    <?php

    //}
}

if(isset($_POST['savesummernote'])){
    $summernote = $_POST['summernote'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Registration_ID = $_POST['Registration_ID'];
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $attachment = $_POST['attachment'];
    $sql_save_report = mysqli_query($conn, "INSERT INTO tbl_nuclear_medicine_report(FindsReport,attachment, Registration_ID,Payment_Item_Cache_List_ID, Employee_ID ) VALUES('$summernote','$attachment', '$Registration_ID', '$Payment_Item_Cache_List_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($sql_save_report){
        echo "Data saved successful";
    }else{
        echo "Failed please try again..";
    }
}
if(isset($_POST['assmentnsaveform'])){
    $clinicalhistory = mysqli_real_escape_string($conn, $_POST['clinicalhistory']);
    $Payment_Item_Cache_List_ID = mysqli_real_escape_string($conn, $_POST['Payment_Item_Cache_List_ID']);
    $therapychecklist = mysqli_real_escape_string($conn, $_POST['therapychecklist']);
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $insertquery = mysqli_query($conn, "INSERT INTO tbl_nm_assessmentform (clinicalhistory,therapychecklist, Registration_ID, Payment_Item_Cache_List_ID, Employee_ID)VALUES('$clinicalhistory','$therapychecklist','$Registration_ID','$Payment_Item_Cache_List_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($insertquery){
        echo "Saved successful";
    }else{
        echo "Failed";
    }


}

if(isset($_POST['therapychecklistsave'])){
    
    $therapychecklist = mysqli_real_escape_string($conn, $_POST['therapychecklist']);
    // die($therapychecklist);
    $Payment_Item_Cache_List_ID = mysqli_real_escape_string($conn, $_POST['Payment_Item_Cache_List_ID']);
    $Therapy = mysqli_real_escape_string($conn, $_POST['Therapy']);
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $insertquery = mysqli_query($conn, "INSERT INTO tbl_nm_therapychecklist (therapychecklist,Therapy, Registration_ID, Payment_Item_Cache_List_ID, Employee_ID)VALUES('$therapychecklist','$Therapy','$Registration_ID','$Payment_Item_Cache_List_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($insertquery){
        echo "Saved successful";
    }else{
        echo "Failed";
    }
}


if(isset($_POST['savemanagementplan'])){
    $managementplan = mysqli_real_escape_string($conn, $_POST['managementplan']);
    $Payment_Item_Cache_List_ID = mysqli_real_escape_string($conn, $_POST['Payment_Item_Cache_List_ID']);
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Assessment_ID =$_POST['Assessment_ID'];
    $insertquery = mysqli_query($conn, "INSERT INTO tbl_nm_managementplan (managementplan,Assessment_ID, Registration_ID, Payment_Item_Cache_List_ID, Employee_ID)VALUES('$managementplan','$Assessment_ID','$Registration_ID','$Payment_Item_Cache_List_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($insertquery){
        echo "Saved successful";
    }else{
        echo "Failed";
    }
}
if(isset($_POST['followupform'])){
    ?>
    <fieldset>
    <legend align="center"><b>RAI FOLLOW UP FORM</b></legend>
        <table class="table">
            <thead>
                <tr><th width='5%'>SN</th>
                    <th>Date</th>
                    
                    <th>T₃</th>
                    <th>T₄</th>
                    <th>THS</th>
                    <th>⁹⁹ᵐTCO₄ uptake(0.75-4%)</th>
                    <th>BP</th>
                    <th>Pulse</th>
                    <th>Weight (Kg)</th>
                    <th>Reflexes</th>
                    <th>Medication</th>
                    <th>Plan</th>
                    <th>Visit</th>
                </tr>
            </thead>
            <tbody id="followuptreatment">
              
            </tbody>
        </table>
    </fieldset>
    
<?php
}

if(isset($_POST['followupttreatmentsave'])){
    $followuptreatment = mysqli_real_escape_string($conn, $_POST['followuptreatment']);
    
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Assessment_ID =$_POST['Assessment_ID'];
    $insertquery = mysqli_query($conn, "INSERT INTO tbl_nm_followuptreatment (followuptreatment,Assessment_ID,  Employee_ID)VALUES('$followuptreatment','$Assessment_ID', '$Employee_ID')") or die(mysqli_error($conn));
    if($insertquery){
        echo "Saved successful";
    }else{
        echo "Failed";
    }
}
if(isset($_POST['display_riotfollowup'])){
    $Assessment_ID = $_POST['Assessment_ID'];
    $sn=1;
    $selectfllowup = mysqli_query($conn, "SELECT * FROM tbl_nm_followuptreatment WHERE Assessment_ID='$Assessment_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($selectfllowup)){
        $followuptreatment = explode(',', $row['followuptreatment']);
        $created_at = $row['created_at'];
        $Assessment_ID = $row['Assessment_ID'];
        echo "<tr>
        <td>$sn</td>
        <td>$created_at</td>
        <td>".$followuptreatment[0]."</td>
        <td>".$followuptreatment[1]."</td>
        <td>".$followuptreatment[2]."</td>
        <td>".$followuptreatment[3]."</td>
        <td>".$followuptreatment[4]."</td>
        <td>".$followuptreatment[5]."</td>
        <td>".$followuptreatment[6]."</td>
        <td>".$followuptreatment[7]."</td>
        <td>".$followuptreatment[8]."</td>
        <td>".$followuptreatment[9]."</td>
        <td>".$followuptreatment[10]."</td>
    </tr>

    ";
    $sn++;
    }
    echo '
    <tr>
    <td><a href="nuclear_medicinefollowupprint.php?Assessment_ID='.$Assessment_ID.' " class="art-button-green" target="blank">PRINT</a></td>
    <td><input type="button" value="SAVE" onclick="savefollowuptreatment()" class="art-button-green"></td>
    <td> <input type="text" placeholder="T₃" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="T₄" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="THS" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="⁹⁹ᵐTCO₄ uptake" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="BP" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="Pulse" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="Weight" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="Reflexes" name="followuptreatment[]" style="width:100%;">  </td>
    <td> <input type="text" placeholder="Medication" name="followuptreatment[]" style="width:100%;">  </td>
    <td colspan=""> <textarea rows="1"  placeholder="Plan" name="followuptreatment[]" style="width:100%;"> </textarea> </td>
    <td> <input type="date" placeholder="Next visit" name="followuptreatment[]" style="width:100%;">  </td>
    
</tr>
    ';

}
mysqli_close($conn);