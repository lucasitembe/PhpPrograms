<?php
include("./includes/header.php");
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
   $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
   $Patient_Payment_Item_List_ID=""; 
}

if(isset($_GET['from_doctor'])){
   echo "<a href='diabetes_clinic.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage'
' class='art-button-green'>BACK</a>"; 
}else{
?>
<a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>" class="art-button-green">BACK</a>
  <?php
}
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
<fieldset style='height: 500px;overflow-y: scroll'> 
    <legend align="center" style="text-align:center"><b>DIABETIC CLINIC</b>
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">Previous Saved diabetic clinic data according to visiting date</h4>
                </div>
                <div class="box-body">
                    <hr>
                    
                        <?php 
                            $selected = mysqli_query($conn,"
                            SELECT diabetic_clinic_ID, created_at FROM diabetic_clinic 
                                    WHERE Registration_ID = '$Registration_ID' GROUP BY DATE(created_at) ") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($selected);
                        if ($no > 0) {
                            //$count_btn=1;
                            //while ($row = mysqli_fetch_array($selected)) {
                              //  $diabetic_clinic_ID = $row['diabetic_clinic_ID']; $created_at = $row['created_at'];?>
                            <!-- <div style="margin:20px;"> -->
                            <table class="table table-boarded">
                                <thead>
                                    <tr>
                                        <th>#:</th>
                                        <th>Date visited</th>
                                        <th>Review Mesurement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $na=1; while($row = mysqli_fetch_array($selected)) { $diabetic_clinic_ID = $row['diabetic_clinic_ID']; $created_at = $row['created_at'];
                                        $date_modified = date("Y-m-d", strtotime($created_at))
                                        ?>
                                    <tr>
                                        <td><?php echo $na++; ?></td>
                                        <td><?php echo $date_modified;?></td>
                                        <td><a class="art-button-green" href="diabetic_clinic.php?Registration_ID=<?=$Registration_ID?>&created_at=<?=$created_at?>" >Preview</a>
                                        </td>
                                    </tr>
                                    <?php }?> 
                                </tbody>
                            </table>

                                    <?php }else{?>
                                    <table class="table table-boarded">
                                        <thead>
                                            <tr>
                                                <th>#:</th>
                                                <th>Date visited</th>
                                                <th>Review Mesurement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="danger"  style="text-align: center;">No any diabetic clinic record(s).</td> 
                                            </tr>
                                        </tbody>
                                    </table>

                                   <?php }?>
                      


                                    

                                   