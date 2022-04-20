<?php 
include("./includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
$startDate = $_GET['prev_dh_start_date'];
$endDate = $_GET['prev_dh_end_date'];

?>  
    
    <table class="table table-bordered table-hover" style="background-color:#fff;">
        <thead>
            <tr>
                <th>NO</th>
                <th>STATUS</th>
                <th>INDICATION</th>
                <th>DIAGNOSIS</th>
                <th>MEDICATION</th>
                <th>ORDERED DATE</th>
                <th>MODE</th>
                <th>ACCESS <br> AVF -a <br> Tunneled - t <br> Untunneled -u </th>
                <th>DURATION</th>
                <th>UF/UFR</th>
                <th>QB <br> (PUMP) </th>
                <th>DIALYSATE <br> SPEED</th>
                <th>BATH <br> (NA)</th>
                <th>BATH <br> (K)</th>
                <th>BATH <br> (HCO<sub>3</sub>)</th>
                <th>AMOUNT OF HEPARIN</th>
                <th>TYPE OF DIALYSIS</th>
                <th>PRESCRIPTION</th>
                <th>ORDERED BY </th>
            <?php if(isset($_GET['showActionBTN'])){ ?>
<!--                <th>ACTION</th>-->
                <th>ACTION</th>
                <th>ACTION</th>
            <?php } ?>
            </tr>
        </thead>
        <?php
            $i =1;
            $select_sql ="SELECT * FROM `tbl_dialysis_inpatient_prescriptions` WHERE Registration_ID='$Registration_ID' AND ordered_on BETWEEN '$startDate' AND '$endDate' ORDER BY `prescription_id` DESC";
            //echo $select_sql;exit;
            $select_query=  mysqli_query($conn,$select_sql) or die(mysqli_error($conn));
            
            if(mysqli_num_rows($select_query)<1){
                echo '<tr><td colspan="14"><center><b>NO PRESCRIPTION FAUND..</b></center></td></tr>';
            }
            else{

            while($prescription = mysqli_fetch_assoc($select_query)){
                $prescription_id = $prescription['prescription_id'];
            
        ?>
        <tr <?php if($prescription['status']=='Not Done'){}else{
            echo 'style="background-color:#fff0f0;"';
        }?>>
            <td><?=$i?></td>
            <td> 
                <b <?php if($prescription['status']=='Done'){echo 'class="text-primary"';}else{echo 'class="text-danger"';}?>> 
                    <?php echo $prescription['status']?> 
                </b>
            </td>
            <td><?=$prescription['indication']?></td>
            <td><?=$prescription['diagnosis']?></td>
            <td><?=$prescription['medication']?></td>
            <td><?=$prescription['ordered_on']?></td>
            <td><?=$prescription['mode']?></td>
            <td><?=$prescription['access']?></td>
            <td><?=$prescription['duration']?></td>
            <td><?=$prescription['uf_ufr']?></td>
            <td><?=$prescription['qb']?></td>
            <td><?=$prescription['dialysate']?></td>
            <td><?=$prescription['bath']?></td>
            <td><?=$prescription['bath_k']?></td>
            <td><?=$prescription['bath_hco3']?></td>
            <td><?=$prescription['amount_of_heparine']?></td>
            <td><?=$prescription['dialysis_type']?></td>
            <td>x <?=$prescription['sessioncicle']?> <?=$prescription['sessioncicleunits']?> for 
                            <?=$prescription['duartions']?> 
                            <?=$prescription['durationunits']?>, each session <?=$prescription['sessiontime']?><?=$prescription['sessiontimeunits']?>
            </td>
            <td>
                <?php
                    $ordered_by= $prescription['ordered_by'];
                    echo mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$ordered_by'"))['Employee_Name'];
                ?>
            </td>
<!--            <td>
                <?php
                    $done_by= $prescription['done_by'];
//                    echo mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$done_by'"))['Employee_Name'];
                ?>
            </td>-->
<!--            <td><input type="button" class="btn btn-sm btn-success" value="EDIT" <?php if($prescription['status']=='Done'){}else{ echo 'onclick="edit_prescription('.$prescription_id.')"';}?>></td>-->
            <td><input type="button" class="btn btn-sm btn-primary" value="PROCESS" <?php if($prescription['status']=='Done'){echo 'disabled';}else{ echo 'onclick="showpatientprescription('.$prescription_id.')"';}?>></td>
            <?php if(isset($_GET['showActionBTN'])){ ?>
            <td><input type="button" class="btn btn-sm btn-danger" value="CANCEL" <?php if($prescription['status']=='Done'){echo 'disabled';}else{ echo 'onclick="prescription_done('.$prescription_id.')"';}?>></td>
            <?php } ?>

        </tr>
            <?php 
                $i++;
                }//end while
            }//end if ?>
    </table>

    <script>
        function prescription_done(param) {
            var status = confirm("Are you sure you want to save this? This action cannot be undone.");
                if (status == false) {
                    return false;
                } else {
                    $.ajax({
                        type : 'POST',
                        url : 'save_dialysis_prescription.php',
                        data : {prescription_id:param},

                        success: function (data) {
                            if(data=='ok'){
                                filterPreviousHemodialysisPrescriptions();
                                alert('Saved Successfully');
                            }else{
                                alert('An error occured, please try again'); 
                            }
                        }
                    });
                    return false;
                }
        }
    </script>