<style>
   table {
      background-color: #fff;
   }

   td {
      padding: 8px;
   }

   .filter {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      gap: 1em;
      margin-bottom: 1em
   }

   #table{
      /* overflow: scroll; */
      /* height: 60vh; */
      border-top: 2px solid rgba(34, 138, 170);
   }
</style>
<?php
    include("./includes/connection.php");
    $patient_name = $_GET['patient_name'];
    $patient_age = $_GET['patient_age'];
    $patient_gender = $_GET['patient_gender'];
    if (isset($_GET['admission_id'])) {
        $admission_id = $_GET['admission_id'];
      }
      
      if (isset($_GET['Registration_ID'])) {
       $Registration_ID = $_GET['Registration_ID'];
      }
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
      }else{
        $E_Name = '';
      }
      $fetal_heart_rate=mysqli_query($conn,"SELECT pd.fetal_heart_rate_cache_id, pd.x,pd.y, pd.date_time,emp.Employee_Name FROM tbl_fetal_heart_rate_cache as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id' ");
      ?>
      <fieldset style="overflow-y:scroll;height:550px;">
            </table>
            <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
            <thead style="background-color:#bdb5ac">
            <tr>
                <td style="font-size:20px;" width =25% ><b>Fetal Heart Rate</b></td>
                <td style="font-size:20px;"  width =25%><b>Time(Hrs)</b></td>
                <td style="font-size:20px;"  width =25%><b>Saved Time</b></td>
                <td style="font-size:20px;" width =25%><b>Saved By</b></td>
            </tr>
            </thead>
            <?php
            while ($row = mysqli_fetch_array($fetal_heart_rate)) {
                ?>
                <tr>
                    <td style='font-size:15px;' ><?php echo $row['x']?></td>
                    <td style='font-size:15px;'><?php echo $row['y'];?></td>
                    <td style='font-size:15px;'><?php echo $row['date_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
            <?php
            }
            ?>
            <?php
                $liqour_remark=mysqli_query($conn,"SELECT  pd.liqour_remark,pd.liqour_remark_time, pd.date_time,emp.Employee_Name FROM tbl_mould_liqour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Liqour Remark</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($liqour_remark)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['liqour_remark']?></td>
                    <td style='font-size:15px;'><?php echo $row['liqour_remark_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['date_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                $select_moulding=mysqli_query($conn,"SELECT  pd.moulding,pd.moulding_time, pd.date_time,emp.Employee_Name FROM tbl_moulding as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Moulding</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_moulding)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['moulding']?></td>
                    <td style='font-size:15px;'><?php echo $row['moulding_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['date_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                $select_contraction=mysqli_query($conn,"SELECT  pd.contraction,pd.c_time,pd.actual_time,emp.Employee_Name FROM tbl_contraction as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Contraction</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_contraction)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['contraction']?></td>
                    <td style='font-size:15px;'><?php echo $row['c_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
             <?php
                $select_oxytocine=mysqli_query($conn,"SELECT  pd.oxytocine,pd.oxytocine_time,pd.actual_oxytocine_time,emp.Employee_Name FROM tbl_oxytocine as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Oxyticin IU</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_oxytocine)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['oxytocine']?></td>
                    <td style='font-size:15px;'><?php echo $row['oxytocine_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_oxytocine_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                 $select_drops=mysqli_query($conn,"SELECT  pd.drops,pd.oxytocine_time,pd.actual_time,emp.Employee_Name FROM tbl_oxytocine_drops as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Drops/Minute Pulse</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_drops)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['drops']?></td>
                    <td style='font-size:15px;'><?php echo $row['oxytocine_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                 $select_temp=mysqli_query($conn,"SELECT  pd.temp,pd.tr_time,pd.actual_temp_resp_time,emp.Employee_Name FROM tbl_temp_resp as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Temperature</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_temp)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['temp']?></td>
                    <td style='font-size:15px;'><?php echo $row['tr_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_temp_resp_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                 $liqour_resp=mysqli_query($conn,"SELECT  pd.resp,pd.resp_time, pd.actual_resp_time,emp.Employee_Name FROM tbl_resp as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Respiratory</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($liqour_resp)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['resp']?></td>
                    <td style='font-size:15px;'><?php echo $row['resp_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_resp_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                $liqour_pressure=mysqli_query($conn,"SELECT  pd.pressure,pd.pressure_time, pd.actual_pressure_time,emp.Employee_Name FROM tbl_pressure as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>pressure</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($liqour_pressure)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['pressure']?></td>
                    <td style='font-size:15px;'><?php echo $row['pressure_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_pressure_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                $acetone=mysqli_query($conn,"SELECT pd.acetone, pd.acetone_time,pd.actual_acetone_time,emp.Employee_Name FROM tbl_acetone as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id' ");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Acetone</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($acetone)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['acetone']?></td>
                    <td style='font-size:15px;'><?php echo $row['acetone_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_acetone_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
               $volume=mysqli_query($conn,"SELECT pd.volume, pd.volume_time,pd.actual_volume_time,emp.Employee_Name FROM tbl_volume as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Volume</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($volume)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['volume']?></td>
                    <td style='font-size:15px;'><?php echo $row['volume_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_volume_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
             while ($row = mysqli_fetch_array($volume)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['volume']?></td>
                    <td style='font-size:15px;'><?php echo $row['volume_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_volume_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
               $tbl_urine=mysqli_query($conn,"SELECT pd.protein, pd.urine_time,pd.actual_urine_time,emp.Employee_Name FROM tbl_urine as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =25%><b>Protein</b></td>
                        <td style="font-size:20px;" width =25%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =25%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($tbl_urine)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['protein']?></td>
                    <td style='font-size:15px;'><?php echo $row['urine_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['actual_urine_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                 $select_progress_labour=mysqli_query($conn,"SELECT  pd.fx,pd.fy,pd.sx,pd.sy,pd.date_time,emp.Employee_Name FROM tbl_progress_of_labour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            ?>
           <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
           <thead style="background-color:#bdb5ac">
                <tr>
                    <tr>
                        <td style="font-size:20px;" width =20%><b>Cervical Dilation</b></td>
                        <td style="font-size:20px;" width =20%><b>Descent</b></td>
                        <td style="font-size:20px;" width =20%><b>Time(Hrs)</b></td>
                        <td style="font-size:20px;" width =20%><b>Saved Time</b></td>
                        <td style="font-size:20px;" width =20%><b>Saved By</b></td>
                    </tr>
                </tr>
            </thead>
            <?php
             while ($row = mysqli_fetch_array($select_progress_labour)) {
                ?>
                <tr>
                    <td style='font-size:15px;'><?php echo $row['fx']?></td>
                    <td style='font-size:15px;'><?php echo $row['sx'];?></td>
                    <td style='font-size:15px;'><?php echo $row['fy'];?></td>
                    <td style='font-size:15px;'><?php echo $row['date_time'];?></td>
                    <td style='font-size:15px;'><?php echo $row['Employee_Name'];?></td>
                </tr>
                <?php
            }
            ?>
            <?php
                 $data=mysqli_query($conn,"SELECT pd.date_birth, pd.weight,pd.weight,emp.Employee_Name,pd.weight,pd.sex,pd.apgar,pd.method_delivery,pd.first_stage,pd.second_stage,pd.third_stage,pd.placenta_membrane,pd.blood_loss,pd.fourth_stage,pd.reason_pph,pd.perineum,pd.repair_by,pd.delivery_by,pd.supervision_by,pd.save_date FROM summary_labour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.Registration_ID='$Registration_ID' AND admission_id='$admission_id'");
            ?>
            <center><table width =100% id="nurse_obsv" class="table table-striped table-hover" border=1 >
            <center><h3 style='background-color:#bdb5ac'>SUMMARY OF LABOUR</h3></center>
            <?php
             while ($row = mysqli_fetch_array($data)) {
                ?>
                <tr>
                <td style='font-size:15px;'><b>First Stage Duration</b></td>
                <td style='font-size:15px;'> <?php echo $row['first_stage'];?></td>
                <td style='font-size:15px;'><b>Second Stage Duration</b></td>
                <td style='font-size:15px;'><?php echo $row['second_stage'];?></td>
                <td style='font-size:15px;'><b>Third Stage Duration</b></td>
                <td style='font-size:15px;'><?php echo $row['third_stage']?></td>
                </tr>

                <tr >
                <td style='font-size:15px;'> <b>Fourth Stage Duration</b> </td>
                <td style='font-size:15px;'> <?php echo $row['fourth_stage'];?></td>
                <td style='font-size:15px;'> <b>Reason For PPH</b> </td>
                <td style='font-size:15px;'><?php echo $row['reason_pph'];?></td>
                <td style='font-size:15px;'><b>Perineum</b> </td>
                <td style='font-size:15px;'><?php echo $row['perineum'];?></td>
                </tr>

                <tr >
                <td style='font-size:15px;'><b><b>Placenta And Membranes</b> </td>
                <td style='font-size:15px;'><?php echo  $row['placenta_membrane'];?></td>
                <td style='font-size:15px;'><b>Blood Loss</b> </td>
                <td style='font-size:15px;'><?php echo $row['blood_loss'];?></td>
                <td style='font-size:15px;'><b>Repair By</b> </td>
                <td style='font-size:15px;'><?php echo $row['repair_by'];?></td>
                </tr>

                <tr >
                <td style='font-size:15px;'><b>Placenta And Membranes</b> </td>
                <td style='font-size:15px;'><?php echo  $row['placenta_membrane'];?></td>
                <td style='font-size:15px;'> <b>Blood Loss</b> </td>
                <td style='font-size:15px;'><?php echo $row['blood_loss'];?></td>
                <td style='font-size:15px;'><b>Repair By</b> </td>
                <td style='font-size:15px;'><?php echo $row['repair_by'];?></td>
                </tr>
                <tr >
                <td style='font-size:15px;'><b>Delivery By</b> </td>
                <td style='font-size:15px;'> <?php echo $row['delivery_by'];?></td>
                <td style='font-size:15px;'> <b><b>Supervision By</b> </td>
                <td style='font-size:15px;'><?php echo $row['supervision_by'];?></td>
                </tr>
                <?php
            }
            ?>
            </fieldset>

<input type="hidden" value="<?php echo $Registration_ID;?>" id="Registration_ID">
<input type="hidden" value="<?php echo $admission_id;?>" id="admission_id">