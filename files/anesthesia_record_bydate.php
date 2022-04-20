<?php
include("./includes/header.php");
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
?>
<a href="anesthesia_record_chart.php?Registration_ID=<?= $Registration_ID ?>" class="art-button-green">BACK</a>

  <?php
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
    <legend align="center" style="text-align:center"><b>ANESTHESIA RECORD</b>
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">Previous Saved anesthesia record data according to visiting date</h4>
                </div>
                <div class="box-body">
                    <hr>
                    
                        <?php 
                            $selected = mysqli_query($conn,"
                            SELECT anasthesia_record_id, anasthesia_created_at FROM tbl_anasthesia_record_chart 
                                    WHERE Registration_ID = '$Registration_ID' GROUP BY DATE(anasthesia_created_at) ") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($selected);
                        if ($no > 0) {
                            //$count_btn=1;
                            //while ($row = mysqli_fetch_array($selected)) {
                              //  $anasthesia_record_id = $row['anasthesia_record_id']; $anasthesia_created_at = $row['anasthesia_created_at'];?>
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
                                    <?php $na=1; while($row = mysqli_fetch_array($selected)) { $anasthesia_record_id = $row['anasthesia_record_id']; $anasthesia_created_at = $row['anasthesia_created_at'];
                                        $date_modified = date("Y-m-d", strtotime($anasthesia_created_at))
                                        ?>
                                    <tr>
                                        <td><?php echo $na++; ?></td>
                                        <td><?php echo $date_modified;?></td>
                                        <td><a class="art-button-green" href="anesthesia_record_preveiw.php?Registration_ID=<?=$Registration_ID?>&anasthesia_created_at=<?=$anasthesia_created_at?>&anasthesia_record_id=<?=$anasthesia_record_id?>?>" target="_blank" >Preview PDF</a>
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
                                                <td colspan="3" class="danger"  style="text-align: center;">No any anesthesia record(s).</td> 
                                            </tr>
                                        </tbody>
                                    </table>

                                   <?php }?>
                      


                                    

                                   