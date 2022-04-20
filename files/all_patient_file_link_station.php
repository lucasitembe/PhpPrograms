<?php
include("./includes/header.php");
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
if(isset($_GET['Patient_Payment_ID'])){
   $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}else{
   $Patient_Payment_ID=""; 
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
   $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
   $Patient_Payment_Item_List_ID=""; 
}
if(isset($_GET['this_page_from'])){
   $this_page_from=$_GET['this_page_from'];
}else{
   $this_page_from=""; 
}
if(isset($_GET['consultation_ID'])){
   $consultation_ID=$_GET['consultation_ID'];
}else{
   $consultation_ID=""; 
}
if(isset($_GET['from_consulted'])){
   $from_consulted=$_GET['from_consulted'];
}else{
   $from_consulted=""; 
}
if(isset($_GET['previous_notes'])){
   $previous_notes=$_GET['previous_notes'];
}else{
   $previous_notes=""; 
}
$Admision_ID="";
 $sql_select_admision_id_result=mysqli_query($conn,"SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_admision_id_result)){

        $Admision_ID=mysqli_fetch_assoc($sql_select_admision_id_result)['Admision_ID'];
}

// if($this_page_from=="doctor_outpatient"){
// ?>
<!-- // <a href="doctorspageoutpatientwork.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&NR=true&PatientBilling=PatientBillingThisForm&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a> --> <?php 
// }else if($this_page_from=="patient_record"){
// ?>
<!-- // <a href="Patientfile_Record.php?section=Patient&DialysisWorks=DialysisWorksThisPage&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a> -->
 <?php  
// }else if($this_page_from=="nurse_communication"){
//  ?>
<!-- // <a href="nursecommunicationpage.php?Registration_ID=<?= $Registration_ID ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a> -->
 <?php    
// }else if($this_page_from=="out_patient_clinical_notes"){
//    ?>
<!-- // <a href="clinicalnotes.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&previous_notes=<?= $previous_notes ?>&from_consulted=<?= $from_consulted ?>&this_page_from=<?= $this_page_from ?>" class="art-button-green">BACK</a> -->
 <?php  
// }else{
// ?>
<input type="button" value="BACK" class="art-button-green" onclick="history.go(-1)"/>
<!-- <a href="#" onclick="goBack()"class="art-button-green">BACK</a> -->
 <script>
//  function goBack() {
//     window.history.back();
//  }
 </script>
    <?php
//}        
 $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;

        ?>
<fieldset style='height: 450px;'>
    <legend align="center" style="text-align:center"><b>DIFFERENT PATIENT FILE FORMAT</b>
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    
    <div class="row">
        <br/>
         
        <div class="col-md-12">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <table class="table">
                    <!--<tr>
                        <td>
                            <a href="#" onclick="get_result_from_integrated_machine(<?= $Registration_ID ?>)">
                                <button style="width:100%">INTERGRATED LAB RESULTS</button>
                            </a>
                        </td>
                    </tr>-->
                    <tr style=''>
                        <td>
                            <a href="afya_card_medical_record_details.php?Registration_ID=<?= $Registration_ID; ?>">
                                <button style="width:100%">AFYA CARD DETAIL</button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="Patientfile_Record_Detail.php?Registration_ID=<?= $Registration_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&PatientFile=PatientFileThisForm&position=out&this_page_from=<?= $this_page_from ?>">
                                <button style="width:100%">PATIENT FILE</button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- <a href="Patientfile_Record_Detail.php?Registration_ID=<?= $Registration_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&PatientFile=PatientFileThisForm&position=out&this_page_from=<?= $this_page_from ?>" onclick="alert('*Comprehensive file* is under repair.Please use patient file button above.Sorry for inconvenience!')">
                                <button style="width:100%">COMPREHENSIVE PATIENT FILE</button>
                            </a> -->
                           <a href="newpateientfile_summary.php?Registration_ID=<?= $Registration_ID; ?>&Patient_Payment_ID=<?= $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&this_page_from=<?= $this_page_from ?>&this_page_from=<?= $this_page_from ?>&previous_notes=<?= $previous_notes ?>&from_consulted=<?= $from_consulted ?>">
                                <button style="width:100%">COMPREHENSIVE PATIENT FILE</button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button style="width:100%" onclick='showSummeryPatientFile()'>SUMMARY PATIENT FILE</button>
                        </td>
                    </tr>
