<?php
include("./includes/header.php");
include("./includes/connection.php");

if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
   
//get section for back buttons
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = '';
}

if (isset($_GET['saved_date'])) {
    $saved_date = $_GET['saved_date'];
} else {
    $saved_date = 0;
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}

if(isset($_GET['cancer_id'])){
    $cancer_id = $_GET['cancer_id'];
}else{
    $cancer_id =0;
}
?>
<a href="chemotherapy_patient_file.php?Registration_ID=<?=$Registration_ID?>" class="art-button-green">BACK</a>
<?php 
include('patient_demograpric_data.php');




$maincomplain = mysqli_fetch_assoc(mysqli_query($conn, "SELECT maincomplain FROM tbl_consultation  WHERE Registration_ID='$Registration_ID' AND  DATE(Consultation_Date_And_Time) = DATE('$saved_date')"))['maincomplain'];


?>
<br/><br/><br/><fieldset >
    <!-- style="width:99%;height:600px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll" -->
    <input type="text" id="Patient_Name" value="<?php echo  $Patient_Name ?>" style="display: none;">
    <input type="text" id="Registration_ID" value="<?php echo  $Registration_ID ?>" style="display: none;">
    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
     A:  PATIENT PROFILE
    </div>
       <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan=""> <?php echo  $Patient_Name ?></td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan=""><?php echo $Country  ?></td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan=""><?php echo $Region  ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td><?php echo $Registration_ID  ?></td><td style="text-align:right"><b>Phone #:</b></td><td ><?php echo $Phone_Number ?></td><td style="text-align:right"><b>District:</b></td><td ><?php echo $District ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td ><?php echo date("j F, Y", strtotime($Date_Of_Birth)) ?></td><td style="text-align:right"><b>Gender:</b></td><td ><?php echo $Gender ?></td><td style="text-align:right"></td><td ><?php //echo $Deseased ?></td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right" ><b>Insurance Details:</b></td><td colspan=""> <?php echo $Guarantor_Name . $sponsoDetails ?></td>
                <td style="width:10%;text-align:right" ><b>Consultation Date:</b></td><td colspan=""> <?php echo $Consultation_Date_And_Time ?></td>
                <td style="width:10%;text-align:right" ><b>Consultant :</b></td><td colspan=""> <?php echo $Employee_Title ?>  <?php echo ucfirst($Employee_Name) ?></td>
            </tr> 
            
        </table>
        <table class="table">
            <tr>
                <td  style="width:10%;text-align:right "><b>Clinical notes:</b></td>
                <td ><?php echo $maincomplain; ?></td>
            </tr>
        </table>
        <?php  include('cancer_registration_form_inclusion.php');?>
        <table class="table">
            <tr>
                <td width="100%">
                    <div style="width:99%;font-size:larger;border:1px solid #000;  background:#ccc;" id="outpatient">
                            PATIENT PROTOCAL ASSIGNED
                    </div>
                </td>
            </tr>
        <?php
       

        $select_patient_procal=mysqli_query($conn,"SELECT Cancer_Name, cpd.cancer_type_id,Patient_protocal_details_ID FROM tbl_cancer_patient_details cpd, tbl_cancer_type ct WHERE ct.cancer_type_id=cpd.cancer_type_id AND  Registration_ID='$Registration_ID' ORDER BY cpd.cancer_type_id DESC") or die(mysqli_error($conn));
        if((mysqli_num_rows($select_patient_procal))>0){
        while($row=mysqli_fetch_assoc($select_patient_procal)){
                $cancer_ID=$row['cancer_type_id'];
                $disease_name=$row['Cancer_Name'];
                $Patient_protocal_details_ID = $row['Patient_protocal_details_ID'];
                $num_count++;
                ?>
                <tr>
                    <th style="background: #ccc" width="100%"><?php echo strtoupper($disease_name)?></th>
                </tr>
                <tr>
                    <td width="100%">
                        <?php include('chemotherapy_patient_protocal_inclusion.php')?>
                    </td>
                </tr>
                 
         <?php
            }
        }else{
            echo "<tr>
                        <td colspan='3' style='color:red; text-align:center;'>No any Protocal assigned  to this patient yet!! </td>
                    </tr>";
        }
        ?>
        </table>
        </div>