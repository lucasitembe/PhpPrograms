<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Clinic_Name'])){
        $Clinic_Name = $_GET['Clinic_Name'];   
    }else{
        $Clinic_Name = ''; 
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:10%;"><b>SN</b></td>
                <td><b>CLINIC NAME</b></td><td width="10%"><b>STATUS</b></td>
                <td><b>REMARK</b></td>
                <td><b>Main complain</b></td>
                <td><b>Physical Examinations</b></td>                
                <td><b>Investigation </b></td>
                <td><b>Treatment</b></td>
                <td><b>Doctor notes Remarks</b></td>
                <td><b>Diagnosis</b></td>
                <td><b>Radiology Result</b></td>
                <td><b>Laboratory Result</b></td>
                <td><b>Other cancer Registr</b></td>
                </tr>';
    $select_sub_clinics = mysqli_query($conn,"select * from tbl_clinic") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_sub_clinics)){
        $Clinic_ID =$row['Clinic_ID'];
        $Main_complain = $row['Main_complain'];
        $Investigation_Results = $row['Investigation_Results'];
        $Treatment = $row['Treatment'];
        $Doctor_notes_Remarks= $row['Doctor_notes_Remarks'];
        $Physical_Examinations = $row['Physical_Examinations'];
        $Diagnosis = $row['Diagnosis'];
        $Radiology = $row['Radiology'];
        $Laboratory = $row['Laboratory'];
        echo "<tr>
		<td id='thead'>".$temp."</td>";
        echo "<td><a href='editclinic.php?Clinic_ID=".$row['Clinic_ID']."&EditClinic=EditClinicThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Name']."</a></td>";  
        echo "<td><a href='editclinic.php?Clinic_ID=".$row['Clinic_ID']."&EditClinic=EditClinicThisForm' target='_parent' style='text-decoration: none;'>".$row['Clinic_Status']."</a></td>"; 
        echo "<td>
        <select id='remark".$row['Clinic_ID']."' onchange='update_remark(".$row['Clinic_ID'].")'>
        <option>".$row['remark']."</option>
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
    
    </select>
        </td>"; 
        echo "<td>
        <select id='Main_complain".$Clinic_ID."' onchange='update_Main_complain(".$row['Clinic_ID'].")'>
        <option>".$Main_complain."</option>
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
    
    </select>
        </td>"; 
        echo "<td>
        <select id='Physical_Examinations".$row['Clinic_ID']."' onchange='update_Physical_Examinations(".$row['Clinic_ID'].")'>
        <option>".$Physical_Examinations."</option>
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
    
    </select>
        </td>"; 
        echo "<td>
        <select id='Investigation_Results".$row['Clinic_ID']."' onchange='update_Investigation_Results(".$row['Clinic_ID'].")'>
        <option>".$Investigation_Results."</option>
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
    
    </select>
        </td>"; 
        echo "<td>
        <select id='Treatment".$row['Clinic_ID']."' onchange='update_Treatment(".$row['Clinic_ID'].")'>
        <option>".$Treatment."</option>
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
    
    </select>
        </td>"; 
    echo "<td>
        <select id='Doctor_notes_Remarks".$row['Clinic_ID']."' onchange='update_Doctor_notes_Remarks(".$row['Clinic_ID'].")'>
            <option>".$Doctor_notes_Remarks."</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>    
        </select>
        </td>";
        echo "<td>
        <select id='Diagnosis".$row['Clinic_ID']."' onchange='update_Diagnosis(".$row['Clinic_ID'].")'>
            <option>".$Diagnosis."</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>    
        </select>
        </td>";  
        echo "<td>
        <select id='Radiology".$row['Clinic_ID']."' onchange='update_Radiology(".$row['Clinic_ID'].")'>
            <option>".$Radiology."</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>    
        </select>
        </td>";  
        echo "<td>
        <select id='Laboratory".$row['Clinic_ID']."' onchange='update_Laboratory(".$row['Clinic_ID'].")'>
            <option>".$Laboratory."</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>    
        </select>
        </td>";  
        echo "<td>
        <select id='other_cancer_registr".$row['Clinic_ID']."' onchange='update_other_cancer_registr(".$row['Clinic_ID'].")'>
            <option>".$row['other_cancer_registr']."</option>
            <option value='Yes'>Yes</option>
            <option value='No'>No</option>    
        </select>
        </td>";  
 
        $temp++; 
    }   echo "</tr>";
?>
</table></center>
<script src="css/jquery.js"></script>
<script src="script.js"></script>
<script>
    function update_remark(Clinic_ID){
        var remark = $("#remark"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                remark:remark,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_Main_complain(Clinic_ID){
        var Main_complain = $("#Main_complain"+Clinic_ID).val();
        
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Main_complain:Main_complain,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_Physical_Examinations(Clinic_ID){
        var Physical_Examinations = $("#Physical_Examinations"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Physical_Examinations:Physical_Examinations,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }
    
    function update_Diagnosis(Clinic_ID){
        var Diagnosis = $("#Diagnosis"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Diagnosis:Diagnosis,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }
    function update_Investigation_Results(Clinic_ID){
        var Investigation_Results = $("#Investigation_Results"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Investigation_Results:Investigation_Results,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_Treatment(Clinic_ID){
        var Treatment = $("#Treatment"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Treatment:Treatment,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_Doctor_notes_Remarks(Clinic_ID){
        var Doctor_notes_Remarks = $("#Doctor_notes_Remarks"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Doctor_notes_Remarks:Doctor_notes_Remarks,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }
    function update_Radiology(Clinic_ID){
        var Radiology = $("#Radiology"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Radiology:Radiology,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_Laboratory(Clinic_ID){
        var Laboratory = $("#Laboratory"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                Laboratory:Laboratory,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }

    function update_other_cancer_registr(Clinic_ID){
        var other_cancer_registr = $("#other_cancer_registr"+Clinic_ID).val();
        $.ajax({
            type: 'post',
            url: 'update_clinic_remark.php',
            data: {
                other_cancer_registr:other_cancer_registr,
                Clinic_ID:Clinic_ID
            },
            success: function(response) {                
            }
        });
    }
</script>