<!--                    <tr>
                        <td>
                            <a href="preview_anethesia.php?registration_id=<?= $Registration_ID; ?>">
                                <button style="width:100%">ANESTHESIA RECORD</button>
                            </a>
                        </td>
                    </tr>-->
<!--                    <tr>
                        <td>
                            <?php 
                                $sql_select_admision_id_result=mysqli_query($conn,"SELECT Admision_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_admision_id_result)){
                                    
                                    $Admision_ID=mysqli_fetch_assoc($sql_select_admision_id_result)['Admision_ID'];
                                    
                                    $sql_select_consultation_id_result=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                                    $consultation_ID=mysqli_fetch_assoc($sql_select_consultation_id_result)['consultation_ID'];
                                ?>
                            <a href="nursecommunication_preOperative.php?Registration_ID=<?= $Registration_ID ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>&this_page_from=<?= $this_page_from ?>">
                                <button style="width:100%">PRE-OPERATIVE CHECK LIST</button>
                            </a>
                                <?php }else{ ?>
                                    <a href="#" onclick="alert('Make sure the this Patient has been admitted')">
                                        <button style="width:100%">PRE-OPERATIVE CHECK LIST</button>
                                    </a>
                                <?php } ?>
                        </td>
                    </tr>-->
                      <tr>
                        <td>
                            <!-- <a href="#" onclick="alert('*The function of this button * is under repair.Sorry for inconvenience!')">
                                <button style="width:100%">CANCER PATIENT</button>
                            </a> -->
                           <a href="Cancer_patient_record.php?Registration_ID=<?php echo $Registration_ID; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record">
                                <button style="width:100%">CANCER PATIENT</button>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</fieldset>
<div id="summerypatientfile" style="width:100%;overflow-x:hidden;height:620px;display:none;overflow-y:scroll">
        <div id="summpatfileInfo">
        </div>
</div>
<script>
    function showSummeryPatientFile() {
        document.getElementById('summpatfileInfo').innerHTML = '';
        if (window.XMLHttpRequest) {
            ajaxTimeObjt = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
            ajaxTimeObjt.overrideMimeType('text/xml');
        }
        ajaxTimeObjt.onreadystatechange = function () {
            var data = ajaxTimeObjt.responseText;
            document.getElementById('summpatfileInfo').innerHTML = data;
            $("#summerypatientfile").dialog("open");
        }; //specify name of function that will handle server response....
        ajaxTimeObjt.open("GET", "get_summery_pat_file.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID ?>&Registration_ID=<?php echo $Registration_ID ?>", true);
        ajaxTimeObjt.send();


    }
 function preview_lab_result(Product_Name,Payment_Item_Cache_List_ID){
    window.open("preview_ntergrated_lab_result.php?Product_Name="+Product_Name+"&Payment_Item_Cache_List_ID="+Payment_Item_Cache_List_ID,"_blank");
 }
 function get_result_from_integrated_machine(Registration_ID){
    $.ajax({
        type:'POST',
        url:'ajax_get_result_from_integrated_machine_patient_file.php',
        data:{Registration_ID:Registration_ID},
        success:function(data){
                $("<div></div>").dialog({
                        title: 'INTERGRATED PATIENT LAB RESULTS',
                        width: '90%',
                        height: '600',
                        modal: true,
                        resizable: false,
                    }).html(data);
        }
    });
}
</script>

<script>
    $(document).ready(function () {//
        $("#summerypatientfile").dialog({autoOpen: false, width: '95%', height: 620, title: 'PATIENT FILE', modal: true, position: 'middle'});
         
    });
</script>
<?php
include("./includes/footer.php");
?>

