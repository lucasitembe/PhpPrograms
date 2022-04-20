<?php
// include("./includes/header.php");
include("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
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
//  if(isset($_GET['this_page_from'])){
//     $this_page_from=$_GET['this_page_from'];
//  }else{
//     $this_page_from=""; 
//  }
//  if(isset($_GET['consultation_ID'])){
//     $consultation_ID=$_GET['consultation_ID'];
//  }else{
//     $consultation_ID=""; 
//  }
?>
  <?php
  $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
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
<fieldset style='height: 500px;overflow-y: scroll'>
    <legend align="center" style="text-align:center">
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
   
      <?php  
    $index=1;
    include("./includes/connection.php");
    //$Registration_ID=$_POST['Registration_ID'];
    $sql="SELECT ref.objective_RE, ref.objective_LE, ref.subjective_RE, ref.subjective_LE, ref.phoria, ref.pd, ref.eom, ref.npc, ref.diagnosis_management,ref.vision_assesment_note,ref.orthoptics_notes,ref.diagnosis,emp.Employee_Name,saved__data,ref.refraction_remark,emp.Employee_Name,ref.add_remark FROM tbl_refraction AS ref,tbl_employee as emp WHERE  ref.Registration_ID='$Registration_ID' AND emp.Employee_ID=ref.Employee_ID ORDER BY saved__data DESC";
    $query=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($query) > 0){

   
    while($row=mysqli_fetch_array($query)){
        $objective_RE=$row['objective_RE'];
        $objective_LE=$row['objective_LE'];
        $subjective_RE=$row['subjective_RE'];
        $subjective_LE=$row['subjective_LE'];
        $pd=$row['pd'];
        $eom=$row['eom'];
        $npc=$row['npc'];
        $phoria=$row['phoria'];
        $diagnosis_management=$row['diagnosis_management'];
        $vision_assesment_note=$row['vision_assesment_note'];
        $diagnosis=$row['diagnosis'];
        $orthoptics_notes=$row['orthoptics_notes'];
        $saved__data=$row['saved__data'];
        $Employee_Name=$row['Employee_Name'];
        $refraction_remark=$row['refraction_remark'];
        $add_remark=$row['add_remark'];
    ?>
    <center><h3 style='color:white' class="art-button-green">Visit Date <?=$saved__data?></h3></center>
     <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em">
        <div class="one">
            <table class="table">
                <caption></caption>
                <thead>
                    <tr>
                        <th  colspan="2" style="text-align:center;">OBJECTIVE</th>
                    <tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th>R</th>
                        <th>L</th>
                    <tr>
                    <tr>
                        <td><input type="text" class="form-control" id="objective_RE" value="<?php echo $objective_RE;?>" readonly> </td>
                        <td><input type="text" class="form-control" id="objective_LE" value="<?php echo $objective_LE;?>" readonly></td>     
                    <tr>
                </tbody>
            </table>
        </div>
        <div class="two">
            <table class="table">
                <caption></caption>
                <thead>
                    <tr>
                        <th  colspan="2" style="text-align:center;">SUBJECTIVE</th>
                    <tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th>R</th>
                        <th>L</th>
                    <tr>
                    <tr>
                        <td><input type="text" class="form-control" id="subjective_RE"value="<?php echo $objective_LE;?>" readonly></td>
                        <td><input type="text" class="form-control" id="subjective_LE"value="<?php echo $objective_LE;?>" readonly></td>     
                    <tr>
                </tbody>
            </table>
               
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="one">
            ADD<td><input type='text' id='add_remark' class="form-control" value="<?php echo $add_remark;?>"readonly>
        </div>
    </div>
   
    

    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            P.D<td><input type='text' id='pd' class="form-control" value="<?php echo $pd;?>" readonly>
        </div>
        <div class="two">
            N.P.C<td><input type='text' id='npc' class="form-control" value="<?php echo $npc;?>" readonly>
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            E.O.M<td><input type='text' id='eom' class="form-control" value="<?php echo $eom;?>" readonly>
        </div>
        <div class="two">
            PHORIA<td><input type='text' id='phoria' class="form-control" value="<?php echo $phoria;?>" readonly>  
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Diagnosis Management<textarea id="diagnosis_management" class="form-control" readonly><?php echo $diagnosis_management;?></textarea>
        </div>
        <div class="two">
            Low Vision Assesment Notes<textarea id="vision_assesment_note" class="form-control" readonly><?php echo $vision_assesment_note;?></textarea>   
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Orthoptics Notes<textarea id="orthoptics_notes" class="form-control" readonly><?php echo $orthoptics_notes;?></textarea>
            
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Remarks<textarea id="orthoptics_notes" class="form-control" readonly><?php echo $refraction_remark;?></textarea>
        </div>
    </div>
    <table class="table">
        <caption></caption>
        <thead>
            <tr>
                <th colspan="5" style="text-align:left;">Diagnosis Found</th>
            <tr>
        </thead>
        <tbody>
            
            <tr>
                <td>
                    <input type="text" id="ANTIMETROPIA" class="form-control" class="diagnosis"value="<?php echo $diagnosis;?>" readonly>
                </td>

            <tr>
            <tr>
                <td>
                    <label>Perfomed By</label><input type="text" id="ANTIMETROPIA" class="form-control" class="diagnosis"value="<?php echo $Employee_Name;?>" readonly>
                </td>

            <tr>
        </tbody>
    </table>

<?php
    }
    }else{
        echo '<h3 style="text-align:center">No Result Found</h3>';
    }
    ?>
</fieldset>


<?php
// include("./includes/footer.php");
?>

