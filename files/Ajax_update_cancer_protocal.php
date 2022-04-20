<?php
include("./includes/connection.php");
session_start();
// +========================MSk MOSCOW ======================+
if(isset($_POST['Search_value'])){
    $protocal_name = $_POST['protocal_name'];
    $select_search  = mysqli_query($conn, "SELECT cancer_type_id, date_time,Cancer_Name, Employee_Name FROM tbl_cancer_type ct, tbl_employee e WHERE added_by=Employee_ID AND Cancer_Name LIKE '%$protocal_name%' ") or die(mysqli_error($conn));
    $num=0;
    if(mysqli_num_rows($select_search)>0){
        while($row =mysqli_fetch_assoc($select_search)){
            $cancer_type_id = $row['cancer_type_id'];
            $num++;
            echo "<tr>
                <td>$num</td>
                <td>".$row['Cancer_Name']."</td>
                <td>".$row['Employee_Name']."</td>
                <td>".$row['date_time']."</td>
                <td><input type='button' class='art-button-green' onclick='update_chemo_name($cancer_type_id)' value='EDIT'> <a href='update_cancer_protocal.php?cancer_type_id=$cancer_type_id' class='art-button-green'>PREVIEW</a></td>
            </tr>";
        }
    }else{
        echo "<tr><td colspan='5'>No data Found</td></tr>";
    }
}

