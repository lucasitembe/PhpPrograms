<?php  
    $index=1;
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $date_exam=$_POST['date_exam'];
    $sql="SELECT  op.VA_RE,op.VA_LE, op.VA_WPIN_RE, op.VA_WPIN_LE, op.IOP_RE, op.IOP_LE, op.VA_GLASSES_RE, op.VA_GLASSES_LE, op.date_exam,emp.Employee_Name,op.picture_note,op.trainee,op.optical_image,op.filled_status,op.a_scan,op.k_scan FROM optical_clinic as op,tbl_employee as emp WHERE op.Registration_ID='$Registration_ID' AND op.Employee_ID=emp.Employee_ID AND op.date_exam='$date_exam'";
    $query=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($query) > 0){
    while($row=mysqli_fetch_array($query)){
        $VA_RE=$row['VA_RE'];
        $VA_LE=$row['VA_LE'];
        $VA_WPIN_RE=$row['VA_WPIN_RE'];
        $VA_WPIN_LE=$row['VA_WPIN_LE'];
        $IOP_RE=$row['IOP_RE'];
        $IOP_LE=$row['IOP_LE'];
        $date_exam=$row['date_exam'];
        $VA_GLASSES_RE=$row['VA_GLASSES_RE'];
        $VA_GLASSES_LE=$row['VA_GLASSES_LE'];
        $Employee_Name=$row['Employee_Name'];
        $picture_note=$row['picture_note'];
        $trainee=$row['trainee'];
        $optical_image=$row['optical_image'];
        $filled_status=$row['filled_status'];
        $a_scan=$row['a_scan'];
        $k_scan=$row['k_scan'];
    ?>

<form  action="" enctype="multipart/form-data" autocomplete="off">
<div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <label>Assistance</label><input type="text" value="<?php echo $trainee;?>" readonly class="form-control">
</div>
        <table class="table table-striped table-hover" style="margin-top:25px;">
                
            <thead class='thead-dark'>
                <tr>
                    <th> PROCEDURE</th>
                    <th> RE</th>
                    <th> LE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>VA</td>
                    <td><?php echo $VA_RE;?></td>
                    <td><?php echo $VA_LE;?></td>
                </tr>
                <tr>
                    <td>VA W/PIN</td>
                    <td><?php echo $VA_WPIN_RE;?></td>
                    <td><?php echo $VA_WPIN_LE;?></td>
                </tr>
                <tr>
                    <td>VA W/GLASSES</td>
                    <td><?php echo $VA_GLASSES_RE;?></td>
                    <td><?php echo $VA_GLASSES_LE;?></td>
                </tr>
                <tr>
                    <td>IOP</td>
                    <td><?php echo $IOP_RE;?></td>
                    <td><?php echo $IOP_LE;?></label></td>
                </tr>
                <tr>
                    <td>A-SCAN</td>
                    <td colspan="2"><?php echo $a_scan;?></td>
                </tr>
                <tr>
                    <td>K-SCAN</td>
                    <td colspan="2"><?php echo $k_scan;?></td>
                </tr>
                <tr>
                    <!-- <td>Perfomed By <?php echo " ";?><?php echo $filled_status;?> </td> -->
                    <td>Perfomed By </td>
                    <td colspan="2"><?php echo $Employee_Name;?></td>
                </tr>
                <tr>
                    <td colspan='3'>
                    <?php
                        if(!empty($picture_note)){?>
                            <img style='width:930px; height:800px;' src='upload/<?php echo $picture_note;?>'>
                    <?php
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>
                    <?php
                        if(!empty($optical_image)){?>
                            <img style='width:930px; height:800px;' src='../optical_esign/patients_signature/<?php echo $optical_image;?>'>
                    <?php
                    
                        }else{
                            
                        }
                    ?>
                    </td>
                </tr>
            <tbody>
        </table>
        </form>
        <?php
    }
    }else{
        echo '<h3 style="text-align:center">No Result Found</h3>';
    }
    ?>