if(isset($_POST['adjuvant'])){
    $Status = $_POST['Status'];
    $adjuvant_ID  = $_POST['adjuvant_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $updateadjuvant = mysqli_query($conn, "UPDATE tbl_adjuvant_duration SET status ='$Status', updated_by ='$Employee_ID', updated_at=NOW() WHERE adjuvant_ID='$adjuvant_ID'") or die(mysqli_error($conn));
    if($updateadjuvant){
        echo "Updated";
    }else{
        echo "No";
    }
}

if(isset($_POST['physician'])){
    $Status = $_POST['Status'];
    $physician_ID  = $_POST['physician_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $updateadjuvant = mysqli_query($conn, "UPDATE tbl_physician SET status ='$Status', updated_by ='$Employee_ID', updated_at=NOW() WHERE physician_ID='$physician_ID'") or die(mysqli_error($conn));
    if($updateadjuvant){
        echo "Updated";
    }else{
        echo "No";
    }
}

if(isset($_POST['treatment'])){
    $Status = $_POST['Status'];
    $treatment_ID  = $_POST['treatment_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $updateadjuvant = mysqli_query($conn, "UPDATE tbl_supportive_treatment SET Status ='$Status', Updated_by ='$Employee_ID', Updated_at=NOW() WHERE treatment_ID='$treatment_ID'") or die(mysqli_error($conn));
    if($updateadjuvant){
        echo "Updated";
    }else{
        echo "No";
    }
}


if(isset($_POST['chemotherapy'])){
    $Status = $_POST['Status'];
    $chemotherapy_ID  = $_POST['chemotherapy_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $updateadjuvant = mysqli_query($conn, "UPDATE tbl_chemotherapy_drug SET Status ='$Status', Updated_by ='$Employee_ID', Updated_at=NOW() WHERE chemotherapy_ID='$chemotherapy_ID'") or die(mysqli_error($conn));
    if($updateadjuvant){
        echo "Updated";
    }else{
        echo "No";
    }
}

if(isset($_POST['update_field_added'])){
    $cancer_ID = $_POST['cancer_ID'];
    if(isset($_POST['adjuvantstreanthvl'])){
        $adjuvantstreanthvl = $_POST['adjuvantstreanthvl'];
    }else{
        $adjuvantstreanthvl = '';
    }
    
    if(isset($_POST['duration'])){
        $duration = $_POST['duration'];
    }else{
        $duration = '';
    }
    
    if(isset($_POST['Items'])){
        $Items = $_POST['Items'];
    }else{
        $Items = '';
    }
    if(isset($_POST['Itemss'])){
        $Itemss = $_POST['Itemss'];
    }else{
        $Itemss = '';
    }
    if(isset($_POST['Treatment'])){
        $Treatmentdose = $_POST['Treatment'];
    }else{
        $Treatmentdose = '';
    }
    if(isset($_POST['routTreatment'])){
        $routTreatment = $_POST['routTreatment'];
    }else{
        $routTreatment = '';
    }
    if(isset($_POST['admintTreatment'])){
        $admintTreatment = $_POST['admintTreatment'];
    }else{
        $admintTreatment = '';
    }
    if(isset($_POST['frequanceTreatment'])){
        $frequanceTreatment = $_POST['frequanceTreatment'];
    }else{
        $frequanceTreatment = '';
    }

    if(isset($_POST['medicationTreatment'])){
        $medicationTreatment = $_POST['medicationTreatment'];
    }else{
        $medicationTreatment = '';
    }
    if(isset($_POST['Itemsss'])){
        $Itemsss = $_POST['Itemsss'];
    }else{
        $Itemsss = '';
    }
    if(isset($_POST['Chemotherapydose'])){
        $Chemotherapydose = $_POST['Chemotherapydose'];
    }else{
        $Chemotherapydose = '';
    }
    if(isset($_POST['chemoroutes'])){
        $chemoroutes = $_POST['chemoroutes'];
    }else{
        $chemoroutes = '';
    }
    if(isset($_POST['Chemotherapyadmin'])){
        $Chemotherapyadmin = $_POST['Chemotherapyadmin'];
    }else{
        $Chemotherapyadmin = '';
    }
    if(isset($_POST['Chemomedication'])){
        $Chemomedication = $_POST['Chemomedication'];
    }else{
        $Chemomedication = '';
    }
    if(isset($_POST['chemofrequence'])){
        $chemofrequence = $_POST['chemofrequence'];
    }else{
        $chemofrequence = '';
    }
    if(isset($_POST['dvolume'])){
        $dvolume = $_POST['dvolume'];
    }else{
        $dvolume = '';
    }
    if(isset($_POST['Hydrationvolume'])){
        $Hydrationvolume = $_POST['Hydrationvolume'];
    }else{
        $Hydrationvolume = '';
    }

    if(isset($_POST['Item_ID_physician'])){
        $Item_ID_physician = $_POST['Item_ID_physician'];
    }else{
        $Item_ID_physician =array();
    }
    if(isset($_POST['Hydrationtype'])){
        $Hydrationtype = $_POST['Hydrationtype'];
    }else{
        $Hydrationtype = '';
    }

    if(isset($_POST['Hydrationminutes'])){
        $Hydrationminutes = $_POST['Hydrationminutes'];
    }else{
        $Hydrationminutes = '';
    }
    

    //chemo update
    if(isset($_POST['chemoVolume_c'])){
        $chemoVolume_c = $_POST['chemoVolume_c'];
    }else{
        $chemoVolume_c = array();
    }
    if(isset($_POST['chemofrequence_c'])){
        $chemofrequence_c_Array = $_POST['chemofrequence_c'];
    }else{
        $chemofrequence_c_Array = array();
    }
    if(isset($_POST['chemoadmin_c'])){
        $chemoadmin_c_Array = $_POST['chemoadmin_c'];
    }else{
        $chemoadmin_c_Array = array();
    }
    if(isset($_POST['chemoroute_c'])){
        $chemoroute_c_Array = $_POST['chemoroute_c'];
    }else{
        $chemoroute_c_Array = array();
    }
    if(isset($_POST['chemotherapy_ID'])){
        $chemotherapy_ID = $_POST['chemotherapy_ID'];
    }else{
        $chemotherapy_ID = array();
    }
#----------
    if(isset($_POST['chemotherapy_ID'])){
        $chemotherapy_ID_Array = $_POST['chemotherapy_ID'];
    }else{
        $chemotherapy_ID_Array = array();
    }

    if(isset($_POST['chemoVolume_c'])){
        $chemoVolume_c_Array = $_POST['chemoVolume_c'];
    }else{
        $chemoVolume_c_Array = array();
    }
    if(isset($_POST['adjuvantname'])){
        $adjuvantname_array = $_POST['adjuvantname'];
    }else{
        $adjuvantname_array=array();
    }
//adjuvant order
    if(isset($_POST['adjuvant_ID'])){
        $adjuvant_ID_array = $_POST['adjuvant_ID'];
    }else{
        $adjuvant_ID_array=array();
    }
    if(isset($_POST['duration'])){
        $duration_array = $_POST['duration'];
    }else{
        $duration_array=array();
    }
    if(isset($_POST['adjuvantstrenth'])){
        $adjuvantstrenth_array = $_POST['adjuvantstrenth'];
    }else{
        $adjuvantstrenth_array=array();
    }

    // physician update
    if(isset($_POST['physician_ID'])){
        $physician_ID_array = $_POST['physician_ID'];
    }else{
        $physician_ID_array=array();
    }
    if(isset($_POST['physician_type'])){
        $physician_type_array = $_POST['physician_type'];
    }else{
        $physician_type_array=array();
    }
    if(isset($_POST['physician_minutes'])){
        $physician_minutes_array = $_POST['physician_minutes'];
    }else{
        $physician_minutes_array=array();
    }
    if(isset($_POST['physician_volume'])){
        $physician_volume_array = $_POST['physician_volume'];
    }else{
        $physician_volume_array=array();
    }

    //supportive update
    if(isset($_POST['treat_medication'])){
        $treat_medication_Array = $_POST['treat_medication'];
    }else{
        $treat_medication_Array = array();
    }
    if(isset($_POST['frequance_Treatment'])){
        $frequance_Treatment_Array = $_POST['frequance_Treatment'];
    }else{
        $frequance_Treatment_Array = array();
    }
    if(isset($_POST['admint_Treatment'])){
        $admint_Treatment_Array = $_POST['admint_Treatment'];
    }else{
        $admint_Treatment_Array = array();
    }
    if(isset($_POST['treat_route'])){
        $treat_route_Array = $_POST['treat_route'];
    }else{
        $treat_route_Array = array();
    }
    if(isset($_POST['treat_dose'])){
        $treat_dose_Array= $_POST['treat_dose'];
    }else{
        $treat_dose_Array=array();
    }
    if(isset($_POST['treatment_ID'])){
        $treatment_ID_Array = $_POST['treatment_ID'];
    }else{
        $treatment_ID_Array = array();
    }
    if(isset($_POST['medication_Treatment'])){
        $medication_Treatment_Array = $_POST['medication_Treatment'];
    }else{
        $medication_Treatment_Array=array();
    }

    
    $totaltype = sizeof($Hydrationvolume); 
    
    //insert adjuvant
   
    if(isset($_POST['adjuvantstreanthvl'])){
        $totalitem = sizeof($Items);
        for ($i=0; $i< $totalitem; $i++){                       
            $adjuvant = $Items[$i];
            $duration_name  = $duration[$i];
            $adjuvantstrenth = $adjuvantstreanthvl[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           
            $updateadjuvant =mysqli_query($conn,"INSERT INTO tbl_adjuvant_duration (cancer_type_id,adjuvant,adjuvantstrenth,duration,date_and_time,Added_by) VALUES('$cancer_ID','$adjuvant','$adjuvantstrenth','$duration_name',NOW(), '$Employee_ID')") or die(mysqli_error($conn));   
        }   
    }
     //update adjuvant
   
     if(sizeof($adjuvant_ID_array)>0){
        $totalitem = sizeof($adjuvant_ID_array);
        for ($i=0; $i< $totalitem; $i++){   
            $adjuvant_ID = $adjuvant_ID_array[$i];                    
            $adjuvant = $adjuvantname_array[$i];
            $duration_name  = $duration_array[$i];
            $adjuvantstrenth = $adjuvantstrenth_array[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           
            $updateadjuvant =mysqli_query($conn,"UPDATE tbl_adjuvant_duration SET adjuvant='$adjuvant',adjuvantstrenth='$adjuvantstrenth',duration='$duration_name' WHERE adjuvant_ID='$adjuvant_ID' AND cancer_type_id='$cancer_ID'") or die(mysqli_error($conn));   
        }   
    }
    
    // Insert hypration physician_ID
    if(sizeof($physician_ID_array)>0){
        $totalphsician = sizeof($physician_ID_array);
        for($i=0;$i<$totalphsician;$i++) {
            $volume_name = $physician_volume_array[$i];
            $type_name  = $physician_type_array[$i];
            $minutes_name = $physician_minutes_array[$i];  
            $physician_ID=$physician_ID_array[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];                    
            $sql_attache=mysqli_query($conn,"UPDATE tbl_physician SET physician_volume='$volume_name',physician_type='$type_name',physician_minutes='$minutes_name' where cancer_type_id='$cancer_ID' AND physician_ID='$physician_ID'" ) or die("Failed to update ".mysqli_error($conn));  
            
        }
    }

    // Update hypration
    if(isset($_POST['Hydrationvolume'])){
        for($i=0;$i<$totaltype;$i++) {
            $Physician_Item_name= $Item_ID_physician[$i];
            $volume_name = $Hydrationvolume[$i];
            $type_name  = $Hydrationtype[$i];
            $minutes_name = $Hydrationminutes[$i];  
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];                      
            $sql_attache=mysqli_query($conn,"INSERT INTO tbl_physician (Physician_Item_name, physician_volume,physician_type,physician_minutes,date_and_time,cancer_type_id,Added_by) VALUES('$Physician_Item_name','$volume_name','$type_name','$minutes_name',NOW(),'$cancer_ID', '$Employee_ID')") or die("Failed To update ".mysqli_error($conn));  
            
        }
    }
    
    $totalitemtrat = sizeof($Itemss); 
    if(isset($_POST['Itemss'])!=''){
        for($i=0;$i<$totalitemtrat;$i++) {    
                          
            $item_name = $Itemss[$i];
            $dose_name  = $Treatmentdose[$i];
            $route_name  = $routTreatment[$i];
            $admin_name  = $admintTreatment[$i];
            $frequence_name  = $frequanceTreatment[$i];
            $medication_name  = $medicationTreatment[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            $treatment_item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$item_name'"))['Item_ID'];
            //echo $treatment_item_ID;exit();
            $sql_attache=mysqli_query($conn,"INSERT INTO tbl_supportive_treatment(cancer_type_id,item_ID,supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,date_and_time, Added_by)VALUES('$cancer_ID','$treatment_item_ID','$item_name','$dose_name','$route_name','$admin_name','$frequence_name','$medication_name',NOW(), '$Employee_ID')") or die(mysqli_error($conn)."==="); 
            
            
        }
    }
    if(sizeof($treatment_ID_Array)>0){
        $totalTretID = sizeof($treatment_ID_Array);
        $check = "";
        for($i=0;$i<sizeof($treatment_ID_Array);$i++) {        
            $treatmentID = $treatment_ID_Array[$i];
            $admint_Treatment  = $admint_Treatment_Array[$i];
            $frequance_Treatment  = $frequance_Treatment_Array[$i];
            $medication_Treatment  = $medication_Treatment_Array[$i];
            $treat_route  = $treat_route_Array[$i];
            $treat_dose =$treat_dose_Array[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           
            $sql_attache2=mysqli_query($conn,"UPDATE tbl_supportive_treatment SET Route='$treat_route', Administration_Time='$admint_Treatment',Medication_Instructions='$medication_Treatment', Frequence='$frequance_Treatment',Dose='$treat_dose'  WHERE treatment_ID='$treatmentID'") or die(mysqli_error($conn)); 
           

        }
    }
    $totaldrug = sizeof($Itemsss);
    if(isset($_POST['Itemsss']) != ''){  
        $i=0;        
        for($i=0;$i<$totaldrug;$i++) {        
            $drug_name = $Itemsss[$i];
            $ddose_name  = $Chemotherapydose[$i];
            $dvolume_name  = $dvolume[$i];
            $droute_name  = $chemoroutes[$i];
            $dadmin_name  = $Chemotherapyadmin[$i];
            $dfrequence_name  = $chemofrequence[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            $drug_item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$drug_name'"))['Item_ID'];
            $sql_attache2=mysqli_query($conn,"INSERT INTO tbl_chemotherapy_drug(cancer_type_id,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,date_and_time, Added_by)VALUES('$cancer_ID','$drug_name','$ddose_name','$dvolume_name','$droute_name','$dadmin_name','$dfrequence_name',NOW(), '$Employee_ID')") or die(mysqli_error($conn)); 

        }
    }
    if(sizeof($chemotherapy_ID_Array)>0){
        $totalID = sizeof($chemotherapy_ID_Array);
        // $check = "";


        for($i=0;$i<sizeof($chemotherapy_ID_Array);$i++) {        
            $chemotherapyID = $chemotherapy_ID_Array[$i];
            $chemoVolume_c  = $chemoVolume_c_Array[$i];
            $chemoroute_c  = $chemoroute_c_Array[$i];
            $chemoadmin_c  = $chemoadmin_c_Array[$i];
            $dfrequence_name  = $chemofrequence_c_Array[$i];
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            $sql_attache2=mysqli_query($conn,"UPDATE tbl_chemotherapy_drug SET Volume='$chemoVolume_c',Route='$chemoroute_c',Frequency='$dfrequence_name', Admin_Time='$chemoadmin_c'  WHERE chemotherapy_ID='".$chemotherapy_ID_Array[$i]."'") or die(mysqli_error($conn)); 
        }
    }
    echo "Updated successful";
}

if(isset($_POST['deleteadjuvant'])){
    $adjuvant_ID = $_POST['adjuvant_ID'];
    $deleteadjuvant = mysqli_query($conn, "DELETE  FROM tbl_adjuvant_duration WHERE adjuvant_ID='$adjuvant_ID'") or die(mysqli_error($conn));
    if($deleteadjuvant){
        echo "Deleted successful";
    }else{
        echo "Failed to delete";
    }
}

if(isset($_POST['deletetreatment'])){
    $treatment_ID = $_POST['treatment_ID'];
    $deleteadjuvant = mysqli_query($conn, "DELETE  FROM tbl_supportive_treatment WHERE treatment_ID='$treatment_ID'") or die(mysqli_error($conn));
    if($deleteadjuvant){
        echo "Deleted successful";
    }else{
        echo "Failed to delete";
    }
}


if(isset($_POST['deletechemotherapy_ID'])){
    $chemotherapy_ID = $_POST['chemotherapy_ID'];
    $deleteadjuvant = mysqli_query($conn, "DELETE  FROM tbl_chemotherapy_drug WHERE chemotherapy_ID='$chemotherapy_ID'") or die(mysqli_error($conn));
    if($deleteadjuvant){
        echo "Deleted successful";
    }else{
        echo "Failed to delete";
    }
}


if(isset($_POST['deletephysician_ID'])){
    $physician_ID = $_POST['physician_ID'];
    $deletephysician = mysqli_query($conn, "DELETE  FROM tbl_physician WHERE physician_ID='$physician_ID'") or die(mysqli_error($conn));
    if($deletephysician){
        echo "Deleted successful";
    }else{
        echo "Failed to delete";
    }
}
if(isset($_POST['administer_protocalname'])){
    $protocal_name = $_POST['protocal_name'];
    $Protocal_status = $_POST['Protocal_status'];
    $cancer_type_id = $_POST['cancer_type_id'];
    ?>
    <div style="padding-top: 40px;">
        <table width='100%'>
            <tr>
                <td width='75%'>
                    <input type="text" id='Edited_name' class="form-control" style='width:80;' value="<?=$protocal_name; ?>">
                </td>
                <td width='15%'>
                    <select name="" id="Protocal_status" class="form-control">
                        <option value="<?php echo $Protocal_status; ?>"><?= $Protocal_status; ?></option>
                        <option value="Enabled">Enable</option>
                        <option value="Disabled">Disable</option>
                    </select>
                </td>
                <td width='10%'>                    
                    <input type="button" class="art-button-green" value="UPDATE" onclick="editptotocalname(<?=$cancer_type_id;?>)">
                </td>
            </tr>
        </table>
        
    </div>
<?php
}

if(isset($_POST['updateptotocal'])){
    $protocal_name = mysqli_real_escape_string($conn, $_POST['protocal_name']);
    $cancer_type_id = $_POST['cancer_type_id'];
    $Protocal_status =$_POST['Protocal_status'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $updateprotocal = mysqli_query($conn, "UPDATE tbl_cancer_type SET Cancer_Name='$protocal_name', Protocal_status='$Protocal_status' , Updated_by='$Employee_ID', Updated_at='NOW()' WHERE cancer_type_id='$cancer_type_id'") or die(mysqli_error($conn));

    if($updateprotocal){
        echo "Success";
    }else{
        echo "Failed to update";
    }
}